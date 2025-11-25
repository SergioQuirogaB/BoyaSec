<?php
require_once __DIR__ . '/src/Helpers/utils.php';

use Controllers\AuthController;

$auth = new AuthController();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';

    if ($action === 'logout') {
        $auth->logout();
        header('Location: /index.php');
        exit;
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->attemptLogin($username, $password)) {
        header('Location: /admin/panel.php');
        exit;
    } else {
        $error = 'Credenciales inválidas';
    }
}

if (is_authenticated()) {
    header('Location: /admin/panel.php');
    exit;
}

$title = 'Acceso BoyaSec';
ob_start();
?>
<?php if ($error): ?>
    <div class="mb-4 text-sm text-red-300 bg-red-500/10 border border-red-500/30 rounded-xl px-3 py-2">
        <?= htmlspecialchars($error); ?>
    </div>
<?php endif; ?>
<form method="post" class="space-y-5">
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300 mb-2">Usuario</label>
        <input type="text" name="username" class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="admin" required>
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-300 mb-2">Contraseña</label>
        <input type="password" name="password" class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="••••••••" required>
    </div>
    <button class="w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white py-3 rounded-2xl shadow-lg shadow-indigo-500/30 font-semibold tracking-wide uppercase text-sm">Ingresar</button>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/auth.php';

