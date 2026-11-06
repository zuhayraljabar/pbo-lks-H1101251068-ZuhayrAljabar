<?php

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use Exception;

/**
 * BookService — logika bisnis dan validasi untuk buku.
 */
class BookService
{
    public function __construct(private BookRepositoryInterface $repo) {}

    /** @return array[] */
    public function list(): array
    {
        return array_map(fn(Book $b) => $this->toArray($b), $this->repo->findAll());
    }

    public function create(array $data): array
    {
        $this->validate($data);

        $book = new Book(
            id: 0,
            judul: trim($data['judul']),
            penulis: trim($data['penulis']),
            tahun: (int) $data['tahun'],
            stok: (int) ($data['stok'] ?? 0)
        );

        $id = $this->repo->save($book);
        $saved = $this->repo->findById($id);

        if ($saved === null) {
            throw new Exception('Buku gagal disimpan.');
        }

        return $this->toArray($saved);
    }

    private function validate(array $data): void
    {
        if (empty(trim($data['judul'] ?? ''))) {
            throw new Exception('Judul wajib diisi.');
        }
        if (empty(trim($data['penulis'] ?? ''))) {
            throw new Exception('Penulis wajib diisi.');
        }
        $tahun = (int) ($data['tahun'] ?? 0);
        if ($tahun < 1000 || $tahun > (int) date('Y') + 1) {
            throw new Exception('Tahun tidak valid.');
        }
        if ((int) ($data['stok'] ?? 0) < 0) {
            throw new Exception('Stok tidak boleh negatif.');
        }
    }

    private function toArray(Book $b): array
    {
        return [
            'id'      => $b->getId(),
            'judul'   => $b->getJudul(),
            'penulis' => $b->getPenulis(),
            'tahun'   => $b->getTahun(),
            'stok'    => $b->getStok(),
        ];
    }
}