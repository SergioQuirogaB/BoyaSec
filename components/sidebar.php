<?php
?>
<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$menu = [
    [
        'label' => 'Dashboard',
        'href' => '/admin/panel.php',
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>',
    ],
    [
        'label' => 'Subir Logs',
        'href' => '/admin/upload.php',
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 0l-3 3m3-3l3 3M6 20h12a2 2 0 002-2V8.414a2 2 0 00-.586-1.414l-4.414-4.414A2 2 0 0013.586 2H6a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
    ],
    [
        'label' => 'Logs Normalizados',
        'href' => '/admin/logs.php',
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>',
    ],
    [
        'label' => 'Alertas',
        'href' => '/admin/alerts.php',
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z"/></svg>',
    ],
    [
        'label' => 'Reglas',
        'href' => '/admin/rules.php',
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10m-10 6h16"/></svg>',
    ],
];
?>
<aside class="w-64 shrink-0">
    <div class="sticky top-10 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-xl p-6 space-y-4">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Menú</p>
            <p class="text-lg font-semibold text-white">Navegación</p>
        </div>
        <ul class="space-y-2">
            <?php foreach ($menu as $item): 
                $active = $currentPath === $item['href'];
            ?>
                <li>
                    <a href="<?= $item['href']; ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition <?= $active ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-brand' : 'text-slate-300 hover:bg-white/5'; ?>">
                        <span class="<?= $active ? 'text-white' : 'text-slate-400'; ?>"><?= $item['icon']; ?></span>
                        <?= htmlspecialchars($item['label']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</aside>

