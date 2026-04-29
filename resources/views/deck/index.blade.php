@extends('layout.dash')

@section('title', 'Construtor de Baralho')

@section('page_styles')
	<style>
		.deck-page {
			height: 100%;
			display: flex;
			flex-direction: column;
			gap: 1rem;
			overflow: hidden;
		}

		.deck-toolbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 1rem;
			flex-wrap: nowrap;
		}

		.deck-toolbar-filters {
			flex: 1;
			display: grid;
			grid-template-columns: minmax(220px, 1.5fr) repeat(3, minmax(140px, 0.75fr));
			gap: 0.75rem;
			align-items: end;
		}

		.deck-layout {
			flex: 1;
			min-height: 0;
			display: grid;
			grid-template-columns: minmax(320px, 1.25fr) minmax(300px, 1fr) minmax(280px, 0.95fr);
			gap: 1rem;
			overflow: hidden;
		}

		.deck-panel {
			min-width: 0;
			min-height: 0;
			display: flex;
			flex-direction: column;
			background: rgba(255, 255, 255, 0.025);
			border: 1px solid rgba(255, 255, 255, 0.08);
			border-radius: 20px;
			overflow: hidden;
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03);
		}

		.deck-panel-header {
			padding: 1rem 1rem 0.75rem;
			border-bottom: 1px solid rgba(255, 255, 255, 0.06);
		}

		.deck-panel-body {
			flex: 1;
			min-height: 0;
			padding: 1rem;
			overflow: hidden;
		}

		.deck-filters {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 0.75rem;
		}

		.deck-search {
			grid-column: auto;
		}

		.deck-label {
			display: block;
			font-size: 0.78rem;
			color: var(--ez-muted);
			margin-bottom: 0.35rem;
			text-transform: uppercase;
			letter-spacing: 0.08em;
		}

		.deck-input,
		.deck-select {
			width: 100%;
			border-radius: 12px;
			border: 1px solid rgba(255, 255, 255, 0.08);
			background: rgba(255, 255, 255, 0.03);
			color: var(--ez-text);
			padding: 0.75rem 0.85rem;
		}

		.deck-select option {
			background: #1a1d28;
			color: #fff;
		}

		.deck-cards-grid {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 0.75rem;
			overflow-y: auto;
			padding-right: 0.25rem;
			min-height: 0;
		}

		.deck-thumb {
			position: relative;
			border-radius: 14px;
			overflow: hidden;
			border: 1px solid rgba(255, 255, 255, 0.08);
			background: rgba(255, 255, 255, 0.02);
			aspect-ratio: 0.72 / 1;
			cursor: pointer;
		}

		.deck-thumb img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}

		.deck-thumb-overlay {
			position: absolute;
			inset: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(180deg, rgba(0, 0, 0, 0.12), rgba(0, 0, 0, 0.55));
			opacity: 0;
			transition: opacity 0.2s ease;
		}

		.deck-thumb:hover .deck-thumb-overlay,
		.deck-thumb.is-active .deck-thumb-overlay {
			opacity: 1;
		}

		.deck-thumb-zoom {
			width: 52px;
			height: 52px;
			border-radius: 50%;
			border: 0;
			background: rgba(255, 255, 255, 0.9);
			color: #111;
			font-size: 1.35rem;
			box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
		}

		.deck-thumb-status {
			position: absolute;
			top: 0.5rem;
			left: 0.5rem;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 32px;
			height: 32px;
			padding: 0 0.55rem;
			border-radius: 999px;
			background: rgba(11, 14, 24, 0.88);
			border: 1px solid rgba(255, 255, 255, 0.12);
			color: #fff;
			font-size: 0.85rem;
			font-weight: 700;
			z-index: 2;
			box-shadow: 0 8px 18px rgba(0, 0, 0, 0.28);
		}

		.deck-thumb-status.is-banned {
			background: rgba(128, 20, 20, 0.92);
			border-color: rgba(255, 120, 120, 0.35);
		}

		.deck-thumb-status.is-limited {
			background: rgba(151, 110, 16, 0.92);
			border-color: rgba(255, 217, 102, 0.35);
		}

		.deck-thumb-status.is-semi-limited {
			background: rgba(39, 54, 88, 0.92);
			border-color: rgba(124, 160, 255, 0.35);
		}

		.deck-pagination {
			padding-top: 0.75rem;
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 0.75rem;
			flex-wrap: wrap;
		}

		.deck-reader-image {
			width: 100%;
			max-height: 300px;
			object-fit: contain;
			border-radius: 16px;
			background: rgba(255, 255, 255, 0.03);
			border: 1px solid rgba(255, 255, 255, 0.07);
			margin-bottom: 0.85rem;
		}

		.deck-effect-box {
			margin-top: 0.5rem;
			padding: 0.85rem 0.95rem;
			border-radius: 14px;
			background: rgba(255, 255, 255, 0.04);
			border: 1px solid rgba(255, 255, 255, 0.07);
		}

		.deck-builder-list {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.65rem;
			overflow-y: auto;
			min-height: 0;
			align-content: start;
			padding-right: 0.25rem;
		}

		.deck-builder-item {
			position: relative;
			min-width: 0;
		}

		.deck-builder-card {
			position: relative;
			width: 100%;
		}

		.deck-builder-image-wrap {
			position: relative;
			z-index: 1;
			cursor: pointer;
			border-radius: 8px;
			overflow: hidden;
		}

		.deck-builder-image-wrap::after {
			content: '';
			position: absolute;
			inset: 0;
			background: linear-gradient(180deg, rgba(0, 0, 0, 0.08), rgba(0, 0, 0, 0.48));
			opacity: 0;
			transition: opacity 0.18s ease;
		}

		.deck-builder-image-wrap:hover::after {
			opacity: 1;
		}

		.deck-builder-thumb {
			width: 100%;
			height: auto;
			aspect-ratio: 0.72 / 1;
			border-radius: 8px;
			object-fit: cover;
			display: block;
		}

		.deck-remove-floating {
			position: absolute;
			top: -8px;
			left: 6px;
			z-index: 3;
			width: 24px;
			height: 24px;
			border-radius: 999px;
			border: 0;
			background: rgba(140, 20, 20, 0.72);
			color: #fff;
			font-weight: 700;
			line-height: 1;
			box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
		}

		.deck-remove-floating:hover {
			background: rgba(170, 24, 24, 0.88);
		}

		.deck-builder-count {
			position: absolute;
			right: 6px;
			bottom: 6px;
			z-index: 3;
			min-width: 24px;
			height: 24px;
			padding: 0 0.45rem;
			border-radius: 999px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: rgba(8, 11, 20, 0.72);
			color: #fff;
			font-size: 0.78rem;
			font-weight: 700;
			box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
		}

		.deck-builder-zoom {
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			z-index: 3;
			width: 42px;
			height: 42px;
			border-radius: 999px;
			border: 0;
			background: rgba(255, 255, 255, 0.90);
			color: #111;
			font-size: 1.1rem;
			opacity: 0;
			transition: opacity 0.18s ease, transform 0.18s ease;
		}

		.deck-builder-image-wrap:hover .deck-builder-zoom {
			opacity: 1;
			transform: translate(-50%, -50%) scale(1.03);
		}

		.deck-save-status {
			font-size: 0.82rem;
			color: var(--ez-muted);
			text-align: left;
		}

		.deck-muted {
			color: var(--ez-muted);
		}

		.deck-empty {
			border: 1px dashed rgba(255, 255, 255, 0.12);
			border-radius: 16px;
			padding: 1.25rem;
			text-align: center;
			color: var(--ez-muted);
		}

		.deck-ban-badge {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 0.35rem;
			padding: 0.35rem 0.65rem;
			border-radius: 999px;
			font-size: 0.78rem;
			font-weight: 700;
		}

		.deck-ban-badge.is-banned {
			background: rgba(128, 20, 20, 0.22);
			color: #ffb4b4;
			border: 1px solid rgba(255, 120, 120, 0.28);
		}

		.deck-ban-badge.is-limited {
			background: rgba(151, 110, 16, 0.22);
			color: #ffe08b;
			border: 1px solid rgba(255, 217, 102, 0.28);
		}

		.deck-ban-badge.is-semi-limited {
			background: rgba(39, 54, 88, 0.24);
			color: #c5d4ff;
			border: 1px solid rgba(124, 160, 255, 0.28);
		}

		.deck-ban-badge.is-free {
			background: rgba(255, 255, 255, 0.05);
			color: #d8dce8;
			border: 1px solid rgba(255, 255, 255, 0.08);
		}

		@media (max-width: 1399.98px) {
			.deck-toolbar-filters {
				grid-template-columns: minmax(220px, 1.3fr) repeat(3, minmax(120px, 0.7fr));
			}

			.deck-layout {
				grid-template-columns: minmax(320px, 1.25fr) minmax(300px, 1fr) minmax(260px, 0.9fr);
			}

			.deck-cards-grid {
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}
		}

		@media (max-width: 1199.98px) {
			.deck-toolbar {
				flex-wrap: wrap;
			}

			.deck-toolbar-filters {
				grid-template-columns: 1fr 1fr;
			}

			.deck-search {
				grid-column: 1 / -1;
			}

			.deck-layout {
				grid-template-columns: 1fr;
				overflow-y: auto;
			}

			.deck-page {
				overflow: auto;
			}

			.deck-cards-grid,
			.deck-builder-list {
				max-height: 420px;
			}

			.deck-builder-list {
				grid-template-columns: repeat(4, minmax(0, 1fr));
			}
		}

		@media (max-width: 767.98px) {
			.deck-builder-list {
				grid-template-columns: repeat(3, minmax(0, 1fr));
			}
		}
	</style>
@endsection

@section('content')
	<div class="deck-page">
		<div class="deck-toolbar">
			<div class="deck-toolbar-filters deck-filters mb-0">
				<div class="deck-search">
					<label class="deck-label" for="cardSearch">Buscar carta</label>
					<input id="cardSearch" type="text" class="deck-input" placeholder="Buscar por nome ou ID">
				</div>

				<div>
					<label class="deck-label" for="cardTypeFilter">Tipo</label>
					<select id="cardTypeFilter" class="deck-select">
						<option value="">Todos</option>
						<option value="criatura">Criatura</option>
						<option value="comando">Comando</option>
						<option value="cenario">Cenário</option>
					</select>
				</div>

				<div>
					<label class="deck-label" for="cardRarityFilter">Raridade</label>
					<select id="cardRarityFilter" class="deck-select">
						<option value="">Todas</option>
						<option value="Comum">Comum</option>
						<option value="Incomum">Incomum</option>
						<option value="Rara">Rara</option>
						<option value="Épica">Épica</option>
						<option value="Lendária">Lendária</option>
					</select>
				</div>

				<div>
					<label class="deck-label" for="cardElementFilter">Elemento</label>
					<select id="cardElementFilter" class="deck-select">
						<option value="">Todos</option>
						<option value="Fogo">Fogo</option>
						<option value="Água">Água</option>
						<option value="Terra">Terra</option>
						<option value="Ar">Ar</option>
						<option value="Luz">Luz</option>
						<option value="Trevas">Trevas</option>
						<option value="null">Não se aplica</option>
					</select>
				</div>
			</div>

			<a href="{{ url('/') }}" class="btn btn-outline-light">Voltar ao dashboard</a>
		</div>

		<div class="deck-layout" id="deckApp">
			<section class="deck-panel">
				<div class="deck-panel-body d-flex flex-column">
					<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 small deck-muted">
						<span id="cardsCounter">0 cartas encontradas</span>
						<span id="cardsPageIndicator">Página 1 de 1</span>
					</div>

					<div class="deck-cards-grid" id="cardsGrid">
						@forelse ($cards as $card)
							<article
								class="deck-thumb"
								data-card='@json($card)'
								data-id="{{ $card['id'] }}"
								data-name="{{ strtolower($card['nome']) }}"
								data-type="{{ $card['tipo'] }}"
								data-rarity="{{ $card['raridade'] }}"
								data-element="{{ $card['elemento'] ?? 'null' }}"
							>
								@php
									$normalizedId = (string) max(0, (int) ltrim((string) $card['id'], '0'));
								@endphp
								@if (in_array($normalizedId, ['16'], true))
									<span class="deck-thumb-status is-banned" title="Banida • 0 cópias">⛔</span>
								@elseif (in_array($normalizedId, ['22', '35'], true))
									<span class="deck-thumb-status is-limited" title="Limitada • 1 cópia">1</span>
								@elseif (in_array($normalizedId, ['45'], true))
									<span class="deck-thumb-status is-semi-limited" title="Semi-limitada • 2 cópias">2</span>
								@endif
								<img src="{{ $card['imagem'] }}" alt="{{ $card['nome'] }}">

								<div class="deck-thumb-overlay">
									<button type="button" class="deck-thumb-zoom" aria-label="Ler carta">🔍</button>
								</div>
							</article>
						@empty
							<div class="alert alert-warning mb-0">
								Nenhuma imagem foi encontrada em <strong>public/cards</strong>.
							</div>
						@endforelse
					</div>

					<div class="deck-pagination">
						<button type="button" class="btn btn-outline-light btn-sm" id="prevPageBtn">Anterior</button>
						<button type="button" class="btn btn-outline-light btn-sm" id="nextPageBtn">Próxima</button>
					</div>
				</div>
			</section>

			<section class="deck-panel">
				<div class="deck-panel-body" id="readerPanel">
					<div class="deck-empty" id="readerEmpty">Selecione uma carta no catálogo para ver os detalhes aqui.</div>

					<div id="readerContent" class="d-none">
						<img id="readerImage" class="deck-reader-image" src="" alt="">
						<div class="mb-3">
							<div>
								<h3 class="h4 mb-0" id="readerName"></h3>
							</div>
						</div>

						<div class="deck-effect-box">
							<div class="deck-label mb-1">Efeito</div>
							<p class="deck-muted mb-0" id="readerEffect"></p>
						</div>

						<div class="mt-4 d-grid">
							<button type="button" class="btn btn-warning" id="addToDeckBtn">Adicionar ao baralho</button>
							<div class="small deck-muted mt-2 text-center" id="addToDeckHint"></div>
						</div>
					</div>
				</div>
			</section>

			<section class="deck-panel">
				<div class="deck-panel-header">
					<div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-2">
						<h2 class="h5 mb-0">Baralho Principal <span class="text-warning" id="deckCountTitle">0/40</span></h2>
					</div>

					<div class="d-flex align-items-center gap-2 flex-wrap">
						<button type="button" class="btn btn-outline-light btn-sm" id="clearDeckBtn">Limpar</button>
						<button type="button" class="btn btn-warning btn-sm" id="saveDeckBtn">Salvar baralho</button>
					</div>
				</div>

				<div class="deck-panel-body d-flex flex-column">
					<div class="deck-save-status mb-3" id="deckSaveStatus">Adicione 40 cartas para habilitar o salvamento.</div>

					<div class="deck-builder-list" id="deckBuilderList">
						<div class="deck-empty" id="deckEmpty">Nenhuma carta adicionada ao baralho ainda.</div>
					</div>
				</div>
			</section>
		</div>
	</div>
@endsection

@section('page_scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const SAVED_DECK = @json($savedDeck ?? []);
			const BANLIST = {
				banned: ['16'],
				limited: ['22', '35'],
				semiLimited: ['45'],
			};

			const allCards = @json(collect($cards)->values()).map((card) => {
				const normalizedId = normalizeId(card.id);
				const restriction = getRestriction(normalizedId);

				return {
					...card,
					normalizedId,
					restriction,
					maxCopies: getMaxCopies(restriction),
				};
			});
			const state = {
				cards: allCards,
				filtered: allCards,
				selected: allCards[0] ?? null,
				deck: [],
				page: 1,
				perPage: 9,
			};

			const elements = {
				search: document.getElementById('cardSearch'),
				type: document.getElementById('cardTypeFilter'),
				rarity: document.getElementById('cardRarityFilter'),
				element: document.getElementById('cardElementFilter'),
				cardsGrid: document.getElementById('cardsGrid'),
				cardsCounter: document.getElementById('cardsCounter'),
				cardsPageIndicator: document.getElementById('cardsPageIndicator'),
				prevPageBtn: document.getElementById('prevPageBtn'),
				nextPageBtn: document.getElementById('nextPageBtn'),
				readerEmpty: document.getElementById('readerEmpty'),
				readerContent: document.getElementById('readerContent'),
				readerImage: document.getElementById('readerImage'),
				readerName: document.getElementById('readerName'),
				readerEffect: document.getElementById('readerEffect'),
				addToDeckBtn: document.getElementById('addToDeckBtn'),
				addToDeckHint: document.getElementById('addToDeckHint'),
				deckBuilderList: document.getElementById('deckBuilderList'),
				deckEmpty: document.getElementById('deckEmpty'),
				deckCountTitle: document.getElementById('deckCountTitle'),
				deckSaveStatus: document.getElementById('deckSaveStatus'),
				clearDeckBtn: document.getElementById('clearDeckBtn'),
				saveDeckBtn: document.getElementById('saveDeckBtn'),
			};

			function normalize(value) {
				return (value ?? '').toString().toLowerCase();
			}

			function normalizeId(value) {
				const cleaned = String(value ?? '').replace(/^0+/, '');
				return cleaned || '0';
			}

			function getRestriction(normalizedId) {
				if (BANLIST.banned.includes(normalizedId)) return 'banned';
				if (BANLIST.limited.includes(normalizedId)) return 'limited';
				if (BANLIST.semiLimited.includes(normalizedId)) return 'semi-limited';
				return 'free';
			}

			function getMaxCopies(restriction) {
				switch (restriction) {
					case 'banned':
						return 0;
					case 'limited':
						return 1;
					case 'semi-limited':
						return 2;
					default:
						return 3;
				}
			}

			function getRestrictionBadge(card) {
				switch (card.restriction) {
					case 'banned':
						return {
							label: '⛔',
							text: 'Banida • 0 cópias',
							className: 'is-banned',
						};
					case 'limited':
						return {
							label: '1',
							text: 'Limitada • 1 cópia',
							className: 'is-limited',
						};
					case 'semi-limited':
						return {
							label: '2',
							text: 'Semi-limitada • 2 cópias',
							className: 'is-semi-limited',
						};
					default:
						return {
							label: '3',
							text: 'Livre • 3 cópias',
							className: 'is-free',
						};
				}
			}

			function getCopiesInDeck(cardId) {
				return state.deck.find((item) => item.id === cardId)?.quantidade ?? 0;
			}

			function hydrateSavedDeck(entries) {
				return entries.reduce((acc, entry) => {
					const card = state.cards.find((item) => item.id === entry.id);
					if (!card || card.maxCopies === 0) return acc;

					const quantidade = Math.min(Number(entry.quantidade || 0), card.maxCopies);
					if (quantidade <= 0) return acc;

					acc.push({ ...card, quantidade });
					return acc;
				}, []);
			}

			function applyFilters() {
				const search = normalize(elements.search.value);
				const type = elements.type.value;
				const rarity = elements.rarity.value;
				const element = elements.element.value;

				state.filtered = state.cards.filter((card) => {
					const matchesSearch = !search
						|| normalize(card.nome).includes(search)
						|| normalize(card.id).includes(search);
					const matchesType = !type || card.tipo === type;
					const matchesRarity = !rarity || card.raridade === rarity;
					const cardElement = card.elemento ?? 'null';
					const matchesElement = !element || cardElement === element;

					return matchesSearch && matchesType && matchesRarity && matchesElement;
				});

				const totalPages = Math.max(1, Math.ceil(state.filtered.length / state.perPage));
				state.page = Math.min(state.page, totalPages);
				if (state.page < 1) state.page = 1;

				if (!state.filtered.find((card) => card.id === state.selected?.id)) {
					state.selected = state.filtered[0] ?? null;
				}

				renderCards();
				renderReader();
			}

			function getVisibleCards() {
				const start = (state.page - 1) * state.perPage;
				return state.filtered.slice(start, start + state.perPage);
			}

			function renderCards() {
				const visible = getVisibleCards();
				const totalPages = Math.max(1, Math.ceil(state.filtered.length / state.perPage));

				elements.cardsCounter.textContent = `${state.filtered.length} carta(s) encontrada(s)`;
				elements.cardsPageIndicator.textContent = `Página ${state.page} de ${totalPages}`;
				elements.prevPageBtn.disabled = state.page === 1;
				elements.nextPageBtn.disabled = state.page === totalPages;

				if (!visible.length) {
					elements.cardsGrid.innerHTML = '<div class="deck-empty">Nenhuma carta corresponde aos filtros atuais.</div>';
					return;
				}

				elements.cardsGrid.innerHTML = visible.map((card) => `
					<article class="deck-thumb ${state.selected?.id === card.id ? 'is-active' : ''}" data-id="${card.id}">
						${(() => {
							if (card.restriction === 'free') return '';
							const badge = getRestrictionBadge(card);
							return `<span class="deck-thumb-status ${badge.className}" title="${badge.text}">${badge.label}</span>`;
						})()}
						<img src="${card.imagem}" alt="${card.nome}">
						<div class="deck-thumb-overlay">
							<button type="button" class="deck-thumb-zoom" aria-label="Ler carta" data-id="${card.id}">🔍</button>
						</div>
					</article>
				`).join('');

				elements.cardsGrid.querySelectorAll('.deck-thumb, .deck-thumb-zoom').forEach((item) => {
					item.addEventListener('click', (event) => {
						const id = event.currentTarget.dataset.id || event.currentTarget.closest('.deck-thumb')?.dataset.id;
						if (!id) return;
						state.selected = state.cards.find((card) => card.id === id) ?? null;
						renderCards();
						renderReader();
					});
				});
			}

			function renderReader() {
				const card = state.selected;

				if (!card) {
					elements.readerEmpty.classList.remove('d-none');
					elements.readerContent.classList.add('d-none');
					return;
				}

				elements.readerEmpty.classList.add('d-none');
				elements.readerContent.classList.remove('d-none');

				elements.readerImage.src = card.imagem;
				elements.readerImage.alt = card.nome;
				elements.readerName.textContent = card.nome;
				elements.readerEffect.textContent = card.efeito;

				const currentCopies = getCopiesInDeck(card.id);
				const canAdd = currentCopies < card.maxCopies && card.maxCopies > 0;
				elements.addToDeckBtn.disabled = !canAdd;
				elements.addToDeckHint.textContent = card.maxCopies === 0
					? 'Carta banida: não pode ser adicionada ao baralho.'
					: `Máximo permitido: ${card.maxCopies} cópia(s). Atualmente no baralho: ${currentCopies}.`;

			}

			function renderDeck() {
				const total = state.deck.reduce((sum, item) => sum + item.quantidade, 0);
				elements.deckCountTitle.textContent = `${total}/40`;
				elements.saveDeckBtn.disabled = total !== 40;
				elements.deckSaveStatus.textContent = total === 40
					? 'Baralho pronto para salvar.'
					: `Seu baralho precisa ter exatamente 40 cartas. Atual: ${total}.`;

				if (!state.deck.length) {
					elements.deckBuilderList.innerHTML = '<div class="deck-empty" id="deckEmpty">Nenhuma carta adicionada ao baralho ainda.</div>';
					return;
				}

				elements.deckBuilderList.innerHTML = state.deck.map((item) => `
					<div class="deck-builder-item">
						<div class="deck-builder-card">
							<button type="button" class="deck-remove-floating deck-remove-btn" data-id="${item.id}" aria-label="Remover carta">-</button>
							<div class="deck-builder-image-wrap deck-builder-preview" data-id="${item.id}">
								<button type="button" class="deck-builder-zoom" data-id="${item.id}" aria-label="Visualizar carta">🔍</button>
								<img src="${item.imagem}" alt="${item.nome}" class="deck-builder-thumb" title="${item.nome} • ${item.quantidade} cópia(s)">
								<span class="deck-builder-count">${item.quantidade}</span>
							</div>
						</div>
					</div>
				`).join('');

				elements.deckBuilderList.querySelectorAll('.deck-remove-btn').forEach((button) => {
					button.addEventListener('click', () => removeFromDeck(button.dataset.id));
				});

				elements.deckBuilderList.querySelectorAll('.deck-builder-preview, .deck-builder-zoom').forEach((item) => {
					item.addEventListener('click', (event) => {
						if (event.currentTarget.classList.contains('deck-builder-zoom')) {
							event.stopPropagation();
						}

						const id = event.currentTarget.dataset.id;
						if (!id) return;

						state.selected = state.cards.find((card) => card.id === id) ?? null;
						renderCards();
						renderReader();
					});
				});

				renderReader();
			}

			function addToDeck() {
				if (!state.selected) return;
				const total = state.deck.reduce((sum, item) => sum + item.quantidade, 0);
				if (total >= 40) return;
				if (state.selected.maxCopies === 0) return;

				const existing = state.deck.find((item) => item.id === state.selected.id);
				if (existing && existing.quantidade >= state.selected.maxCopies) return;
				if (existing) {
					existing.quantidade += 1;
				} else {
					state.deck.push({ ...state.selected, quantidade: 1 });
				}

				renderDeck();
			}

			function removeFromDeck(id) {
				const target = state.deck.find((item) => item.id === id);
				if (!target) return;

				if (target.quantidade > 1) {
					target.quantidade -= 1;
				} else {
					state.deck = state.deck.filter((item) => item.id !== id);
				}

				renderDeck();
			}

			async function saveDeck() {
				const total = state.deck.reduce((sum, item) => sum + item.quantidade, 0);
				if (total !== 40) {
					elements.deckSaveStatus.textContent = 'O baralho precisa ter exatamente 40 cartas para ser salvo.';
					return;
				}

				elements.saveDeckBtn.disabled = true;
				elements.deckSaveStatus.textContent = 'Salvando baralho...';

				try {
					const response = await fetch('{{ route('deck.save') }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'Accept': 'application/json',
							'X-CSRF-TOKEN': '{{ csrf_token() }}',
						},
						body: JSON.stringify({
							deck: state.deck.map((item) => ({
								id: item.id,
								quantidade: item.quantidade,
							})),
						}),
					});

					const data = await response.json();
					if (!response.ok) {
						throw new Error(data.message || 'Não foi possível salvar o baralho.');
					}

					elements.deckSaveStatus.textContent = data.message;
				} catch (error) {
					elements.deckSaveStatus.textContent = error.message;
				} finally {
					renderDeck();
				}
			}

			elements.search.addEventListener('input', () => {
				state.page = 1;
				applyFilters();
			});

			[elements.type, elements.rarity, elements.element].forEach((field) => {
				field.addEventListener('change', () => {
					state.page = 1;
					applyFilters();
				});
			});

			elements.prevPageBtn.addEventListener('click', () => {
				if (state.page > 1) {
					state.page -= 1;
					renderCards();
				}
			});

			elements.nextPageBtn.addEventListener('click', () => {
				const totalPages = Math.max(1, Math.ceil(state.filtered.length / state.perPage));
				if (state.page < totalPages) {
					state.page += 1;
					renderCards();
				}
			});

			elements.addToDeckBtn.addEventListener('click', addToDeck);
			elements.clearDeckBtn.addEventListener('click', () => {
				state.deck = [];
				renderDeck();
			});
			elements.saveDeckBtn.addEventListener('click', saveDeck);

			state.deck = hydrateSavedDeck(SAVED_DECK);
			applyFilters();
			renderDeck();
		});
	</script>
@endsection

