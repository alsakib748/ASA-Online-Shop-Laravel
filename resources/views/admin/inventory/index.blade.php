@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Inventory Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark">Manage Products</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')

            <div class="row">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalProducts }}</h3>
                            <p>Total Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $trackedProducts }}</h3>
                            <p>Tracked Items</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $inStockProducts }}</h3>
                            <p>In Stock</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $lowStockProductsCount }}</h3>
                            <p>Low Stock</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $outOfStockProductsCount }}</h3>
                            <p>Out of Stock</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3>৳{{ number_format((float) $inventoryValue, 2) }}</h3>
                            <p>Inventory Value</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Low Stock Alerts</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-right">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lowStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->title }}</td>
                                            <td class="text-right">
                                                <span class="badge badge-warning">{{ $product->qty ?? 0 }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4">No low stock products
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Inventory List</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Track Qty</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventoryProducts as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->track_qty }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ ($product->qty ?? 0) <= 0 ? 'badge-danger' : (($product->qty ?? 0) <= 10 ? 'badge-warning' : 'badge-success') }}">
                                                    {{ $product->qty ?? 0 }}
                                                </span>
                                            </td>
                                            <td>৳{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                @if ($product->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $inventoryProducts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
