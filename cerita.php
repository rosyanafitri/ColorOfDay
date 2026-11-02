<?php
declare(strict_types=1);

const STORY_COLOR_OPTIONS = [
    'Off-White Cream',
    'Classic Denim Blue',
    'Pure White',
    'Charcoal Dark Grey',
    'Soft Beige / Oatmeal',
    'Jet Black',
    'Sage Green',
    'Sand Khaki / Cream',
    'Terracotta Warm',
    'Off-White / Beige',
    'Mocha / Chocolate',
    'Soft Ivory Cream',
    'Mustard Gold',
    'Deep Navy Blue',
    'Dusty Rose / Mauve',
    'Light Grey / Off-White',
    'Sky Blue Breeze',
    'White / Light Khaki',
    'Burgundy Wine',
    'Black / Dark Denim',
    'Lilac Lavender',
    'Cloudy White',
    'Olive Green',
    'Warm Cream / Off-White',
];

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sortStoriesByDate(array &$stories): void
{
    usort($stories, static function (array $first, array $second): int {
        return strtotime($second['tanggal'] ?? '') <=> strtotime($first['tanggal'] ?? '');
    });
}

function decodeStories(string $contents): array
{
    if (trim($contents) === '') {
        return [];
    }

    try {
        $stories = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        throw new RuntimeException('Data riwayat cerita bukan JSON yang valid.', 0, $exception);
    }
    if (!is_array($stories) || !array_is_list($stories)) {
        throw new RuntimeException('Format data riwayat cerita tidak valid.');
    }

    foreach ($stories as $story) {
        if (
            !is_array($story)
            || !is_string($story['id'] ?? null)
            || !is_string($story['tanggal'] ?? null)
            || !is_string($story['judul_cerita'] ?? null)
            || !is_string($story['isi_cerita'] ?? null)
            || !is_array($story['rekomendasi_warna'] ?? null)
            || !array_is_list($story['rekomendasi_warna'])
        ) {
            throw new RuntimeException('Format cerita di dalam riwayat tidak valid.');
        }
        foreach ($story['rekomendasi_warna'] as $color) {
            if (!is_string($color)) {
                throw new RuntimeException('Format rekomendasi warna di dalam riwayat tidak valid.');
            }
        }
    }

    return $stories;
}

function getSuggestedColors(string $story): array
{
    $story = mb_strtolower($story, 'UTF-8');

    if (str_contains($story, 'hujan') || str_contains($story, 'dingin') || str_contains($story, 'mendung')) {
        return ['Soft Beige / Oatmeal', 'Classic Denim Blue'];
    }
    if (str_contains($story, 'rapat') || str_contains($story, 'kerja') || str_contains($story, 'presentasi') || str_contains($story, 'skripsi')) {
        return ['Pure White', 'Charcoal Dark Grey'];
    }
    if (str_contains($story, 'jalan') || str_contains($story, 'nongkrong') || str_contains($story, 'cafe') || str_contains($story, 'hangout')) {
        return ['Sky Blue Breeze', 'White / Light Khaki'];
    }
    if (str_contains($story, 'galau') || str_contains($story, 'capek') || str_contains($story, 'sedih') || str_contains($story, 'pusing')) {
        return ['Dusty Rose / Mauve', 'Light Grey / Off-White'];
    }
    if (str_contains($story, 'panas') || str_contains($story, 'pantai') || str_contains($story, 'cerah')) {
        return ['Sage Green', 'Sand Khaki / Cream'];
    }

    return ['Off-White Cream', 'Classic Denim Blue'];
}

function getSuggestedTitle(string $story): string
{
    $story = mb_strtolower($story, 'UTF-8');

    if (str_contains($story, 'rapat') || str_contains($story, 'kerja') || str_contains($story, 'presentasi') || str_contains($story, 'skripsi')) {
        return 'Professional & Confident';
    }
    if (str_contains($story, 'hujan') || str_contains($story, 'dingin') || str_contains($story, 'mendung')) {
        return 'Cozy & Warm Layers';
    }
    if (str_contains($story, 'jalan') || str_contains($story, 'nongkrong') || str_contains($story, 'cafe') || str_contains($story, 'hangout')) {
        return 'Trendy Urban Chic';
    }
    if (str_contains($story, 'galau') || str_contains($story, 'capek') || str_contains($story, 'sedih') || str_contains($story, 'pusing')) {
        return 'Healing Comfort Vibe';
    }
    if (str_contains($story, 'panas') || str_contains($story, 'pantai') || str_contains($story, 'cerah')) {
        return 'Breezy Summer Refresh';
    }

    return 'Effortless Daily Look';
}

function readStories(string $storageFile): array
{
    if (!is_dir(dirname($storageFile)) && !mkdir(dirname($storageFile), 0775, true) && !is_dir(dirname($storageFile))) {
        throw new RuntimeException('Folder penyimpanan riwayat tidak dapat dibuat.');
    }

    $handle = fopen($storageFile, 'c+');
    if ($handle === false) {
        throw new RuntimeException('File penyimpanan riwayat tidak dapat dibuka.');
    }

    try {
        if (!flock($handle, LOCK_SH)) {
            throw new RuntimeException('Riwayat cerita tidak dapat dikunci untuk dibaca.');
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        if ($contents === false) {
            throw new RuntimeException('File riwayat cerita tidak dapat dibaca.');
        }
        $stories = decodeStories($contents);
        sortStoriesByDate($stories);
        return $stories;
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function addStory(string $storageFile, array $story): void
{
    if (!is_dir(dirname($storageFile)) && !mkdir(dirname($storageFile), 0775, true) && !is_dir(dirname($storageFile))) {
        throw new RuntimeException('Folder penyimpanan riwayat tidak dapat dibuat.');
    }

    $handle = fopen($storageFile, 'c+');
    if ($handle === false) {
        throw new RuntimeException('File penyimpanan riwayat tidak dapat dibuka.');
    }

    try {
        if (!flock($handle, LOCK_EX)) {
            throw new RuntimeException('Riwayat cerita tidak dapat dikunci untuk ditulis.');
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        if ($contents === false) {
            throw new RuntimeException('File riwayat cerita tidak dapat dibaca.');
        }
        $stories = decodeStories($contents);
        $stories[] = $story;
        sortStoriesByDate($stories);
        $json = json_encode($stories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        rewind($handle);
        if (!ftruncate($handle, 0) || fwrite($handle, $json) !== strlen($json) || !fflush($handle)) {
            throw new RuntimeException('Riwayat cerita tidak dapat disimpan sepenuhnya.');
        }
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

$storageFile = __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cerita.json';
$isAjaxRequest = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
$formError = '';
$formTitle = '';
$formStory = '';
$stories = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawTitle = $_POST['judul_cerita'] ?? '';
    $rawStory = $_POST['isi_cerita'] ?? '';
    $formTitle = is_string($rawTitle) ? trim($rawTitle) : '';
    $formStory = is_string($rawStory) ? trim($rawStory) : '';

    $storyLength = preg_match_all('/./us', $formStory);
    $titleLength = preg_match_all('/./us', $formTitle);
    if ($formStory === '' || $storyLength === false || $storyLength > 500) {
        $formError = 'Cerita wajib diisi dan maksimal 500 karakter.';
    } elseif ($titleLength === false || $titleLength > 100) {
        $formError = 'Judul cerita maksimal 100 karakter.';
    } else {
        $postedColors = $_POST['rekomendasi_warna'] ?? [];
        $postedColors = is_array($postedColors) ? $postedColors : [];
        $colors = array_values(array_unique(array_filter(
            $postedColors,
            static fn ($color): bool => is_string($color) && in_array($color, STORY_COLOR_OPTIONS, true)
        )));
        if (count($colors) < 2) {
            $colors = getSuggestedColors($formStory);
        }

        $story = [
            'id' => bin2hex(random_bytes(8)),
            'tanggal' => date(DATE_ATOM),
            'judul_cerita' => $formTitle !== '' ? $formTitle : getSuggestedTitle($formStory),
            'isi_cerita' => $formStory,
            'rekomendasi_warna' => $colors,
        ];

        try {
            addStory($storageFile, $story);
            if ($isAjaxRequest) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'message' => 'Cerita berhasil disimpan ke riwayat.'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                exit;
            }

            header('Location: cerita.php?saved=1#riwayat', true, 303);
            exit;
        } catch (RuntimeException $exception) {
            $formError = $exception->getMessage();
            if ($isAjaxRequest) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $formError], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                exit;
            }
        }
    }

    if ($isAjaxRequest && $formError !== '') {
        http_response_code(400);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => $formError], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        exit;
    }
}

try {
    $stories = readStories($storageFile);
} catch (RuntimeException $exception) {
    $formError = $formError !== '' ? $formError : $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111827">
    <title>Riwayat Cerita Outfit — ColorOfDay</title>
    <link href="./dist/output.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#111827] font-sans text-slate-100">
    <header class="sticky top-0 z-50 border-b border-slate-800 bg-[#111827]/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="./index.html" class="text-xl font-bold text-cyan-400 md:text-2xl">Color<span class="text-white">OfDay</span></a>
            <nav class="flex items-center gap-4 text-sm md:gap-6 md:text-base">
                <a href="./index.html" class="font-medium transition hover:text-cyan-400">Beranda</a>
                <a href="./index.html#cerita" class="font-medium transition hover:text-cyan-400">Rekomendasi</a>
                <a href="./cerita.php" aria-current="page" class="font-semibold text-cyan-300">Riwayat</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-14 md:py-20">
        <section class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-300">Story History</span>
                <h1 class="mt-3 text-4xl font-extrabold text-white md:text-5xl">Riwayat Cerita <span class="text-cyan-400">📖</span></h1>
                <p class="mt-4 leading-relaxed text-slate-400">Simpan situasi dan mood yang kamu ceritakan saat mencari outfit. Cerita terbaru akan muncul lebih dulu.</p>

                <form action="./cerita.php" method="post" class="mt-8 rounded-2xl border border-slate-800 bg-[#131C2E] p-6">
                    <h2 class="text-xl font-bold text-white">Tambah cerita</h2>
                    <label for="judul_cerita" class="mt-5 block text-sm font-medium text-slate-300">Judul (opsional)</label>
                    <input id="judul_cerita" name="judul_cerita" type="text" maxlength="100" value="<?= escapeHtml($formTitle) ?>" placeholder="Kosongkan untuk judul otomatis" class="mt-2 w-full rounded-xl border border-slate-700 bg-[#1A2333] px-4 py-3 text-white placeholder-slate-500 focus:border-cyan-500 focus:outline-none">

                    <label for="isi_cerita" class="mt-5 block text-sm font-medium text-slate-300">Ceritakan harimu</label>
                    <textarea id="isi_cerita" name="isi_cerita" rows="5" maxlength="500" required placeholder="Contoh: Hari ini hujan dan aku ada rapat penting..." class="mt-2 w-full resize-y rounded-xl border border-slate-700 bg-[#1A2333] px-4 py-3 text-white placeholder-slate-500 focus:border-cyan-500 focus:outline-none"><?= escapeHtml($formStory) ?></textarea>

                    <?php if ($formError !== ''): ?>
                        <p role="alert" class="mt-3 text-sm text-rose-400"><?= escapeHtml($formError) ?></p>
                    <?php elseif (isset($_GET['saved'])): ?>
                        <p role="status" class="mt-3 text-sm text-emerald-400">Cerita berhasil disimpan ke riwayat.</p>
                    <?php endif; ?>

                    <button type="submit" class="mt-5 w-full rounded-full bg-cyan-500 px-6 py-3 font-bold text-white shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-600">Simpan Cerita</button>
                </form>
            </div>

            <section id="riwayat" aria-labelledby="history-heading">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Tersimpan</span>
                        <h2 id="history-heading" class="mt-2 text-2xl font-extrabold text-white">Semua Cerita</h2>
                    </div>
                    <span class="text-sm text-slate-500"><?= count($stories) ?> cerita</span>
                </div>

                <?php if ($stories === []): ?>
                    <div class="mt-6 rounded-2xl border border-dashed border-slate-700 bg-[#131C2E]/60 p-10 text-center">
                        <p class="text-4xl">💭</p>
                        <h3 class="mt-4 text-lg font-bold text-white">Belum ada cerita</h3>
                        <p class="mt-2 text-sm text-slate-400">Ceritakan harimu di formulir atau mulai dari rekomendasi outfit.</p>
                        <a href="./index.html#cerita" class="mt-5 inline-block rounded-full bg-cyan-500 px-5 py-2.5 font-semibold text-white transition hover:bg-cyan-600">Cari rekomendasi outfit</a>
                    </div>
                <?php else: ?>
                    <div class="mt-6 space-y-4">
                        <?php foreach ($stories as $story): ?>
                            <article class="rounded-2xl border border-slate-800 bg-[#131C2E] p-6 shadow-lg shadow-black/10">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <h3 class="text-lg font-bold text-white"><?= escapeHtml((string) ($story['judul_cerita'] ?? 'Cerita outfit')) ?></h3>
                                    <time datetime="<?= escapeHtml((string) ($story['tanggal'] ?? '')) ?>" class="rounded-full border border-cyan-800/70 bg-cyan-950/50 px-3 py-1 text-xs font-semibold text-cyan-300">
                                        <?= escapeHtml(date('d M Y, H:i', strtotime((string) ($story['tanggal'] ?? '')) ?: 0)) ?>
                                    </time>
                                </div>
                                <p class="mt-4 whitespace-pre-line leading-relaxed text-slate-300"><?= escapeHtml((string) ($story['isi_cerita'] ?? '')) ?></p>
                                <div class="mt-5 flex flex-wrap items-center gap-2">
                                    <span class="mr-1 text-xs font-bold uppercase tracking-wider text-slate-500">Rekomendasi warna</span>
                                    <?php foreach (($story['rekomendasi_warna'] ?? []) as $color): ?>
                                        <span class="rounded-full border border-slate-700 bg-[#1A2333] px-3 py-1 text-xs font-medium text-cyan-200"><?= escapeHtml((string) $color) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </section>
    </main>

    <footer class="border-t border-slate-800 bg-[#0B0F17] px-6 py-8 text-center">
        <p class="font-bold text-cyan-400">ColorOfDay</p>
        <p class="mt-2 text-sm text-slate-500">Your Mood. Your Color. Your Day.</p>
    </footer>
</body>
</html>
