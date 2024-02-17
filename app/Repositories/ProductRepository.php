<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::latest()->get();
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $attributes)
    {
        return Product::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $product = Product::findOrFail($id);
        $imagePath = asset('storage/' . $product->image);
        if ($product->image && file_exists($imagePath)) {
            // Delete the image file from storage
            Storage::disk('public')->delete($product->image);
        }
        $product->update($attributes);
        return $product;
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }


}
