<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111827">
    <title>Studio Warna — ColorOfDay</title>
    <link href="./dist/output.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#111827] font-sans text-slate-100">
    <header class="sticky top-0 z-50 border-b border-slate-800 bg-[#111827]/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="./index.html" class="text-xl font-bold text-cyan-400 md:text-2xl">Color<span class="text-white">OfDay</span></a>
            <nav class="flex items-center gap-2 text-xs md:gap-6 md:text-base">
                <a href="./index.html" class="font-medium transition hover:text-cyan-400">Beranda</a>
                <a href="./index.html#cerita" class="hidden font-medium transition hover:text-cyan-400 md:inline">Rekomendasi</a>
                <a href="./palette-studio.php" aria-current="page" class="font-semibold text-cyan-300"><span class="sm:hidden">Studio</span><span class="hidden sm:inline">Studio Warna</span></a>
                <a href="./favorites.html" class="font-medium transition hover:text-cyan-400">Favorit <span data-favorite-count class="text-cyan-400"></span></a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-14 md:py-20">
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex rounded-full border border-cyan-700/60 bg-cyan-950/50 px-4 py-2 text-sm font-semibold text-cyan-300">COLOR PLAYGROUND</span>
            <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-white md:text-6xl">Warna yang pas, <span class="text-cyan-400">mood yang naik.</span></h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-slate-400">Pilih suasana gayamu dan temukan palet warna baru. Klik chip untuk menyalin kode warna.</p>
        </div>

        <section class="mx-auto mt-12 max-w-4xl rounded-3xl border border-slate-700 bg-[#131C2E] p-6 shadow-2xl shadow-cyan-950/20 md:p-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label for="paletteMood" class="mb-2 block text-sm font-semibold text-slate-300">Pilih mood atau acara</label>
                    <select id="paletteMood" class="w-full rounded-xl border border-slate-600 bg-[#0F172A] px-4 py-3 text-white outline-none transition focus:border-cyan-400">
                        <option value="all">Beri kejutan ✨</option>
                        <option value="Calm">Tenang & nyaman</option>
                        <option value="Bold">Berani & percaya diri</option>
                        <option value="Fresh">Segar & ceria</option>
                        <option value="Elegant">Elegan & rapi</option>
                        <option value="Romantic">Lembut & romantis</option>
                    </select>
                </div>
                <button id="shufflePalette" type="button" class="rounded-xl bg-cyan-500 px-6 py-3 font-bold text-white transition hover:bg-cyan-600">Acak palet ✨</button>
            </div>
            <div id="paletteResult" aria-live="polite" class="mt-8"></div>
            <p id="paletteMessage" role="status" class="mt-4 min-h-6 text-sm text-emerald-300"></p>
        </section>

        <section class="mx-auto mt-12 max-w-4xl rounded-3xl border border-slate-700 bg-[#131C2E] p-6 shadow-2xl shadow-cyan-950/20 md:p-10">
            <div>
                <span class="text-sm font-bold uppercase tracking-widest text-cyan-300">Custom Color Palette Collector</span>
                <h2 class="mt-3 text-2xl font-extrabold text-white md:text-3xl">Simpan palet buatanmu</h2>
                <p class="mt-2 text-slate-400">Pilih 3 atau 4 warna, lalu kumpulkan palet favoritmu.</p>
            </div>

            <?php if ($saved): ?>
                <p role="status" class="mt-6 rounded-xl border border-emerald-800 bg-emerald-950/50 px-4 py-3 text-sm text-emerald-300">Palette berhasil disimpan.</p>
            <?php endif; ?>
            <?php if ($formError !== ''): ?>
                <p role="alert" class="mt-6 rounded-xl border border-rose-800 bg-rose-950/50 px-4 py-3 text-sm text-rose-300"><?= htmlspecialchars($formError, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <?php endif; ?>

            <form method="post" action="palette-studio.php#koleksi" class="mt-8 space-y-6">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nama" class="mb-2 block text-sm font-semibold text-slate-300">Nama Palette</label>
                        <input id="nama" name="nama" type="text" maxlength="80" required value="<?= htmlspecialchars($formData['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-600 bg-[#0F172A] px-4 py-3 text-white outline-none transition focus:border-cyan-400" placeholder="Contoh: Senja di Pantai">
                    </div>
                    <div>
                        <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-300">Kategori</label>
                        <input id="kategori" name="kategori" type="text" maxlength="40" required value="<?= htmlspecialchars($formData['category'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-600 bg-[#0F172A] px-4 py-3 text-white outline-none transition focus:border-cyan-400" placeholder="Contoh: Calm, Fresh, atau Favorit">
                    </div>
                </div>

                <fieldset>
                    <legend class="mb-3 text-sm font-semibold text-slate-300">Warna palette (3 wajib, warna ke-4 opsional)</legend>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <?php foreach ($pickerColors as $index => $color): ?>
                            <div class="rounded-xl border border-slate-700 bg-[#0F172A] p-4">
                                <label for="warna-<?= $index + 1 ?>" class="mb-3 block text-sm font-medium text-slate-300">Warna <?= $index + 1 ?></label>
                                <div class="flex items-center gap-3">
                                    <input type="color" data-color-picker="<?= $index ?>" value="<?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8') ?>" aria-label="Pilih warna <?= $index + 1 ?>" class="h-11 w-14 cursor-pointer rounded-lg border-0 bg-transparent">
                                    <input id="warna-<?= $index + 1 ?>" name="warna[]" type="text" inputmode="text" pattern="#[0-9A-Fa-f]{6}" maxlength="7" required value="<?= htmlspecialchars($formData['colors'][$index], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-lg border border-slate-600 bg-[#111827] px-3 py-2.5 font-mono text-white outline-none focus:border-cyan-400" aria-label="Kode HEX warna <?= $index + 1 ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="rounded-xl border border-slate-700 bg-[#0F172A] p-4">
                            <label for="warna-4" class="mb-3 block text-sm font-medium text-slate-300">Warna 4 (opsional)</label>
                            <input id="warna-4" name="warna[]" type="text" inputmode="text" pattern="#[0-9A-Fa-f]{6}" maxlength="7" value="<?= htmlspecialchars($formData['colors'][3], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-lg border border-slate-600 bg-[#111827] px-3 py-2.5 font-mono text-white outline-none focus:border-cyan-400" placeholder="#FFFFFF">
                        </div>
                    </div>
                </fieldset>
                <button type="submit" class="rounded-xl bg-cyan-500 px-6 py-3 font-bold text-white transition hover:bg-cyan-600">Simpan Palette</button>
            </form>
        </section>

        <section id="koleksi" class="mt-16 scroll-mt-24">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                <div>
                    <span class="text-sm font-bold uppercase tracking-widest text-cyan-300">Koleksi</span>
                    <h2 class="mt-2 text-3xl font-extrabold text-white">Palette tersimpan</h2>
                </div>
                <p class="text-sm text-slate-400"><?= count($palettes) ?> palette</p>
            </div>

            <?php if ($palettes === []): ?>
                <p class="mt-6 rounded-2xl border border-dashed border-slate-700 p-8 text-center text-slate-400">Belum ada palette. Buat palette pertamamu di atas.</p>
            <?php else: ?>
                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <?php foreach ($palettes as $palette): ?>
                        <article class="rounded-2xl border border-slate-700 bg-[#131C2E] p-5">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-lg font-bold text-white"><?= htmlspecialchars($palette->getNamaPalette(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h3>
                                <span class="shrink-0 rounded-full border border-cyan-800 bg-cyan-950/70 px-3 py-1 text-xs font-semibold text-cyan-300"><?= htmlspecialchars($palette->getKategori(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                            </div>
                            <div class="mt-5 flex gap-2">
                                <?php foreach ($palette->getArrayWarna() as $color): ?>
                                    <div class="min-w-0 flex-1 overflow-hidden rounded-lg border border-white/10">
                                        <div class="h-16" style="background-color: <?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8') ?>"></div>
                                        <p class="bg-[#0F172A] px-2 py-2 text-center font-mono text-xs text-slate-300"><?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <p class="mt-4 text-xs text-slate-500">Dibuat <?= htmlspecialchars((new DateTimeImmutable($palette->getTanggal()))->format('d M Y, H:i'), ENT_QUOTES, 'UTF-8') ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <div class="mt-10 text-center">
            <a href="./index.html#cerita" class="font-semibold text-cyan-300 transition hover:text-cyan-200">Sudah punya palet? Buat rekomendasi outfit →</a>
        </div>
    </main>

    <footer class="border-t border-slate-800 bg-[#0B0F17] px-6 py-8 text-center">
        <p class="font-bold text-cyan-400">ColorOfDay</p>
        <p class="mt-2 text-sm text-slate-500">Mix warna, ikuti mood, jadi diri sendiri.</p>
    </footer>
    <script src="./src/js/features.js"></script>
    <script>
        document.querySelectorAll('[data-color-picker]').forEach((picker) => {
            const hexInput = document.querySelector(`#warna-${Number(picker.dataset.colorPicker) + 1}`);
            picker.addEventListener('input', () => { hexInput.value = picker.value.toUpperCase(); });
            hexInput.addEventListener('input', () => {
                if (/^#[0-9A-Fa-f]{6}$/.test(hexInput.value)) picker.value = hexInput.value;
            });
        });
    </script>
</body>
</html>
