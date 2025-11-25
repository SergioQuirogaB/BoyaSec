<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Acceso SIEM'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#6366f1',
                            600: '#4f46e5',
                        },
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 flex items-center justify-center px-6">
    <div class="w-full max-w-md bg-white/10 border border-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <p class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/20 text-white text-xl font-semibold mb-3">B</p>
            <h1 class="text-2xl font-semibold text-white">BoyaSec SIEM Lite</h1>
            <p class="text-sm text-slate-300 mt-1">Monitorea y actúa frente a incidentes en minutos.</p>
        </div>
        <?= $content ?? ''; ?>
        <p class="text-xs text-slate-400 text-center mt-6">© <?= date('Y'); ?> BoyaSec · Hardening & Threat Detection</p>
    </div>
</body>
</html>

