<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Batalha')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @yield('page_styles')
    <style>
        :root {
            --battle-bg: #0a0d17;
            --battle-surface: rgba(18, 23, 38, 0.92);
            --battle-surface-soft: rgba(27, 33, 53, 0.88);
            --battle-border: rgba(255, 255, 255, 0.08);
            --battle-accent: #d4a94f;
            --battle-accent-soft: rgba(212, 169, 79, 0.16);
            --battle-text: #f5f1e8;
            --battle-muted: #a8b0c3;
            --battle-header-h: 126px;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            background:
                radial-gradient(circle at top, rgba(90, 121, 255, 0.12), transparent 28%),
                radial-gradient(circle at bottom, rgba(212, 169, 79, 0.12), transparent 26%),
                linear-gradient(180deg, #05070d 0%, var(--battle-bg) 100%);
            color: var(--battle-text);
            overflow: hidden;
        }

        .battle-shell {
            height: 100vh;
            overflow: hidden;
        }

        .battle-main {
            height: 100vh;
            padding: calc(var(--battle-header-h) + 16px) 16px 16px;
            overflow: hidden;
        }

        .battle-panel {
            height: 100%;
            background: var(--battle-surface);
            border: 1px solid var(--battle-border);
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        @media (max-width: 991.98px) {
            html,
            body,
            .battle-shell,
            .battle-main {
                height: auto;
                min-height: 100vh;
                overflow: auto;
            }

            .battle-main {
                padding-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="battle-shell">
        <main class="battle-main">
            <section class="battle-panel">
                @yield('content')
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @yield('page_scripts')
</body>
</html>
