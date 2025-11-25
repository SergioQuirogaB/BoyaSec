<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'BoyaSec SIEM'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Space Grotesk"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            900: '#1e1b4b',
                        },
                    },
                    boxShadow: {
                        brand: '0 25px 50px -12px rgba(99, 102, 241, 0.35)',
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-slate-950 text-slate-100 font-sans">
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800">
        <?php include base_path('components/navbar.php'); ?>
        <div class="flex max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 gap-8">
            <?php include base_path('components/sidebar.php'); ?>
            <main class="flex-1">
                <div class="bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 shadow-brand p-8">
                    <?= $content ?? ''; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

