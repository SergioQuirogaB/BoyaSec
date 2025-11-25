<?php
require_once __DIR__ . '/../src/Helpers/utils.php';
ensure_auth();

use Controllers\AlertController;

$alertController = new AlertController();
$alerts = $alertController->all();

$title = 'Alertas';
ob_start();
?>
<section class="space-y-8">
    <div class="flex flex-col gap-2">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Alert Management</p>
        <h1 class="text-3xl font-semibold text-white">Alertas generadas</h1>
        <p class="text-sm text-slate-300 max-w-2xl">Cada alerta se crea cuando el motor detecta comportamientos que violan las reglas de fuerza bruta o escaneo. Úsalo como backlog de respuesta.</p>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Total <?= count($alerts); ?></p>
                <h2 class="text-xl font-semibold text-white">Histórico reciente</h2>
            </div>
            <span class="px-3 py-1 rounded-full text-xs bg-pink-500/20 text-pink-100">Motor activo</span>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-white/5">
            <table class="min-w-full text-sm">
                <thead class="bg-white/5 text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Regla</th>
                        <th class="px-4 py-3 text-left">IP</th>
                        <th class="px-4 py-3 text-left">Eventos</th>
                        <th class="px-4 py-3 text-left">Detectado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                <?php foreach ($alerts as $alert): ?>
                    <tr>
                        <td class="px-4 py-3 font-medium"><?= htmlspecialchars($alert['rule_name'] ?? 'Regla'); ?></td>
                        <td class="px-4 py-3 font-mono"><?= htmlspecialchars($alert['ip']); ?></td>
                        <td class="px-4 py-3"><?= (int) $alert['event_count']; ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($alert['detected_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include base_path('layouts/main.php');

