<?php
/**
 * Espera una variable $card con las claves:
 * - title
 * - value
 * - description (opcional)
 */
?>
<?php
$palette = [
    'indigo' => 'from-indigo-500/80 to-purple-500/80',
    'emerald' => 'from-emerald-400/80 to-cyan-400/80',
    'amber' => 'from-amber-400/80 to-orange-500/80',
    'pink' => 'from-pink-500/80 to-rose-500/80',
];
$accent = $card['accent'] ?? array_rand($palette);
$gradient = $palette[$accent] ?? reset($palette);
?>
<div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-5 shadow-brand">
    <div class="absolute inset-0 bg-gradient-to-br <?= $gradient; ?> opacity-10"></div>
    <div class="relative">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-300 mb-3">
            <?= htmlspecialchars($card['title'] ?? 'Título'); ?>
        </p>
        <p class="text-3xl font-semibold text-white">
            <?= htmlspecialchars($card['value'] ?? '0'); ?>
        </p>
        <?php if (!empty($card['description'])): ?>
            <p class="text-xs text-slate-400 mt-2">
                <?= htmlspecialchars($card['description']); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

