<?php
declare(strict_types=1);

class Palette
{
    private string $id;
    private string $namaPalette;
    private string $kategori;
    /** @var string[] */
    private array $arrayWarna;
    private string $tanggal;

    /**
     * @param string[] $arrayWarna
     */
    public function __construct(
        string $id,
        string $namaPalette,
        string $kategori,
        array $arrayWarna,
        string $tanggal
    ) {
        $this->id = $id;
        $this->namaPalette = $namaPalette;
        $this->kategori = $kategori;
        $this->arrayWarna = $arrayWarna;
        $this->tanggal = $tanggal;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNamaPalette(): string
    {
        return $this->namaPalette;
    }

    public function getKategori(): string
    {
        return $this->kategori;
    }

    /** @return string[] */
    public function getArrayWarna(): array
    {
        return $this->arrayWarna;
    }

    public function getTanggal(): string
    {
        return $this->tanggal;
    }
}
