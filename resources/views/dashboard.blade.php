@extends('layout.dash')

@section('title', 'Elemental Zone TCG')

@section('content')
	<div class="row g-3 justify-content-center align-items-start mx-auto" style="max-width: 980px;">

        <!-- MENU ->BATALHA -->
        <div class="col-12 col-md-6 col-xl-4">
			<a href="{{ route('sala-batalha') }}" class="ez-card-link text-decoration-none d-block">
				<div class="card ez-menu-card border-0 shadow-lg text-bg-dark">
					<div class="card-body p-4 text-center">
						<h2 class="ez-menu-title">Batalha</h2>
					</div>
				</div>
			</a>
		</div>

        <!-- MENU -> BARALHO -->
        <div class="col-12 col-md-6 col-xl-4">
			<a href="{{ route('deck') }}" class="ez-card-link text-decoration-none d-block">
				<div class="card ez-menu-card border-0 shadow-lg text-bg-dark">
					<div class="card-body p-4 text-center">
						<h2 class="ez-menu-title">Baralho</h2>
					</div>
				</div>
			</a>
		</div>

                <!-- MENU -> Loja -->
        <div class="col-12 col-md-6 col-xl-4">
			<a href="{{ route('shop') }}" class="ez-card-link text-decoration-none d-block">
				<div class="card ez-menu-card border-0 shadow-lg text-bg-dark">
					<div class="card-body p-4 text-center">
						<h2 class="ez-menu-title">Loja</h2>
					</div>
				</div>
			</a>
		</div>

                <!-- MENU -> Ranking -->
        <div class="col-12 col-md-6 col-xl-4">
			<a href="{{ route('ranking') }}" class="ez-card-link text-decoration-none d-block">
				<div class="card ez-menu-card border-0 shadow-lg text-bg-dark">
					<div class="card-body p-4 text-center">
						<h2 class="ez-menu-title">Ranking</h2>
					</div>
				</div>
			</a>
		</div>

                <!-- MENU -> Perfil -->
        <div class="col-12 col-md-6 col-xl-4">
			<a href="{{ route('profile') }}" class="ez-card-link text-decoration-none d-block">
				<div class="card ez-menu-card border-0 shadow-lg text-bg-dark">
					<div class="card-body p-4 text-center">
						<h2 class="ez-menu-title">Perfil</h2>
					</div>
				</div>
			</a>
		</div>

	</div>
@endsection
