<?php
?>
<?php
$username = $_SESSION['username'] ?? 'admin';
$initials = strtoupper(substr($username, 0, 2));
?>
<nav class="bg-transparent px-4 sm:px-6 lg:px-8 pt-8">
    <div class="w-full bg-white/10 border border-white/10 backdrop-blur-xl rounded-3xl shadow-brand px-6 py-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-300">BoyaSec · SIEM Lite</p>
            <h1 class="text-2xl font-semibold text-white">Centro de monitoreo y respuesta</h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white flex items-center justify-center font-semibold">
                    <?= htmlspecialchars($initials); ?>
                </div>
                <div>
                    <p class="text-sm text-slate-200">Sesión activa</p>
                    <p class="text-base font-semibold text-white"><?= htmlspecialchars($username); ?></p>
                </div>
            </div>
            <form action="/index.php" method="post">
                <input type="hidden" name="action" value="logout">
                <button class="flex items-center gap-2 bg-white/10 border border-white/20 text-white px-4 py-2 rounded-2xl text-sm font-semibold hover:bg-white/20 transition">
                    <span>Salir</span>
                </button>
            </form>
        </div>
    </div>
</nav>

