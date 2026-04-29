<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

$loadRooms = function (): array {
    $storedRooms = [];

    if (Storage::disk('local')->exists('rooms/list.json')) {
        $decoded = json_decode(Storage::disk('local')->get('rooms/list.json'), true);
        $storedRooms = collect($decoded['rooms'] ?? [])
            ->map(fn ($room) => [
                'id' => (string) ($room['id'] ?? ''),
                'name' => (string) ($room['name'] ?? 'Sala sem nome'),
                'player_one' => (string) ($room['player_one'] ?? 'Arcanista Zenith'),
                'player_two' => (string) ($room['player_two'] ?? 'Aguardando...'),
                'status' => (string) ($room['status'] ?? 'Aguardando oponente'),
                'allow_solo' => (bool) ($room['allow_solo'] ?? true),
                'solo_started' => (bool) ($room['solo_started'] ?? false),
            ])
            ->filter(fn ($room) => $room['id'] !== '')
            ->values()
            ->all();
    }

    return $storedRooms;
};

$resolveDeckStatus = function (): array {
    $banlist = [
        'banned' => ['16'],
        'limited' => ['22', '35'],
        'semiLimited' => ['45'],
    ];

    if (!Storage::disk('local')->exists('decks/main.js')) {
        return [
            'label' => 'validar baralho',
            'is_ok' => false,
        ];
    }

    $decoded = json_decode(Storage::disk('local')->get('decks/main.js'), true);
    $deck = collect($decoded['cards'] ?? [])
        ->map(function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'quantidade' => max(0, (int) ($item['quantidade'] ?? 0)),
            ];
        })
        ->filter(fn ($item) => $item['id'] !== '' && $item['quantidade'] > 0)
        ->values();

    if ($deck->isEmpty() || $deck->sum('quantidade') !== 40) {
        return [
            'label' => 'validar baralho',
            'is_ok' => false,
        ];
    }

    foreach ($deck as $item) {
        $normalizedId = (string) max(0, (int) ltrim($item['id'], '0'));
        $maxCopies = 3;

        if (in_array($normalizedId, $banlist['banned'], true)) {
            $maxCopies = 0;
        } elseif (in_array($normalizedId, $banlist['limited'], true)) {
            $maxCopies = 1;
        } elseif (in_array($normalizedId, $banlist['semiLimited'], true)) {
            $maxCopies = 2;
        }

        if ($item['quantidade'] > $maxCopies) {
            return [
                'label' => 'validar baralho',
                'is_ok' => false,
            ];
        }
    }

    return [
        'label' => 'ok',
        'is_ok' => true,
    ];
};

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/room', function () use ($resolveDeckStatus, $loadRooms) {
    return view('battle.room', [
        'deckStatus' => $resolveDeckStatus(),
        'rooms' => $loadRooms(),
    ]);
})->name('battle.room');


Route::get('/batalha', function () use ($resolveDeckStatus, $loadRooms) {
    return view('battle.room', [
        'deckStatus' => $resolveDeckStatus(),
        'rooms' => $loadRooms(),
    ]);
})-> name('sala-batalha');

Route::post('/room/create', function (Request $request) use ($resolveDeckStatus) {
    if (!($resolveDeckStatus()['is_ok'] ?? false)) {
        return redirect()->route('sala-batalha');
    }

    $validated = $request->validate([
        'room_name' => ['required', 'string', 'max:80'],
    ]);

    $room = [
        'id' => 'room-' . now()->format('YmdHis') . '-' . substr(md5($validated['room_name']), 0, 6),
        'name' => trim($validated['room_name']),
        'player_one' => 'Arcanista Zenith',
        'player_two' => 'Aguardando...',
        'status' => 'Aguardando oponente',
        'allow_solo' => true,
        'solo_started' => false,
        'created_at' => now()->toISOString(),
    ];

    $rooms = [];
    if (Storage::disk('local')->exists('rooms/list.json')) {
        $decoded = json_decode(Storage::disk('local')->get('rooms/list.json'), true);
        $rooms = collect($decoded['rooms'] ?? [])->values()->all();
    }

    array_unshift($rooms, $room);

    Storage::disk('local')->put('rooms/list.json', json_encode([
        'rooms' => $rooms,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    return redirect()->route('battle.waiting', [
        'room_id' => $room['id'],
    ]);
})->name('battle.room.create');

Route::get('/aguardar-oponente', function (Request $request) {
    $roomId = trim((string) $request->query('room_id', ''));
    $roomName = trim((string) $request->query('room_name', 'Sala sem nome'));
    $canStartSolo = true;

    if ($roomId !== '' && Storage::disk('local')->exists('rooms/list.json')) {
        $decoded = json_decode(Storage::disk('local')->get('rooms/list.json'), true);
        $foundRoom = collect($decoded['rooms'] ?? [])->first(fn ($room) => (string) ($room['id'] ?? '') === $roomId);

        if ($foundRoom) {
            $roomName = (string) ($foundRoom['name'] ?? $roomName);
            $canStartSolo = !((bool) ($foundRoom['solo_started'] ?? false));
        }
    }

    return view('battle.waiting', [
        'roomId' => $roomId,
        'roomName' => $roomName,
        'canStartSolo' => $canStartSolo,
    ]);
})->name('battle.waiting');

Route::get('/play', function (Request $request) {
    $roomId = trim((string) $request->query('room_id', ''));
    $mode = trim((string) $request->query('mode', 'solo'));
    $roomName = trim((string) $request->query('room_name', 'Sala de Teste'));
    $playerName = 'Arcanista Zenith';
    $opponentName = $mode === 'solo' ? 'Oponente de Teste' : 'Oponente';

    if ($roomId !== '' && $mode === 'solo' && Storage::disk('local')->exists('rooms/list.json')) {
        $decoded = json_decode(Storage::disk('local')->get('rooms/list.json'), true);
        $rooms = collect($decoded['rooms'] ?? [])
            ->map(function ($room) use ($roomId, &$roomName) {
                if ((string) ($room['id'] ?? '') === $roomId) {
                    $room['status'] = 'Jogando';
                    $room['solo_started'] = true;
                    $roomName = (string) ($room['name'] ?? $roomName);
                }

                return $room;
            })
            ->values()
            ->all();

        Storage::disk('local')->put('rooms/list.json', json_encode([
            'rooms' => $rooms,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    $typeCycle = ['criatura', 'comando', 'cenario'];
    $elementCycle = ['Fogo', 'Água', 'Terra', 'Ar', 'Luz', 'Trevas'];
    $rarityCycle = ['Comum', 'Incomum', 'Rara', 'Épica', 'Lendária'];

    $catalog = collect(glob(public_path('cards/*.{png,jpg,jpeg,webp,avif}'), GLOB_BRACE))
        ->sort()
        ->values()
        ->map(function ($path, $index) use ($typeCycle, $elementCycle, $rarityCycle) {
            $id = pathinfo($path, PATHINFO_FILENAME);
            $numericId = max(1, (int) ltrim($id, '0'));
            $type = $typeCycle[$index % count($typeCycle)];
            $isCreature = $type === 'criatura';

            return [
                'id' => $id,
                'nome' => 'Carta ' . $id,
                'tipo' => $type,
                'elemento' => $isCreature ? $elementCycle[$index % count($elementCycle)] : null,
                'raridade' => $rarityCycle[$index % count($rarityCycle)],
                'efeito' => $isCreature
                    ? 'Habilidade temporária da criatura ' . $id . '.'
                    : 'Efeito temporário da carta ' . $id . '.',
                'imagem' => asset('cards/' . basename($path)),
            ];
        })
        ->keyBy('id');

    $deckPool = collect();
    if (Storage::disk('local')->exists('decks/main.js')) {
        $decodedDeck = json_decode(Storage::disk('local')->get('decks/main.js'), true);
        $deckPool = collect($decodedDeck['cards'] ?? [])
            ->flatMap(function ($entry) use ($catalog) {
                $id = (string) ($entry['id'] ?? '');
                $quantity = max(0, (int) ($entry['quantidade'] ?? 0));
                $card = $catalog->get($id);

                if (!$card || $quantity <= 0) {
                    return [];
                }

                return collect(range(1, $quantity))->map(function ($copyIndex) use ($card, $id) {
                    return [
                        ...$card,
                        'uid' => $id . '-copy-' . $copyIndex,
                    ];
                });
            })
            ->shuffle()
            ->values();
    }

    if ($deckPool->isEmpty()) {
        $deckPool = $catalog->values()->take(10)->values()->map(function ($card, $index) {
            return [
                ...$card,
                'uid' => $card['id'] . '-sample-' . ($index + 1),
            ];
        });
    }

    $openingHand = $deckPool->take(5)->values()->all();
    $drawPile = $deckPool->slice(5)->values()->all();
    $remainingDeckCount = max(0, $deckPool->count() - count($openingHand));

    return view('battle.play', [
        'mode' => $mode,
        'roomName' => $roomName,
        'playerName' => $playerName,
        'opponentName' => $opponentName,
        'openingHand' => $openingHand,
        'drawPile' => $drawPile,
        'remainingDeckCount' => $remainingDeckCount,
    ]);
})->name('battle.play');


Route::get('/deck', function () {
    $typeCycle = ['criatura', 'comando', 'cenario'];
    $elementCycle = ['Fogo', 'Água', 'Terra', 'Ar', 'Luz', 'Trevas'];
    $rarityCycle = ['Comum', 'Incomum', 'Rara', 'Épica', 'Lendária'];
    $savedDeck = [];

    $cards = collect(glob(public_path('cards/*.{png,jpg,jpeg,webp,avif}'), GLOB_BRACE))
        ->sort()
        ->values()
        ->map(function ($path, $index) use ($typeCycle, $elementCycle, $rarityCycle) {
            $id = pathinfo($path, PATHINFO_FILENAME);
            $numericId = max(1, (int) ltrim($id, '0'));
            $type = $typeCycle[$index % count($typeCycle)];
            $isCreature = $type === 'criatura';

            return [
                'id' => $id,
                'nome' => 'Carta ' . $id,
                'tipo' => $type,
                'elemento' => $isCreature ? $elementCycle[$index % count($elementCycle)] : null,
                'raridade' => $rarityCycle[$index % count($rarityCycle)],
                'efeito' => $isCreature
                    ? 'Habilidade temporária da criatura ' . $id . '. Futuramente virá do banco de dados.'
                    : 'Efeito temporário da carta ' . $id . '. Futuramente virá do banco de dados.',
                'ataque' => $isCreature ? 8 + ($numericId % 9) : null,
                'vida' => $isCreature ? 12 + ($numericId % 11) : null,
                'imagem' => asset('cards/' . basename($path)),
            ];
        });

    if (Storage::disk('local')->exists('decks/main.js')) {
        $decoded = json_decode(Storage::disk('local')->get('decks/main.js'), true);
        $savedDeck = collect($decoded['cards'] ?? [])
            ->map(fn ($item) => [
                'id' => (string) ($item['id'] ?? ''),
                'quantidade' => (int) ($item['quantidade'] ?? 0),
            ])
            ->filter(fn ($item) => $item['id'] !== '' && $item['quantidade'] > 0)
            ->values()
            ->all();
    }

    return view('deck.index', compact('cards', 'savedDeck'));
})-> name('deck');

Route::post('/deck/save', function (Request $request) {
    $banlist = [
        'banned' => ['16'],
        'limited' => ['22', '35'],
        'semiLimited' => ['45'],
    ];

    $deck = collect($request->input('deck', []))
        ->map(function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'quantidade' => max(0, (int) ($item['quantidade'] ?? 0)),
            ];
        })
        ->filter(fn ($item) => $item['id'] !== '' && $item['quantidade'] > 0)
        ->values();

    $totalCards = $deck->sum('quantidade');

    if ($totalCards !== 40) {
        return response()->json([
            'message' => 'O baralho precisa ter exatamente 40 cartas para ser salvo.',
        ], 422);
    }

    foreach ($deck as $item) {
        $normalizedId = (string) max(0, (int) ltrim($item['id'], '0'));
        $maxCopies = 3;

        if (in_array($normalizedId, $banlist['banned'], true)) {
            $maxCopies = 0;
        } elseif (in_array($normalizedId, $banlist['limited'], true)) {
            $maxCopies = 1;
        } elseif (in_array($normalizedId, $banlist['semiLimited'], true)) {
            $maxCopies = 2;
        }

        if ($item['quantidade'] > $maxCopies) {
            return response()->json([
                'message' => 'A carta ' . $item['id'] . ' excede o limite permitido pela banlist.',
            ], 422);
        }
    }

    Storage::disk('local')->put('decks/main.js', json_encode([
        'saved_at' => now()->toISOString(),
        'cards' => $deck->values()->all(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    return response()->json([
        'message' => 'Baralho salvo com sucesso no arquivo storage/app/decks/main.js.',
    ]);
})->name('deck.save');

Route::get('/shop', function () {
    return view('shop.index');
})-> name('shop');

Route::get('/ranking', function () {
    return view('ranking.index');
})-> name('ranking');

Route::get('/profile', function () {
    return view('profile.index');
})-> name('profile');