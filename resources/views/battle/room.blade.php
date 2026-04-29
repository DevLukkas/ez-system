@extends('layout.dash')

@section('title', 'Salas de Partida')

@section('page_styles')
	<style>
		.room-page {
			height: 100%;
			display: flex;
			flex-direction: column;
			gap: 1rem;
		}

		.room-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 1rem;
			flex-wrap: wrap;
		}

		.room-switcher {
			display: inline-flex;
			gap: 0.75rem;
			flex-wrap: wrap;
		}

		.room-switcher-btn {
			padding: 0.9rem 1.35rem;
			border-radius: 14px;
			border: 1px solid rgba(255, 255, 255, 0.1);
			background: rgba(255, 255, 255, 0.04);
			color: var(--ez-text);
			text-decoration: none;
			font-weight: 700;
			letter-spacing: 0.04em;
			transition: all 0.2s ease;
		}

		.room-switcher-btn.is-active {
			background: linear-gradient(135deg, rgba(212, 169, 79, 0.28), rgba(212, 169, 79, 0.12));
			border-color: rgba(212, 169, 79, 0.38);
			color: #fff4d6;
			box-shadow: 0 14px 24px rgba(0, 0, 0, 0.2);
		}

		.room-card {
			background: rgba(255, 255, 255, 0.03);
			border: 1px solid rgba(255, 255, 255, 0.08);
			border-radius: 22px;
			overflow: hidden;
		}

		.room-card-header {
			padding: 1rem 1.25rem;
			border-bottom: 1px solid rgba(255, 255, 255, 0.06);
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 1rem;
			flex-wrap: wrap;
		}

		.room-card-body {
			padding: 1rem 1.25rem 1.25rem;
		}

		.room-subtitle {
			margin: 0;
			color: var(--ez-muted);
			font-size: 0.95rem;
		}

		.room-table-wrap {
			overflow-x: auto;
		}

		.room-table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
			min-width: 720px;
		}

		.room-table th,
		.room-table td {
			padding: 1rem 0.9rem;
			border-bottom: 1px solid rgba(255, 255, 255, 0.06);
			vertical-align: middle;
		}

		.room-table th {
			font-size: 0.78rem;
			text-transform: uppercase;
			letter-spacing: 0.08em;
			color: var(--ez-muted);
			font-weight: 700;
		}

		.room-table tbody tr:last-child td {
			border-bottom: 0;
		}

		.room-name {
			font-weight: 700;
			color: var(--ez-text);
		}

		.room-versus {
			color: var(--ez-muted);
		}

		.room-status-badge {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 0.3rem 0.7rem;
			border-radius: 999px;
			background: rgba(109, 211, 206, 0.14);
			border: 1px solid rgba(109, 211, 206, 0.28);
			color: #bdf4f0;
			font-size: 0.78rem;
			font-weight: 700;
		}

		.room-actions {
			display: inline-flex;
			justify-content: flex-end;
			gap: 0.5rem;
			flex-wrap: wrap;
		}

		.room-status-badge.is-waiting {
			background: rgba(212, 169, 79, 0.14);
			border-color: rgba(212, 169, 79, 0.28);
			color: #ffe6b2;
		}

		.room-status-badge.is-playing {
			background: rgba(122, 72, 181, 0.18);
			border-color: rgba(167, 123, 234, 0.28);
			color: #dfc5ff;
		}

		.room-modal-label {
			font-size: 0.78rem;
			text-transform: uppercase;
			letter-spacing: 0.08em;
			color: var(--ez-muted);
			margin-bottom: 0.4rem;
			font-weight: 700;
		}

		.room-modal-input {
			width: 100%;
			padding: 0.85rem 1rem;
			border-radius: 14px;
			border: 1px solid rgba(255, 255, 255, 0.1);
			background: rgba(255, 255, 255, 0.04);
			color: var(--ez-text);
		}

		.room-modal-input:focus {
			outline: none;
			border-color: rgba(212, 169, 79, 0.4);
			box-shadow: 0 0 0 0.2rem rgba(212, 169, 79, 0.12);
		}

		.room-deck-status {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 0.45rem 0.8rem;
			border-radius: 999px;
			font-size: 0.85rem;
			font-weight: 700;
			text-transform: uppercase;
		}

		.room-deck-status.is-ok {
			background: rgba(58, 160, 92, 0.16);
			border: 1px solid rgba(86, 210, 127, 0.32);
			color: #bbf7cd;
		}

		.room-deck-status.is-invalid {
			background: rgba(169, 108, 31, 0.16);
			border: 1px solid rgba(233, 185, 77, 0.32);
			color: #ffe5aa;
		}

		.room-modal-help {
			color: var(--ez-muted);
			font-size: 0.92rem;
			margin-top: 0.65rem;
		}

		.modal-content.room-modal {
			background: #171b2a;
			border: 1px solid rgba(255, 255, 255, 0.08);
			border-radius: 22px;
			color: var(--ez-text);
		}

		.modal-content.room-modal .modal-header,
		.modal-content.room-modal .modal-footer {
			border-color: rgba(255, 255, 255, 0.06);
		}

		@media (max-width: 991.98px) {
			.room-page {
				height: auto;
			}
		}
	</style>
@endsection

@section('content')
	<div class="room-page">
		<div class="room-header">
			<div class="room-switcher">
				<button type="button" class="room-switcher-btn is-active" data-bs-toggle="modal" data-bs-target="#createRoomModal">CRIAR PARTIDA</button>
				<a href="#" class="room-switcher-btn">ENCONTRAR PARTIDA</a>
			</div>

			<a href="{{ route('dashboard') }}" class="btn btn-outline-light">Voltar ao dashboard</a>
		</div>

		<section class="room-card">
			<div class="room-card-header">
				<div>
					<h2 class="h5 mb-1">Partidas criadas</h2>
					<p class="room-subtitle">Base inicial da listagem de salas para futuras melhorias.</p>
				</div>
			</div>

			<div class="room-card-body">
				<div class="room-table-wrap">
					<table class="room-table">
						<thead>
							<tr>
								<th>Nome da sala</th>
								<th>Jogador x Jogador</th>
								<th>Status</th>
								<th class="text-end">Ação</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($rooms as $room)
								<tr>
									<td>
										<div class="room-name">{{ $room['name'] }}</div>
									</td>
									<td>
										<div class="room-versus">{{ $room['player_one'] }} x {{ $room['player_two'] }}</div>
									</td>
									<td>
										<span class="room-status-badge {{ str_contains(strtolower($room['status']), 'aguardando') ? 'is-waiting' : '' }} {{ str_contains(strtolower($room['status']), 'jogando') ? 'is-playing' : '' }}">{{ $room['status'] }}</span>
									</td>
									<td class="text-end">
										<div class="room-actions">
											@if (strtolower($room['status']) === 'jogando')
												<button type="button" class="btn btn-secondary btn-sm" disabled>Jogando</button>
											@else
												<button type="button" class="btn btn-outline-light btn-sm">Entrar</button>
											@endif
										</div>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="4" class="text-center text-secondary">Nenhuma sala criada ainda.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>

	<div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content room-modal">
				<form action="{{ route('battle.room.create') }}" method="POST">
					@csrf
					<div class="modal-header">
						<h2 class="modal-title fs-5" id="createRoomModalLabel">Criar Partida</h2>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
					</div>

					<div class="modal-body d-flex flex-column gap-3">
						<div>
							<label for="roomName" class="room-modal-label">Nome da sala</label>
							<input
								type="text"
								id="roomName"
								name="room_name"
								class="room-modal-input"
								placeholder="Digite o nome da sala"
								required
							>
						</div>

						<div>
							<div class="room-modal-label">Baralho status</div>
							<span class="room-deck-status {{ ($deckStatus['is_ok'] ?? false) ? 'is-ok' : 'is-invalid' }}">
								{{ $deckStatus['label'] ?? 'validar baralho' }}
							</span>
							
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-warning" {{ ($deckStatus['is_ok'] ?? false) ? '' : 'disabled' }}>Criar Sala</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
