@extends('layout.dash')

@section('title', 'Aguardando Oponente')

@section('page_styles')
	<style>
		.waiting-page {
			height: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.waiting-card {
			width: 100%;
			max-width: 720px;
			padding: 2rem;
			border-radius: 24px;
			background: rgba(255, 255, 255, 0.03);
			border: 1px solid rgba(255, 255, 255, 0.08);
			text-align: center;
		}

		.waiting-room-name {
			font-size: clamp(1.6rem, 3vw, 2.3rem);
			font-weight: 700;
			margin-bottom: 0.75rem;
		}

		.waiting-subtitle {
			color: var(--ez-muted);
			font-size: 1rem;
			margin-bottom: 1.5rem;
		}

		.waiting-pulse {
			width: 96px;
			height: 96px;
			margin: 0 auto 1.5rem;
			border-radius: 50%;
			background: radial-gradient(circle, rgba(212, 169, 79, 0.55) 0%, rgba(212, 169, 79, 0.08) 55%, transparent 72%);
			animation: waitingPulse 1.8s infinite ease-in-out;
		}

		@keyframes waitingPulse {
			0% { transform: scale(0.9); opacity: 0.7; }
			50% { transform: scale(1.05); opacity: 1; }
			100% { transform: scale(0.9); opacity: 0.7; }
		}
	</style>
@endsection

@section('content')
	<div class="waiting-page">
		<section class="waiting-card">
			<div class="waiting-pulse"></div>
			<div class="waiting-room-name">{{ $roomName }}</div>
			<p class="waiting-subtitle">Sala criada com sucesso. Agora é só aguardar a entrada do oponente.</p>

			<div class="d-flex justify-content-center gap-2 flex-wrap">
				@if ($canStartSolo ?? true)
					<a href="{{ route('battle.play', ['mode' => 'solo', 'room_name' => $roomName, 'room_id' => $roomId]) }}" class="btn btn-warning">Jogar Solo</a>
				@else
					<button type="button" class="btn btn-secondary" disabled>Jogando</button>
				@endif
				<a href="{{ route('sala-batalha') }}" class="btn btn-outline-light">Voltar para salas</a>
				<a href="{{ route('dashboard') }}" class="btn btn-outline-light">Ir ao dashboard</a>
			</div>
		</section>
	</div>
@endsection