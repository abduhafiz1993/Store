@extends('layouts.app')

@section('title', 'Product Details - ' . $category->name)

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Category Details</h4>
                </div>

                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $category->id }}</dd>

                        <dt class="col-sm-4">Name</dt>
                        <dd class="col-sm-8">{{ $category->name }}</dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">{{ $category->description ?? '—' }}</dd>


                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $category->created_at->format('d M Y H:i') }}</dd>

                        <dt class="col-sm-4">Last Updated</dt>
                        <dd class="col-sm-8">{{ $category->updated_at->format('d M Y H:i') }}</dd>
                    </dl>

                    <div class="mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to List</a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit Product</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection