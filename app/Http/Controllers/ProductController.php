<?php

namespace App\Http\Controllers;

use App\Models\Product;
// use Illuminate\Auth\Middleware\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;
use App\Exceptions\ProductNotBelongsToUser;
use App\Http\Requests\UpdateProductRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;

class ProductController extends Controller
{

    public function __construct() {

        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return ProductCollection::collection(Product::all());
        // return new ProductCollection(Product::all());
        return ProductCollection::collection(Product::paginate(20));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = new Product;
        $product->name = $request->name;
        $product->details = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->discount = $request->discount;
        $product->save();

        // return response([
        //     "data" => new ProductResource($product)
        // ], 201);

        // Or Just use symfony HTTP Status

        return response([
            "data" => new ProductResource($product)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {

        $this->ProductUserCheck($product);

        $request['details'] = $request->description;
        unset($request['description']);

        $product->update($request->all());

        return response([
            "data" => new ProductResource($product)
        ], Response::HTTP_CREATED);

        // return $request->all();
        // return $request->product->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        $this->ProductUserCheck($product);

        $product->delete();
        return response(null, 204);
    }

    public function ProductUserCheck($product)
    {
        if (Auth::id() !== $product->user_id) {
            throw new ProductNotBelongsToUser;
        }
    }
}
