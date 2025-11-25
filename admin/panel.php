<?php
require_once __DIR__ . '/../src/Helpers/utils.php';
ensure_auth();

use Controllers\LogController;
use Controllers\AlertController;

$logController = new LogController();
$alertController = new AlertController();

$dashboard = $logController->getDashboardData();
$alerts = $alertController->latest(5);

$title = 'Dashboard';
ob_start();
?>
<section class="space-y-10">
    <div class="bg-gradient-to-br from-indigo-500/20 via-purple-500/10 to-cyan-500/20 border border-white/10 rounded-3xl p-8 text-white shadow-brand">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-200 mb-2">Situational Awareness</p>
        <h2 class="text-3xl font-semibold mb-2">Visibilidad completa de eventos críticos</h2>
        <p class="text-sm text-slate-100 max-w-2xl">Ingiere, normaliza y correlaciona logs HTTP para detectar fuerza bruta, escaneos y patrones anómalos en cuestión de segundos.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php
        $cards = [
            ['title' => 'Top IPs monitoreadas', 'value' => count($dashboard['top_ips']), 'accent' => 'indigo'],
            ['title' => 'Métodos detectados', 'value' => count($dashboard['methods']), 'accent' => 'emerald'],
            ['title' => 'Statuses detectados', 'value' => count($dashboard['statuses']), 'accent' => 'amber'],
        ];
        foreach ($cards as $card) {
            include base_path('components/card.php');
        }
        ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs tracking-[0.4em] text-slate-400 uppercase">Inteligencia</p>
                    <h3 class="text-xl font-semibold text-white">Top 10 IPs</h3>
                </div>
                <span class="px-3 py-1 rounded-full text-xs bg-white/10 text-slate-300">Tiempo real</span>
            </div>
            <div class="overflow-hidden rounded-2xl border border-white/5">
                <table class="min-w-full text-sm">
                    <thead class="bg-white/5 text-slate-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">IP</th>
                            <th class="px-4 py-3 text-left">Eventos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-200">
                    <?php foreach ($dashboard['top_ips'] as $row): ?>
                        <tr>
                            <td class="px-4 py-3 font-mono"><?= htmlspecialchars($row['ip']); ?></td>
                            <td class="px-4 py-3"><?= (int) $row['total']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs tracking-[0.4em] text-slate-400 uppercase">Alerting</p>
                    <h3 class="text-xl font-semibold text-white">Alertas recientes</h3>
                </div>
                <span class="px-3 py-1 rounded-full text-xs bg-pink-500/20 text-pink-100"><?= count($alerts); ?> activas</span>
            </div>
            <ul class="space-y-3">
                <?php foreach ($alerts as $alert): ?>
                    <li class="bg-gradient-to-r from-white/10 to-white/5 rounded-2xl border border-white/10 p-4">
                        <p class="text-sm font-semibold text-white">
                            <?= htmlspecialchars($alert['rule_name'] ?? 'Regla'); ?>
                        </p>
                        <p class="text-xs text-slate-300 mt-1">
                            IP: <?= htmlspecialchars($alert['ip']); ?> · Eventos: <?= (int) $alert['event_count']; ?>
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            Detectado: <?= htmlspecialchars($alert['detected_at']); ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
            <h3 class="text-sm uppercase tracking-[0.4em] text-slate-400 mb-4">Eventos por método</h3>
            <?php if ($dashboard['methods']): ?>
                <div class="space-y-3">
                    <?php foreach ($dashboard['methods'] as $method): ?>
                        <div class="flex items-center justify-between text-sm text-white bg-white/5 rounded-2xl px-4 py-3 border border-white/5">
                            <span><?= htmlspecialchars($method['method']); ?></span>
                            <span class="font-semibold"><?= (int) $method['total']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm text-slate-400">Sin datos.</p>
            <?php endif; ?>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
            <h3 class="text-sm uppercase tracking-[0.4em] text-slate-400 mb-4">Eventos por status</h3>
            <?php if ($dashboard['statuses']): ?>
                <div class="space-y-3">
                    <?php foreach ($dashboard['statuses'] as $status): ?>
                        <div class="flex items-center justify-between text-sm text-white bg-white/5 rounded-2xl px-4 py-3 border border-white/5">
                            <span><?= htmlspecialchars($status['status_code']); ?></span>
                            <span class="font-semibold"><?= (int) $status['total']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm text-slate-400">Sin datos.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include base_path('layouts/main.php');

