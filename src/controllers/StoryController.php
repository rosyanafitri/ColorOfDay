<?php
declare(strict_types=1);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'StoryModel.php';

class StoryController
{
    private const COLOR_OPTIONS = [
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

    private StoryModel $model;
    private string $formError = '';
    private string $formTitle = '';
    private string $formStory = '';

    public function __construct(?StoryModel $model = null)
    {
        $this->model = $model ?? new StoryModel();
    }

    public function index(): void
    {
        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $saved = isset($_GET['saved']);

        try {
            $stories = $this->model->getAll();
        } catch (RuntimeException $exception) {
            http_response_code(500);
            $stories = [];
            if ($this->formError === '') {
                $this->formError = $exception->getMessage();
            }
        }

        $formError = $this->formError;
        $formTitle = $this->formTitle;
        $formStory = $this->formStory;
        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'story-view.php';
    }

    public function store(): void
    {
        $ajaxRequest = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
        $rawTitle = $_POST['judul_cerita'] ?? '';
        $rawStory = $_POST['isi_cerita'] ?? '';
        $this->formTitle = is_string($rawTitle) ? trim($rawTitle) : '';
        $this->formStory = is_string($rawStory) ? trim($rawStory) : '';

        $storyLength = preg_match_all('/./us', $this->formStory);
        $titleLength = preg_match_all('/./us', $this->formTitle);
        if ($this->formStory === '' || $storyLength === false || $storyLength > 500) {
            $this->formError = 'Cerita wajib diisi dan maksimal 500 karakter.';
        } elseif ($titleLength === false || $titleLength > 100) {
            $this->formError = 'Judul cerita maksimal 100 karakter.';
        }

        if ($this->formError !== '') {
            if ($ajaxRequest) {
                $this->sendJson(400, ['success' => false, 'message' => $this->formError]);
            }
            $this->index();
            return;
        }

        $rawColors = $_POST['rekomendasi_warna'] ?? [];
        $postedColors = is_array($rawColors) ? $rawColors : [];
        $colors = array_values(array_unique(array_filter(
            $postedColors,
            static fn ($color): bool => is_string($color) && in_array($color, self::COLOR_OPTIONS, true)
        )));
        if (count($colors) < 2) {
            $colors = $this->getSuggestedColors($this->formStory);
        }

        $story = [
            'id' => bin2hex(random_bytes(8)),
            'tanggal' => date(DATE_ATOM),
            'judul_cerita' => $this->formTitle !== '' ? $this->formTitle : $this->getSuggestedTitle($this->formStory),
            'isi_cerita' => $this->formStory,
            'rekomendasi_warna' => $colors,
        ];

        try {
            $this->model->save($story);
        } catch (RuntimeException $exception) {
            $this->formError = $exception->getMessage();
            if ($ajaxRequest) {
                $this->sendJson(500, ['success' => false, 'message' => $this->formError]);
            }
            http_response_code(500);
            $this->index();
            return;
        }

        if ($ajaxRequest) {
            $this->sendJson(200, ['success' => true, 'message' => 'Cerita berhasil disimpan ke riwayat.']);
        }

        header('Location: cerita.php?saved=1#riwayat', true, 303);
        exit;
    }

    private function sendJson(int $status, array $payload): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        exit;
    }

    /** @return string[] */
    private function getSuggestedColors(string $story): array
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

    private function getSuggestedTitle(string $story): string
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
}
