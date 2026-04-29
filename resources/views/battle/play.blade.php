@extends('layout.batalha')

@section('title', 'Partida')

@section('page_styles')
	<style>
		.play-page {
			height: 100%;
			display: flex;
			flex-direction: column;
			gap: 0.85rem;
			padding: 0.4rem;
			overflow: hidden;
		}

		.play-score-header {
			background: #020202;
			border-radius: 0 0 22px 22px;
			padding: 1rem 1.5rem;
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 1rem;
			min-height: 72px;
		}

		.play-score-side {
			display: flex;
			align-items: center;
			gap: 0.8rem;
		}

		.play-score-name {
			font-size: 0.9rem;
			font-weight: 700;
			color: #e8ebf7;
		}

		.play-score-value {
			font-size: 1.8rem;
			font-weight: 800;
			letter-spacing: 0.08em;
		}

		.play-score-separator {
			font-size: 1.35rem;
			font-weight: 800;
			color: var(--battle-accent);
		}

		.play-points {
			display: inline-flex;
			gap: 0.45rem;
			margin-top: 0.2rem;
		}

		.play-point {
			width: 12px;
			height: 12px;
			border-radius: 50%;
			border: 2px solid rgba(255, 255, 255, 0.45);
			background: transparent;
		}

		.play-phase-dock-wrap {
			display: flex;
			justify-content: center;
		}

		.play-phase-dock {
			width: min(780px, calc(100% - 2rem));
			background: #353535;
			border-radius: 999px;
			padding: 0.8rem 1rem;
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 1rem;
			box-shadow: 0 14px 30px rgba(0, 0, 0, 0.28);
		}

		.play-phases {
			display: flex;
			gap: 0.5rem;
			flex-wrap: wrap;
		}

		.play-phase-btn {
			border: 0;
			border-radius: 999px;
			padding: 0.55rem 0.9rem;
			background: rgba(255, 255, 255, 0.08);
			color: #fff;
			font-size: 0.78rem;
			font-weight: 700;
			text-transform: uppercase;
		}

		.play-phase-btn.is-active {
			background: rgba(212, 169, 79, 0.22);
			color: #ffe6b2;
		}

		.play-turn-flag {
			border-radius: 999px;
			padding: 0.5rem 0.9rem;
			background: rgba(95, 140, 255, 0.2);
			color: #dbe7ff;
			font-weight: 700;
			white-space: nowrap;
		}

		.play-board-layout {
			flex: 1;
			min-height: 0;
			display: grid;
			grid-template-columns: 118px minmax(0, 1fr) 126px;
			grid-template-rows: minmax(0, 1fr) 150px;
			gap: 0.9rem;
		}

		.play-left-rail {
			grid-row: 1 / 2;
			background: #8c8b00;
			border: 1px solid rgba(0, 0, 0, 0.22);
			padding: 0.75rem;
			display: grid;
			grid-template-rows: 1fr 1fr;
			gap: 0.75rem;
		}

		.play-rail-box {
			border: 1px dashed rgba(255, 255, 255, 0.28);
			background: rgba(0, 0, 0, 0.12);
			color: #f5f4c2;
			font-size: 0.76rem;
			font-weight: 700;
			text-transform: uppercase;
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			padding: 0.5rem;
		}

		.play-battlefield {
			grid-column: 2 / 3;
			grid-row: 1 / 2;
			background: #575757;
			padding: 1rem;
			display: grid;
			grid-template-rows: 1fr auto 1fr;
			gap: 0.9rem;
			min-height: 0;
		}

		.play-field-row {
			border: 1px solid rgba(0, 0, 0, 0.2);
			background: rgba(0, 0, 0, 0.14);
			padding: 0.75rem;
			min-height: 0;
		}

		.play-field-row-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 0.6rem;
			gap: 0.75rem;
		}

		.play-field-title {
			font-size: 0.74rem;
			text-transform: uppercase;
			letter-spacing: 0.08em;
			font-weight: 700;
			color: #ececec;
		}

		.play-field-slots {
			display: grid;
			grid-template-columns: repeat(5, minmax(0, 1fr));
			gap: 0.7rem;
			height: calc(100% - 1.6rem);
		}

		.play-slot,
		.play-middle-zone {
			position: relative;
			border: 1px dashed rgba(255, 255, 255, 0.22);
			background: rgba(255, 255, 255, 0.03);
			min-height: 96px;
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: rgba(255, 255, 255, 0.7);
			font-size: 0.8rem;
			padding: 0.35rem;
		}

		.play-slot.can-drop,
		.play-middle-zone.can-drop {
			border-color: rgba(212, 169, 79, 0.72);
			background: rgba(212, 169, 79, 0.12);
		}

		.play-middle-row {
			display: grid;
			grid-template-columns: minmax(0, 1fr) 180px;
			gap: 0.75rem;
		}

		.play-right-rail {
			grid-column: 3 / 4;
			grid-row: 1 / 2;
			display: grid;
			grid-template-rows: 108px 108px minmax(0, 1fr);
			gap: 0.75rem;
		}

		.play-pile-box {
			background: #050505;
			border: 1px solid rgba(255, 255, 255, 0.08);
			padding: 0.65rem;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			gap: 0.45rem;
			text-align: center;
		}

		.play-pile-title {
			font-size: 0.7rem;
			text-transform: uppercase;
			letter-spacing: 0.08em;
			color: #d9d9d9;
			font-weight: 700;
		}

		.play-pile-count {
			font-size: 1.15rem;
			font-weight: 800;
			color: var(--battle-accent);
		}

		.play-pile-preview {
			width: 70px;
			height: 84px;
			background: rgba(255, 255, 255, 0.05);
			border: 1px solid rgba(255, 255, 255, 0.08);
			overflow: hidden;
		}

		.play-pile-preview img,
		.play-card img,
		.play-slot-card img,
		.play-attachment img,
		.play-deck-thumb img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}

		.play-deck-box {
			background: #050505;
			border: 1px solid rgba(255, 255, 255, 0.08);
			padding: 0.8rem;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			gap: 0.6rem;
		}

		.play-deck-button {
			width: 100%;
			flex: 1;
			min-height: 176px;
			border: 1px solid rgba(212, 169, 79, 0.28);
			background: linear-gradient(160deg, rgba(34, 42, 71, 0.98), rgba(66, 49, 24, 0.98));
			color: #fff;
			font-size: 0.82rem;
			font-weight: 800;
			text-transform: uppercase;
			letter-spacing: 0.08em;
		}

		.play-deck-thumb {
			width: 76px;
			height: 108px;
			border: 1px solid rgba(255, 255, 255, 0.08);
			overflow: hidden;
		}

		.play-hand-panel {
			grid-column: 2 / 3;
			grid-row: 2 / 3;
			background: #393939;
			padding: 0.85rem 1rem;
			overflow-x: auto;
			overflow-y: hidden;
		}

		.play-hand-track {
			display: flex;
			gap: 0.75rem;
			min-height: 118px;
		}

		.play-card {
			position: relative;
			flex: 0 0 92px;
			height: 126px;
			border: 1px solid rgba(255, 255, 255, 0.1);
			background: rgba(255, 255, 255, 0.04);
			overflow: hidden;
			cursor: grab;
		}

		.play-card-meta {
			position: absolute;
			left: 0;
			right: 0;
			bottom: 0;
			padding: 0.25rem 0.35rem;
			background: linear-gradient(180deg, transparent, rgba(0, 0, 0, 0.82));
			font-size: 0.64rem;
			font-weight: 700;
			text-transform: uppercase;
		}

		.play-slot-card {
			width: 100%;
			height: 100%;
			position: relative;
			overflow: hidden;
		}

		.play-slot-stack {
			position: absolute;
			left: 0.2rem;
			right: 0.2rem;
			bottom: 0.2rem;
			display: flex;
			gap: 0.25rem;
			justify-content: center;
		}

		.play-attachment {
			width: 28px;
			height: 40px;
			border: 1px solid rgba(255, 255, 255, 0.1);
			overflow: hidden;
		}

		.play-empty-text {
			padding: 0.4rem;
		}

		@media (max-width: 1199.98px) {
			.play-board-layout {
				grid-template-columns: 92px 1fr 112px;
			}
		}

		@media (max-width: 991.98px) {
			.play-page {
				overflow: auto;
			}

			.play-score-header,
			.play-phase-dock {
				border-radius: 18px;
			}

			.play-board-layout {
				grid-template-columns: 1fr;
				grid-template-rows: auto auto auto auto;
			}

			.play-left-rail,
			.play-battlefield,
			.play-right-rail,
			.play-hand-panel {
				grid-column: auto;
				grid-row: auto;
			}

			.play-right-rail {
				grid-template-columns: repeat(3, minmax(0, 1fr));
				grid-template-rows: none;
			}

			.play-field-slots,
			.play-middle-row {
				grid-template-columns: 1fr;
			}
		}
	</style>
@endsection

@section('content')
	<div class="play-page">
		<section class="play-score-header">
			<div class="play-score-side">
				<div>
					<div class="play-score-name">Eu ({{ $playerName }})</div>
					<div class="play-points">
						<span class="play-point"></span>
						<span class="play-point"></span>
						<span class="play-point"></span>
					</div>
				</div>
				<div class="play-score-value">000</div>
			</div>

			<div class="play-score-separator">x</div>

			<div class="play-score-side text-end">
				<div class="play-score-value">000</div>
				<div>
					<div class="play-score-name">{{ $opponentName }} (Oponente)</div>
					<div class="play-points justify-content-end d-inline-flex">
						<span class="play-point"></span>
						<span class="play-point"></span>
						<span class="play-point"></span>
					</div>
				</div>
			</div>
		</section>

		<div class="play-phase-dock-wrap">
			<section class="play-phase-dock">
				<div class="play-phases">
					<button type="button" class="play-phase-btn is-active" data-phase="Comprar">Comprar</button>
					<button type="button" class="play-phase-btn" data-phase="Fase principal">Fase principal</button>
					<button type="button" class="play-phase-btn" data-phase="Fase de batalha">Fase de batalha</button>
					<button type="button" class="play-phase-btn" data-phase="Fim do seu turno">Fim do seu turno</button>
				</div>
				<div class="play-turn-flag" id="turnFlag">⚑ Seu Turno</div>
			</section>
		</div>

		<div class="play-board-layout">
			<aside class="play-left-rail">
				<div class="play-rail-box" id="leftDiscardBox">Descarte</div>
				<div class="play-rail-box" id="leftExileBox">Exílio</div>
			</aside>

			<section class="play-battlefield">
				<div class="play-field-row">
					<div class="play-field-row-header">
						<div class="play-field-title">Campo do oponente</div>
					</div>
					<div class="play-field-slots">
						<div class="play-slot"><div class="play-empty-text">Criatura</div></div>
						<div class="play-slot"><div class="play-empty-text">Criatura</div></div>
						<div class="play-slot"><div class="play-empty-text">Criatura</div></div>
						<div class="play-slot"><div class="play-empty-text">Criatura</div></div>
						<div class="play-slot"><div class="play-empty-text">Criatura</div></div>
					</div>
				</div>

				<div class="play-middle-row">
					<div class="play-middle-zone drop-zone" data-zone="scenario">
						<div class="play-empty-text">Cenário em campo</div>
					</div>
					<div class="play-middle-zone drop-zone" data-zone="command">
						<div class="play-empty-text">Comando ativo → descarte</div>
					</div>
				</div>

				<div class="play-field-row">
					<div class="play-field-row-header">
						<div class="play-field-title">Seu campo</div>
						<div class="small text-light-emphasis">Arraste criaturas para o campo e anexe itens/habilidades.</div>
					</div>
					<div class="play-field-slots">
						<div class="play-slot drop-zone" data-zone="creature"><div class="play-empty-text">Criatura 1</div></div>
						<div class="play-slot drop-zone" data-zone="creature"><div class="play-empty-text">Criatura 2</div></div>
						<div class="play-slot drop-zone" data-zone="creature"><div class="play-empty-text">Criatura 3</div></div>
						<div class="play-slot drop-zone" data-zone="creature"><div class="play-empty-text">Criatura 4</div></div>
						<div class="play-slot drop-zone" data-zone="creature"><div class="play-empty-text">Criatura 5</div></div>
					</div>
				</div>
			</section>

			<aside class="play-right-rail">
				<div class="play-pile-box">
					<div class="play-pile-title">Descarte</div>
					<div class="play-pile-preview" id="discardPreview"></div>
					<div class="play-pile-count" id="discardCount">0</div>
				</div>

				<div class="play-pile-box">
					<div class="play-pile-title">Exílio</div>
					<div class="play-pile-preview" id="exilePreview"></div>
					<div class="play-pile-count" id="exileCount">0</div>
				</div>

				<div class="play-deck-box">
					<div class="play-pile-title">Deck salvo · {{ $roomName }}</div>
					<div class="play-deck-thumb" id="deckPreview">
						@if (!empty($drawPile[0]['imagem'] ?? null))
							<img src="{{ $drawPile[0]['imagem'] }}" alt="Topo do deck">
						@endif
					</div>
					<button type="button" class="play-deck-button" id="drawDeckBtn">Comprar do deck</button>
					<div class="play-pile-count" id="deckCount">{{ $remainingDeckCount }}</div>
				</div>
			</aside>

			<section class="play-hand-panel">
				<div class="play-hand-track" id="handTrack"></div>
			</section>
		</div>
	</div>
@endsection

@section('page_scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const state = {
				hand: @json($openingHand ?? []),
				drawPile: @json($drawPile ?? []),
				discard: [],
				exile: [],
				draggingId: null,
			};

			const handTrack = document.getElementById('handTrack');
			const drawDeckBtn = document.getElementById('drawDeckBtn');
			const deckCount = document.getElementById('deckCount');
			const deckPreview = document.getElementById('deckPreview');
			const discardPreview = document.getElementById('discardPreview');
			const exilePreview = document.getElementById('exilePreview');
			const discardCount = document.getElementById('discardCount');
			const exileCount = document.getElementById('exileCount');
			const leftDiscardBox = document.getElementById('leftDiscardBox');
			const leftExileBox = document.getElementById('leftExileBox');
			const turnFlag = document.getElementById('turnFlag');

			function topCardMarkup(card) {
				return card ? `<img src="${card.imagem}" alt="${card.nome}">` : '';
			}

			function renderDeckPreview() {
				deckCount.textContent = state.drawPile.length;
				drawDeckBtn.disabled = state.drawPile.length === 0;
				deckPreview.innerHTML = topCardMarkup(state.drawPile[0]);
			}

			function renderPiles() {
				discardPreview.innerHTML = topCardMarkup(state.discard[state.discard.length - 1]);
				exilePreview.innerHTML = topCardMarkup(state.exile[state.exile.length - 1]);
				discardCount.textContent = state.discard.length;
				exileCount.textContent = state.exile.length;
				leftDiscardBox.textContent = `Descarte ${state.discard.length}`;
				leftExileBox.textContent = `Exílio ${state.exile.length}`;
			}

			function renderHand() {
				if (!state.hand.length) {
					handTrack.innerHTML = '<div class="small text-light-emphasis align-self-center">Sua mão está vazia.</div>';
					return;
				}

				handTrack.innerHTML = state.hand.map((card) => `
					<div class="play-card" draggable="true" data-id="${card.uid}">
						<img src="${card.imagem}" alt="${card.nome}">
						<div class="play-card-meta">${card.tipo}</div>
					</div>
				`).join('');

				handTrack.querySelectorAll('.play-card').forEach((cardEl) => {
					cardEl.addEventListener('dragstart', () => {
						state.draggingId = cardEl.dataset.id;
					});

					cardEl.addEventListener('dragend', () => {
						state.draggingId = null;
					});
				});
			}

			function removeCardFromHand(cardId) {
				const index = state.hand.findIndex((card) => card.uid === cardId);
				if (index === -1) return null;
				return state.hand.splice(index, 1)[0];
			}

			function drawCard() {
				if (!state.drawPile.length) return;
				const card = state.drawPile.shift();
				state.hand.push(card);
				renderHand();
				renderDeckPreview();
			}

			function placeCardInZone(zone, card) {
				zone.innerHTML = `
					<div class="play-slot-card">
						<img src="${card.imagem}" alt="${card.nome}">
						<div class="play-card-meta">${card.tipo}</div>
					</div>
				`;
			}

			function attachDropHandlers() {
				document.querySelectorAll('.drop-zone').forEach((zone) => {
					zone.addEventListener('dragover', (event) => {
						event.preventDefault();
						zone.classList.add('can-drop');
					});

					zone.addEventListener('dragleave', () => zone.classList.remove('can-drop'));

					zone.addEventListener('drop', (event) => {
						event.preventDefault();
						zone.classList.remove('can-drop');
						if (!state.draggingId) return;

						const card = removeCardFromHand(state.draggingId);
						if (!card) return;

						const zoneType = zone.dataset.zone;
						if (zoneType === 'command') {
							state.discard.push(card);
							renderHand();
							renderPiles();
							return;
						}

						if (zoneType === 'scenario' && card.tipo !== 'cenario') {
							state.hand.push(card);
							renderHand();
							return;
						}

						if (zoneType === 'creature' && !['criatura', 'habilidade', 'item'].includes(card.tipo)) {
							state.hand.push(card);
							renderHand();
							return;
						}

						if (zoneType === 'creature' && ['habilidade', 'item'].includes(card.tipo) && zone.querySelector('.play-slot-card')) {
							const stack = zone.querySelector('.play-slot-stack') || (() => {
								const el = document.createElement('div');
								el.className = 'play-slot-stack';
								zone.appendChild(el);
								return el;
							})();

							const attachment = document.createElement('div');
							attachment.className = 'play-attachment';
							attachment.innerHTML = `<img src="${card.imagem}" alt="${card.nome}">`;
							stack.appendChild(attachment);
							renderHand();
							return;
						}

						placeCardInZone(zone, card);
						renderHand();
					});
				});
			}

			drawDeckBtn.addEventListener('click', drawCard);

			document.querySelectorAll('.play-phase-btn').forEach((button) => {
				button.addEventListener('click', () => {
					document.querySelectorAll('.play-phase-btn').forEach((btn) => btn.classList.remove('is-active'));
					button.classList.add('is-active');
					turnFlag.textContent = `⚑ Seu Turno · ${button.dataset.phase}`;
				});
			});

			renderHand();
			renderDeckPreview();
			renderPiles();
			attachDropHandlers();
		});
	</script>
@endsection
