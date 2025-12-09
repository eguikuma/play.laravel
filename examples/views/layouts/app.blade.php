<!DOCTYPE html>
<html lang="ja" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>play.laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        [data-theme="light"] {
            --color-primary: oklch(0.55 0.03 250);
            --color-primary-content: oklch(0.98 0 0);
        }

        .card {
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.06), 0 4px 6px -2px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 1rem;
        }
        .card:hover {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .card, .btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            background: linear-gradient(135deg, oklch(0.55 0.03 250) 0%, oklch(0.45 0.03 250) 100%);
        }

        .avatar > div { box-shadow: 0 2px 4px rgba(0,0,0,0.12); }

        .input:focus { box-shadow: 0 0 0 3px oklch(0.55 0.03 250 / 0.15); }

        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen bg-base-200">
    <div
        x-data="{ show: false, type: '', message: '' }"
        x-on:notify.window="show = true; type = $event.detail.type; message = $event.detail.message; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="toast toast-bottom toast-end z-50"
        x-cloak
    >
        <div class="alert shadow-lg" :class="type === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white'">
            <span x-text="message"></span>
        </div>
    </div>

    <main class="container mx-auto px-4 py-8 max-w-6xl">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
