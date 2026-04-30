@extends('layout.batalha')

@section('title', 'Partida')

@section('page_styles')
	<style>
		.play-page {
			height: 100%;
			display: flex;
			flex-direction: column;
			gap: 0.85rem;
			padding: 7rem 0.5rem 0.5rem;
			overflow: hidden;
		}

		.play-score-header {
			position: fixed;
			top: 16px;
			left: 32px;
			right: 32px;
			z-index: 1200;
			background: #020202;
			border-radius: 0 0 22px 22px;
			padding: 1rem 1.5rem;
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 1rem;
			min-height: 72px;
			box-shadow: 0 16px 30px rgba(0, 0, 0, 0.32);
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
			width: min(780px, calc(100% - 1rem));
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
			grid-template-columns: minmax(0, 1fr) 138px;
			grid-template-rows: minmax(0, 1fr) 150px;
			gap: 0.9rem;
		}

		.play-battlefield {
			grid-column: 1 / 2;
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
			display: flex;
			flex-direction: column;
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

		.play-free-zone,
		.play-middle-zone {
			position: relative;
			border: 1px dashed rgba(255, 255, 255, 0.22);
			background: rgba(255, 255, 255, 0.03);
			min-height: 96px;
			flex: 1;
			padding: 0.55rem;
			overflow: hidden;
		}

		.play-middle-zone {
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.play-free-zone.can-drop,
		.play-middle-zone.can-drop,
		.play-pile-box.can-drop {
			border-color: rgba(212, 169, 79, 0.72);
			background: rgba(212, 169, 79, 0.12);
		}

		.play-middle-row {
			display: grid;
			grid-template-columns: minmax(0, 1fr) 180px;
			gap: 0.75rem;
		}

		.play-right-rail {
			grid-column: 2 / 3;
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
		.play-zone-card img,
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
			grid-column: 1 / 2;
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

		.play-card,
		.play-zone-card {
			position: relative;
			width: 92px;
			height: 126px;
			border: 1px solid rgba(255, 255, 255, 0.1);
			background: rgba(255, 255, 255, 0.04);
			overflow: hidden;
		}

		.play-card {
			flex: 0 0 92px;
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

		.play-zone-card {
			position: absolute;
			flex: 0 0 auto;
		}

		.play-zone-stack {
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
			position: absolute;
			inset: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 0.4rem;
			text-align: center;
			color: rgba(255, 255, 255, 0.72);
		}

		@media (max-width: 1199.98px) {
			.play-board-layout {
				grid-template-columns: minmax(0, 1fr) 112px;
			}
		}

		@media (max-width: 991.98px) {
			.play-page {
				padding-top: 1rem;
				overflow: auto;
			}

			.play-score-header {
				position: static;
				left: auto;
				right: auto;
			}

			.play-score-header,
			.play-phase-dock {
				border-radius: 18px;
			}

			.play-board-layout {
				grid-template-columns: 1fr;
				grid-template-rows: auto auto auto;
			}

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
			<section class="play-battlefield">
				<div class="play-field-row">
					<div class="play-field-row-header">
						<div class="play-field-title">Campo do oponente</div>
					</div>
					<div class="play-free-zone" id="opponentFieldZone">
						<div class="play-empty-text">Área unificada do campo do oponente.</div>
					</div>
				</div>

				<div class="play-middle-row">
					<div class="play-middle-zone drop-zone" data-zone="scenario" id="scenarioZone">
						<div class="play-empty-text">Cenário em campo</div>
					</div>
					<div class="play-middle-zone drop-zone" data-zone="command" id="commandZone">
						<div class="play-empty-text">Comando ativo → descarte</div>
					</div>
				</div>

				<div class="play-field-row">
					<div class="play-field-row-header">
						<div class="play-field-title">Seu campo</div>
						<div class="small text-light-emphasis">Solte a carta em qualquer posição da área.</div>
					</div>
					<div class="play-free-zone drop-zone" data-zone="creature" id="playerFieldZone">
						<div class="play-empty-text">Área unificada para criaturas, itens e habilidades.</div>
					</div>
				</div>
			</section>

			<aside class="play-right-rail">
				<div class="play-pile-box drop-zone" data-zone="discard" id="discardZone">
					<div class="play-pile-title">Descarte</div>
					<div class="play-pile-preview" id="discardPreview"></div>
					<div class="play-pile-count" id="discardCount">0</div>
				</div>

				<div class="play-pile-box drop-zone" data-zone="exile" id="exileZone">
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
			const turnFlag = document.getElementById('turnFlag');
			const playerFieldZone = document.getElementById('playerFieldZone');
			const scenarioZone = document.getElementById('scenarioZone');
			const commandZone = document.getElementById('commandZone');

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

			function clamp(value, min, max) {
				return Math.max(min, Math.min(max, value));
			}

			function getDropPosition(zone, event) {
				const rect = zone.getBoundingClientRect();
				const cardWidth = 92;
				const cardHeight = 126;
				const left = clamp(event.clientX - rect.left - (cardWidth / 2), 4, Math.max(4, rect.width - cardWidth - 4));
				const top = clamp(event.clientY - rect.top - (cardHeight / 2), 4, Math.max(4, rect.height - cardHeight - 4));

				return { left, top };
			}

			function getClosestCreatureCard(zone, event) {
				const cards = [...zone.querySelectorAll('.play-zone-card')];
				if (!cards.length) return null;

				let closestCard = null;
				let closestDistance = Number.POSITIVE_INFINITY;

				cards.forEach((cardEl) => {
					const rect = cardEl.getBoundingClientRect();
					const centerX = rect.left + (rect.width / 2);
					const centerY = rect.top + (rect.height / 2);
					const distance = Math.hypot(event.clientX - centerX, event.clientY - centerY);

					if (distance < closestDistance) {
						closestDistance = distance;
						closestCard = cardEl;
					}
				});

				return closestCard;
			}

			function drawCard() {
				if (!state.drawPile.length) return;
				const card = state.drawPile.shift();
				state.hand.push(card);
				renderHand();
				renderDeckPreview();
			}

			function createZoneCard(card, position = null) {
				const cardEl = document.createElement('div');
				cardEl.className = 'play-zone-card';
				cardEl.dataset.uid = card.uid;
				if (position) {
					cardEl.style.left = `${position.left}px`;
					cardEl.style.top = `${position.top}px`;
				}
				cardEl.innerHTML = `
					<img src="${card.imagem}" alt="${card.nome}">
					<div class="play-card-meta">${card.tipo}</div>
				`;
				return cardEl;
			}

			function clearEmptyText(zone) {
				const emptyText = zone.querySelector('.play-empty-text');
				if (emptyText) emptyText.remove();
			}

			function placeInFreeZone(zone, card, event) {
				clearEmptyText(zone);

				if (['habilidade', 'item'].includes(card.tipo)) {
					const creatureCard = getClosestCreatureCard(zone, event);
					if (creatureCard) {
						const stack = creatureCard.querySelector('.play-zone-stack') || (() => {
							const el = document.createElement('div');
							el.className = 'play-zone-stack';
							creatureCard.appendChild(el);
							return el;
						})();

						const attachment = document.createElement('div');
						attachment.className = 'play-attachment';
						attachment.innerHTML = `<img src="${card.imagem}" alt="${card.nome}">`;
						stack.appendChild(attachment);
						return;
					}
				}

				zone.appendChild(createZoneCard(card, getDropPosition(zone, event)));
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

						switch (zone.dataset.zone) {
							case 'discard':
							case 'command':
								state.discard.push(card);
								renderHand();
								renderPiles();
								return;
							case 'exile':
								state.exile.push(card);
								renderHand();
								renderPiles();
								return;
							case 'scenario':
								if (card.tipo !== 'cenario') {
									state.hand.push(card);
									renderHand();
									return;
								}
								zone.innerHTML = '';
								placeInFreeZone(zone, card, event);
								renderHand();
								return;
							case 'creature':
								if (!['criatura', 'habilidade', 'item'].includes(card.tipo)) {
									state.hand.push(card);
									renderHand();
									return;
								}
								placeInFreeZone(zone, card, event);
								renderHand();
								return;
						}
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
