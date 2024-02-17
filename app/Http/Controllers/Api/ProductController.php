<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepository;

    /**
     * The function is a constructor that takes a ProductRepositoryInterface object as a parameter and
     * assigns it to the  property.
     * 
     * @param ProductRepositoryInterface productRepository The  parameter is an
     * instance of a class that implements the ProductRepositoryInterface. It is used to interact with
     * the database or any other data source to retrieve and manipulate product data.
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * The index function retrieves all products from the product repository and passes them to the
     * view for display.
     * 
     * @return a view called 'products.index' and passing the variable 'products' to the view.
     */
    public function index()
    {
        $products = $this->productRepository->all();
        return response()->json([
            'data' => ProductsResource::collection($products)
        ], 200);
    }


    public function store(ProductRequest $request)
    {
        // Handle image upload
        $data = $request->all();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }
        $product = $this->productRepository->create($data);
        return response()->json([
            'data' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = $this->productRepository->find($id);
        if ($product) {
            return response()->json([
                'msg' => 'Product find success',
                'data' => new ProductResource($product)
            ], 200);
        }
        return response()->json([
            'data' => '',
            'msg' => 'Product not found!'
        ], 204);
    }



    public function update(ProductRequest $request, $id)
    {
        try {
            $data = $request->all();
            return $data;
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $data['image'] = $imagePath;
            }
            $product = $this->productRepository->update($id, $data);
            return response()->json([
                'msg' => 'Product updated success',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = $this->productRepository->delete($id);
        return response()->json([
            'msg' => 'Product deleted success',
            'data' => $product
        ], 201);
    }
}
