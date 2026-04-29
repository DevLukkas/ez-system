<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Elemental Zone TCG')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @yield('page_styles')
    <style>
        :root {
            --ez-bg: #0f1020;
            --ez-surface: rgba(20, 24, 45, 0.88);
            --ez-surface-soft: rgba(34, 40, 72, 0.78);
            --ez-primary: #d4a94f;
            --ez-secondary: #6dd3ce;
            --ez-text: #f5f1e8;
            --ez-muted: #b8b2a5;
            --ez-border: rgba(212, 169, 79, 0.28);
            --ez-header-height: 92px;
        }

        html,
        body {
            height: 100vh;
            overflow: hidden;
            background:
                radial-gradient(circle at top, rgba(109, 211, 206, 0.12), transparent 30%),
                radial-gradient(circle at bottom, rgba(212, 169, 79, 0.12), transparent 25%),
                linear-gradient(180deg, #0b0d1a 0%, var(--ez-bg) 100%);
            color: var(--ez-text);
        }

        .ez-shell {
            height: 100vh;
            overflow: hidden;
        }

        .ez-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: var(--ez-header-height);
            background: rgba(10, 12, 25, 0.78);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--ez-border);
        }

        .ez-header-inner {
            height: 100%;
        }

        .ez-avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(212, 169, 79, 0.32), rgba(109, 211, 206, 0.22));
            border: 1px solid var(--ez-border);
            color: var(--ez-primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .ez-main {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: calc(var(--ez-header-height) + 24px) 16px 16px;
            overflow: hidden;
        }

        .ez-panel {
            width: 100%;
            max-width: 1100px;
            height: 100%;
            background: var(--ez-surface);
            border: 1px solid var(--ez-border);
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ez-hero {
            padding: 2rem 2rem 1.25rem;
            background: linear-gradient(135deg, rgba(212, 169, 79, 0.14), rgba(109, 211, 206, 0.08));
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .ez-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .ez-title-accent {
            color: var(--ez-primary);
        }

        .ez-description {
            color: var(--ez-muted);
            max-width: 700px;
            margin-bottom: 0;
        }

        .ez-content {
            padding: 1.5rem 2rem 2rem;
            flex: 1;
            overflow: hidden;
        }

        .ez-card-link {
            color: inherit;
            position: relative;
        }

        .ez-card-link:hover {
            color: inherit;
        }

        .ez-menu-card {
            position: relative;
            min-height: 96px;
            border-radius: 18px;
            overflow: hidden;
            background: linear-gradient(135deg, #242830 0%, #353b45 48%, #505861 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.28);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .ez-menu-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 0%, rgba(255, 255, 255, 0.04) 35%, rgba(255, 255, 255, 0.22) 50%, rgba(255, 255, 255, 0.04) 65%, transparent 100%);
            transform: translateX(-160%);
            transition: transform 0.75s ease;
            pointer-events: none;
        }

        .ez-menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 45px rgba(0, 0, 0, 0.35);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .ez-card-link:hover .ez-menu-card::before {
            transform: translateX(160%);
        }

        .ez-menu-card .card-body {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 96px;
            padding: 1.25rem 1.5rem;
        }

        .ez-menu-title {
            margin-bottom: 0;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .ez-top-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--ez-muted);
        }

        .ez-top-value {
            color: var(--ez-text);
            font-weight: 600;
        }

        .ez-currency,
        .ez-logout {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.7rem 0.9rem;
        }

        .ez-logout {
            color: #ffb4b4;
            text-decoration: none;
        }

        .ez-logout:hover {
            color: #ffd0d0;
        }

        @media (max-width: 991.98px) {
            .ez-navbar {
                height: auto;
                position: static;
            }

            .ez-main {
                height: auto;
                min-height: 100vh;
                padding-top: 1rem;
            }

            .ez-shell,
            html,
            body {
                overflow: auto;
                height: auto;
                min-height: 100vh;
            }

            .ez-panel {
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="ez-shell">
        <header class="ez-navbar">
            <div class="container ez-header-inner d-flex align-items-center justify-content-between gap-3 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="ez-avatar">AZ</div>

                    <div class="d-flex flex-column flex-md-row gap-2 gap-md-4">
                        <div>
                            <div class="ez-top-label">Usuário</div>
                            <div class="ez-top-value">Arcanista Zenith</div>
                        </div>

                        <div>
                            <div class="ez-top-label">Ranking</div>
                            <div class="ez-top-value">Mestre IV</div>
                        </div>

                        <div>
                            <div class="ez-top-label">Nível</div>
                            <div class="ez-top-value">27</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 ms-auto flex-wrap justify-content-end">
                    <div class="ez-currency text-end">
                        <div class="ez-top-label">Cristais</div>
                        <div class="ez-top-value">320</div>
                    </div>

                    <div class="ez-currency text-end">
                        <div class="ez-top-label">Ouro</div>
                        <div class="ez-top-value">12.450</div>
                    </div>

                    <a href="#" class="ez-logout text-end">
                        <div class="ez-top-label">Conta</div>
                        <div class="ez-top-value">Deslogar</div>
                    </a>
                </div>
            </div>
        </header>

        <main class="ez-main">
            <section class="ez-panel">
                

                <div class="ez-content">
                    @yield('content')
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @yield('page_scripts')
</body>
</html>
