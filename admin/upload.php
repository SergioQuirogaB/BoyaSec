<?php
require_once __DIR__ . '/../src/Helpers/utils.php';
ensure_auth();

use Controllers\LogController;

$logController = new LogController();
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['log_file'])) {
    try {
        $result = $logController->handleUpload($_FILES['log_file'], $_POST['source'] ?? 'manual');
        flash('upload', sprintf(
            'Procesado: %d eventos normalizados · %d alertas nuevas',
            $result['events_normalized'],
            $result['alerts_generated']
        ));
        header('Location: /admin/upload.php');
        exit;
    } catch (\Throwable $th) {
        $error = $th->getMessage();
    }
}

$message = flash('upload');

$title = 'Subir Logs';
ob_start();
?>
<section class="space-y-8">
    <div class="flex flex-col gap-2">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Pipeline</p>
        <h1 class="text-3xl font-semibold text-white">Ingesta de logs</h1>
        <p class="text-sm text-slate-300 max-w-2xl">Carga archivos `.log`, `.txt` o `.csv` para que el SIEM los almacene, normalice y ejecute el motor de correlación automáticamente.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white/5 border border-white/10 rounded-3xl p-6">
            <h2 class="text-xl font-semibold text-white mb-2">Sube tu archivo</h2>
            <p class="text-sm text-slate-400 mb-6">Aceptamos formatos Apache, Nginx o CSV delimitado. Mantén un registro de cada ingestión para auditoría.</p>

            <?php if ($message): ?>
                <div class="mb-4 text-sm text-emerald-200 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl px-4 py-3">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="mb-4 text-sm text-red-200 bg-red-500/10 border border-red-500/30 rounded-2xl px-4 py-3">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 mb-2">Archivo de log</label>
                    <input type="file" name="log_file" accept=".log,.txt,.csv" class="w-full rounded-2xl border border-dashed border-white/20 bg-white/5 px-4 py-4 text-white cursor-pointer" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 mb-2">Fuente</label>
                    <select name="source" class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-white">
                        <option value="apache">Apache</option>
                        <option value="nginx">Nginx</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <button class="w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white py-3 rounded-2xl font-semibold shadow-brand">Procesar</button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
                <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-3">Checklist</h3>
                <ul class="space-y-3 text-sm text-slate-200">
                    <li>• Archivo menor a 5MB para evitar timeouts.</li>
                    <li>• Un evento por línea para resultados más precisos.</li>
                    <li>• Logs CSV deben seguir el orden: fecha,ip,método,ruta,status,UserAgent.</li>
                </ul>
            </div>
            <div class="bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 border border-white/10 rounded-3xl p-6 text-white">
                <h3 class="text-lg font-semibold mb-2">¿Qué sucede después?</h3>
                <p class="text-sm text-slate-100">El archivo se almacena en `logs_raw`, luego el `LogNormalizer` parsea cada línea y la guarda en `logs_normalized`. Finalmente, el `RuleEngine` ejecuta las reglas y dispara alertas si corresponde.</p>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include base_path('layouts/main.php');

