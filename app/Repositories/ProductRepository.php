<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAll($page = 1, $perPage = 10)
    {
        return Product::with('facility')
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }
}
