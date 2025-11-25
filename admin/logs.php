<?php
require_once __DIR__ . '/../src/Helpers/utils.php';
ensure_auth();

use Controllers\LogController;

$logController = new LogController();
$logs = $logController->latestNormalized();

$title = 'Logs Normalizados';
ob_start();
?>
<section class="space-y-8">
    <div class="flex flex-col gap-2">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Data Lake</p>
        <h1 class="text-3xl font-semibold text-white">Logs normalizados</h1>
        <p class="text-sm text-slate-300 max-w-2xl">Cada línea fue procesada por el normalizador (Apache/Nginx/CSV) y almacenada con campos consistentes para correlacionar eventos.</p>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Últimos <?= count($logs); ?> eventos</p>
                <h2 class="text-xl font-semibold text-white">Detalle completo</h2>
            </div>
            <span class="px-3 py-1 rounded-full text-xs bg-emerald-500/20 text-emerald-100">Normalizados</span>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-white/5">
            <table class="min-w-full text-sm">
                <thead class="bg-white/5 text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">IP</th>
                        <th class="px-4 py-3 text-left">Método</th>
                        <th class="px-4 py-3 text-left">Ruta</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">User Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="px-4 py-3"><?= htmlspecialchars($log['event_time']); ?></td>
                        <td class="px-4 py-3 font-mono"><?= htmlspecialchars($log['ip']); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($log['method']); ?></td>
                        <td class="px-4 py-3 truncate max-w-xs"><?= htmlspecialchars($log['path']); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs bg-white/10 border border-white/10">
                                <?= htmlspecialchars($log['status_code']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-400"><?= htmlspecialchars($log['user_agent']); ?></td>
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

