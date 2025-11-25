<?php
require_once __DIR__ . '/../src/Helpers/utils.php';
ensure_auth();

use Controllers\RuleController;

$ruleController = new RuleController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = [
        'name' => $_POST['name'] ?? '',
        'type' => $_POST['type'] ?? 'brute_force',
        'threshold' => (int) ($_POST['threshold'] ?? 5),
        'window_seconds' => (int) ($_POST['window_seconds'] ?? 300),
        'conditions' => json_decode($_POST['conditions'] ?? '[]', true) ?? [],
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];

    if ($action === 'delete' && isset($_POST['id'])) {
        $ruleController->delete((int) $_POST['id']);
        flash('rule', 'Regla eliminada.');
    } elseif ($action === 'update' && isset($_POST['id'])) {
        $ruleController->update((int) $_POST['id'], $data);
        flash('rule', 'Regla actualizada.');
    } else {
        $ruleController->store($data);
        flash('rule', 'Regla creada.');
    }

    header('Location: /admin/rules.php');
    exit;
}

$rules = $ruleController->index();
$activeRules = array_sum(array_map(fn ($rule) => (int) $rule['is_active'], $rules));

$title = 'Reglas';
ob_start();
?>
<section class="space-y-8">
    <div class="flex flex-col gap-2">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Rule Engine</p>
        <h1 class="text-3xl font-semibold text-white">Reglas y correlación</h1>
        <p class="text-sm text-slate-300 max-w-2xl">Define las condiciones que disparan alertas. Ajusta umbrales, ventanas temporales y condiciones avanzadas en JSON de forma centralizada.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6 lg:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400 mb-1">Nueva regla</p>
                    <h2 class="text-xl font-semibold text-white">Motor dinámico</h2>
                </div>
                <span class="px-3 py-1 rounded-full text-xs bg-indigo-500/20 text-indigo-100"><?= $activeRules; ?> activas</span>
            </div>
            <?php if ($message = flash('rule')): ?>
                <div class="mb-4 text-sm text-emerald-200 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl px-4 py-3">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="post" class="space-y-4">
                <input type="hidden" name="action" value="create">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 mb-2">Nombre</label>
                    <input type="text" name="name" class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 mb-2">Tipo</label>
                    <select name="type" class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white">
                        <option value="brute_force">Brute Force</option>
                        <option value="scanner">Scanner</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 mb-2">Threshold</label>
                        <input type="number" name="threshold" class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white" value="5">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 mb-2">Ventana (seg)</label>
                        <input type="number" name="window_seconds" class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white" value="300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 mb-2">Condiciones (JSON)</label>
                    <textarea name="conditions" class="w-full h-24 rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white text-sm">[]</textarea>
                </div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" class="rounded border-white/20 bg-white/5" checked>
                    <span class="text-sm text-slate-200">Regla activa</span>
                </label>
                <button class="w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white py-3 rounded-2xl font-semibold shadow-brand">Guardar</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white/5 border border-white/10 rounded-3xl p-6 overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Reglas actuales</p>
                    <h2 class="text-xl font-semibold text-white">Inventario</h2>
                </div>
                <span class="px-3 py-1 rounded-full text-xs bg-white/10 text-slate-200"><?= count($rules); ?> totales</span>
            </div>
            <div class="overflow-x-auto rounded-2xl border border-white/5">
                <table class="min-w-full text-sm">
                    <thead class="bg-white/5 text-slate-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Nombre</th>
                            <th class="px-4 py-3 text-left">Tipo</th>
                            <th class="px-4 py-3 text-left">Threshold</th>
                            <th class="px-4 py-3 text-left">Ventana</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-200">
                    <?php foreach ($rules as $rule): ?>
                        <tr>
                            <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($rule['name']); ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($rule['type']); ?></td>
                            <td class="px-4 py-3"><?= (int) $rule['threshold']; ?></td>
                            <td class="px-4 py-3"><?= (int) $rule['window_seconds']; ?>s</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs <?= $rule['is_active'] ? 'bg-emerald-500/20 text-emerald-100' : 'bg-slate-500/20 text-slate-200'; ?>">
                                    <?= $rule['is_active'] ? 'Activa' : 'Inactiva'; ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <form method="post" class="inline">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= (int) $rule['id']; ?>">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($rule['name'], ENT_QUOTES); ?>">
                                    <input type="hidden" name="type" value="<?= htmlspecialchars($rule['type'], ENT_QUOTES); ?>">
                                    <input type="hidden" name="threshold" value="<?= (int) $rule['threshold']; ?>">
                                    <input type="hidden" name="window_seconds" value="<?= (int) $rule['window_seconds']; ?>">
                                    <input type="hidden" name="conditions" value="<?= htmlspecialchars($rule['conditions'] ?? '[]', ENT_QUOTES); ?>">
                                    <input type="hidden" name="is_active" value="<?= $rule['is_active'] ? 0 : 1; ?>">
                                    <button class="text-xs text-indigo-300 hover:text-indigo-100">Toggle</button>
                                </form>
                                <form method="post" class="inline" onsubmit="return confirm('¿Eliminar regla?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $rule['id']; ?>">
                                    <button class="text-xs text-rose-300 hover:text-rose-100">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include base_path('layouts/main.php');

