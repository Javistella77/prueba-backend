<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(
            Product::with('currency')->latest()->paginate(15)
        );
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return new ProductResource($product->load('currency'));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('currency', 'prices.currency'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return new ProductResource($product->load('currency'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}