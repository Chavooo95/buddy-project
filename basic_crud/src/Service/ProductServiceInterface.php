<?php
declare(strict_types=1);

namespace App\Service;

interface ProductServiceInterface
{
    public function findAll(): array;
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id): bool;
}