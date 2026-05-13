@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Analytics Dashboard</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">Main Dashboard</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')

            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $totalOrders }}</h3>
                            <p>Total Orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>${{ number_format($totalRevenue, 2) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>${{ number_format($averageOrderValue, 2) }}</h3>
                            <p>Average Order Value</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $paidOrders }}</h3>
                            <p>Paid Orders</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Monthly Sales</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $maxMonthlySales = collect($monthlySales)->max('value') ?: 1;
                            @endphp
                            @foreach ($monthlySales as $monthlySale)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ $monthlySale['label'] }}</span>
                                        <span>${{ number_format($monthlySale['value'], 2) }}</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ min(100, ($monthlySale['value'] / $maxMonthlySales) * 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Order Status Breakdown</h3>
                        </div>
                        <div class="card-body">
                            @forelse ($orderStatusBreakdown as $statusRow)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $statusRow->status ?? 'unknown' }}</span>
                                    <strong>{{ $statusRow->total }}</strong>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No order status data available.</p>
                            @endforelse

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Pending Payments</span>
                                <strong>{{ $pendingOrders }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Cancelled Orders</span>
                                <strong>{{ $cancelledOrders }}</strong>
                            </div>

                            @if (!empty($bestMonth))
                                <div class="alert alert-light border mt-3 mb-0">
                                    Best month: <strong>{{ $bestMonth['label'] }}</strong> with
                                    <strong>${{ number_format($bestMonth['value'], 2) }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top Selling Products</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-right">Qty</th>
                                        <th class="text-right">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topProducts as $product)
                                        <tr>
                                            <td>{{ $product->title }}</td>
                                            <td class="text-right">{{ $product->total_qty }}</td>
                                            <td class="text-right">${{ number_format($product->revenue, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">No product sales data
                                                yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Orders</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->customer_name ?? 'Guest' }}</td>
                                            <td class="text-right">${{ number_format($order->grand_total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">No orders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
