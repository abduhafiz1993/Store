@extends('layouts.app')

@section('title', 'Product Details - ' . $product->name)

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Product Details</h4>
                </div>

                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $product->id }}</dd>

                        <dt class="col-sm-4">Name</dt>
                        <dd class="col-sm-8">{{ $product->name }}</dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">{{ $product->description ?? '—' }}</dd>

                        <dt class="col-sm-4">Price</dt>
                        <dd class="col-sm-8">ETB {{ number_format($product->price, 2) }}</dd>

                        <dt class="col-sm-4">Stock Quantity</dt>
                        <dd class="col-sm-8">{{ $product->stock_quantity }}</dd>

                        <dt class="col-sm-4">SKU</dt>
                        <dd class="col-sm-8">{{ $product->sku ?? '—' }}</dd>

                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $product->created_at->format('d M Y H:i') }}</dd>

                        <dt class="col-sm-4">Last Updated</dt>
                        <dd class="col-sm-8">{{ $product->updated_at->format('d M Y H:i') }}</dd>
                    </dl>

                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit Product</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection