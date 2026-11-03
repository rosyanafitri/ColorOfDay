<?php
declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Palette.php';

class PaletteManager
{
    private string $filePath;

    public function __construct()
    {
        $this->filePath = dirname(__DIR__, 2)
            . DIRECTORY_SEPARATOR . 'storage'
            . DIRECTORY_SEPARATOR . 'palettes.json';
    }

    /**
     * Memvalidasi lalu menyimpan satu palette secara aman ke file JSON.
     *
     * @param string[] $arrayWarna
     */
    public function tambahPalette(string $nama, string $kategori, array $arrayWarna): void
    {
        $nama = trim($nama);
        $kategori = trim($kategori);
        $arrayWarna = array_values($arrayWarna);

        if ($nama === '' || $kategori === '') {
            throw new InvalidArgumentException('Nama palette dan kategori wajib diisi.');
        }
        $nameLength = preg_match_all('/./us', $nama);
        $categoryLength = preg_match_all('/./us', $kategori);
        if (
            $nameLength === false || $nameLength > 80
            || $categoryLength === false || $categoryLength > 40
        ) {
            throw new InvalidArgumentException('Nama palette maksimal 80 karakter dan kategori maksimal 40 karakter.');
        }
        if (count($arrayWarna) < 3 || count($arrayWarna) > 4) {
            throw new InvalidArgumentException('Palette harus memiliki 3 atau 4 warna.');
        }
        foreach ($arrayWarna as $warna) {
            if (!is_string($warna) || preg_match('/^#[0-9A-Fa-f]{6}$/D', $warna) !== 1) {
                throw new InvalidArgumentException('Setiap warna harus berupa kode HEX, misalnya #22D3EE.');
            }
        }

        $palette = new Palette(
            bin2hex(random_bytes(8)),
            $nama,
            $kategori,
            array_map('strtoupper', $arrayWarna),
            date(DATE_ATOM)
        );

        $handle = fopen($this->filePath, 'c+');
        if ($handle === false) {
            throw new RuntimeException('File penyimpanan palette tidak dapat dibuka.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException('File palette tidak dapat dikunci untuk ditulis.');
            }

            rewind($handle);
            $contents = stream_get_contents($handle);
            if ($contents === false) {
                throw new RuntimeException('File palette tidak dapat dibaca.');
            }

            $palettes = $this->decodePalettes($contents);
            $palettes[] = $this->paletteToArray($palette);
            $json = json_encode($palettes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

            rewind($handle);
            if (!ftruncate($handle, 0) || fwrite($handle, $json) !== strlen($json) || !fflush($handle)) {
                throw new RuntimeException('Data palette tidak dapat disimpan sepenuhnya.');
            }
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    /**
     * @return Palette[]
     */
    public function ambilSemuaPalette(): array
    {
        $handle = fopen($this->filePath, 'rb');
        if ($handle === false) {
            throw new RuntimeException('File penyimpanan palette tidak dapat dibuka.');
        }

        try {
            if (!flock($handle, LOCK_SH)) {
                throw new RuntimeException('File palette tidak dapat dikunci untuk dibaca.');
            }

            $contents = stream_get_contents($handle);
            if ($contents === false) {
                throw new RuntimeException('File palette tidak dapat dibaca.');
            }

            $palettes = array_map(
                fn (array $data): Palette => new Palette(
                    $data['id'],
                    $data['namaPalette'],
                    $data['kategori'],
                    $data['arrayWarna'],
                    $data['tanggal']
                ),
                $this->decodePalettes($contents)
            );
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }

        usort(
            $palettes,
            static fn (Palette $first, Palette $second): int =>
                strtotime($second->getTanggal()) <=> strtotime($first->getTanggal())
        );

        return $palettes;
    }

    /**
     * @return array<array{id: string, namaPalette: string, kategori: string, arrayWarna: string[], tanggal: string}>
     */
    private function decodePalettes(string $contents): array
    {
        if (trim($contents) === '') {
            return [];
        }

        try {
            $palettes = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Data palette bukan JSON yang valid.', 0, $exception);
        }
        if (!is_array($palettes) || !array_is_list($palettes)) {
            throw new RuntimeException('Format data palette tidak valid.');
        }

        foreach ($palettes as $data) {
            if (
                !is_array($data)
                || !is_string($data['id'] ?? null)
                || !is_string($data['namaPalette'] ?? null)
                || !is_string($data['kategori'] ?? null)
                || !is_array($data['arrayWarna'] ?? null)
                || !array_is_list($data['arrayWarna'])
                || !is_string($data['tanggal'] ?? null)
                || strtotime($data['tanggal']) === false
            ) {
                throw new RuntimeException('Format palette di dalam penyimpanan tidak valid.');
            }
            foreach ($data['arrayWarna'] as $warna) {
                if (!is_string($warna) || preg_match('/^#[0-9A-F]{6}$/D', $warna) !== 1) {
                    throw new RuntimeException('Format kode warna di dalam penyimpanan tidak valid.');
                }
            }
        }

        return $palettes;
    }

    /**
     * @return array{id: string, namaPalette: string, kategori: string, arrayWarna: string[], tanggal: string}
     */
    private function paletteToArray(Palette $palette): array
    {
        return [
            'id' => $palette->getId(),
            'namaPalette' => $palette->getNamaPalette(),
            'kategori' => $palette->getKategori(),
            'arrayWarna' => $palette->getArrayWarna(),
            'tanggal' => $palette->getTanggal(),
        ];
    }
}
