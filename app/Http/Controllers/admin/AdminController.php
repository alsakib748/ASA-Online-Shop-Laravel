<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function AdminLogin()
    {
        return view('auth.login');
    }

    public function AdminDashboard()
    {
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'user')->count();

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentData = Carbon::now()->format('Y-m-d');

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentData)
            ->sum('grand_total');

        $lastMonthStartData = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndData = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastMonthStartData)
            ->whereDate('created_at', '<=', $lastMonthEndData)
            ->sum('grand_total');

        $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
            ->whereDate('created_at', '<=', $currentData)
            ->sum('grand_total');

        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();

        foreach ($tempImages as $tempImage) {
            $path = public_path('/temp/' . $tempImage->name);
            $thumbPath = public_path('/temp/thumb/' . $tempImage->name);

            if (File::exists($path)) {
                File::delete($path);
            }

            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            TempImage::where('id', $tempImage->id)->delete();
        }

        return view('admin.dashboard', compact([
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'revenueThisMonth',
            'revenueLastMonth',
            'revenueLastThirtyDays',
            'lastMonthName'
        ]));
    }

    public function inventoryDashboard()
    {
        $totalProducts = Product::count();
        $trackedProducts = Product::where('track_qty', 'Yes')->count();
        $inStockProducts = Product::where('track_qty', 'Yes')->whereNotNull('qty')->where('qty', '>', 0)->count();
        $lowStockProductsCount = Product::where('track_qty', 'Yes')->whereNotNull('qty')->where('qty', '<=', 10)->count();
        $outOfStockProductsCount = Product::where('track_qty', 'Yes')->whereNotNull('qty')->where('qty', '<=', 0)->count();

        $inventoryValue = Product::where('track_qty', 'Yes')
            ->selectRaw('COALESCE(SUM(COALESCE(qty,0) * price), 0) as inventory_value')
            ->value('inventory_value');

        $lowStockProducts = Product::with('product_images')
            ->where('track_qty', 'Yes')
            ->whereNotNull('qty')
            ->where('qty', '<=', 10)
            ->orderBy('qty', 'asc')
            ->orderBy('title', 'asc')
            ->get();

        $inventoryProducts = Product::with('product_images')
            ->latest('id')
            ->paginate(12);

        return view('admin.inventory.index', compact([
            'totalProducts',
            'trackedProducts',
            'inStockProducts',
            'lowStockProductsCount',
            'outOfStockProductsCount',
            'inventoryValue',
            'lowStockProducts',
            'inventoryProducts'
        ]));
    }

    public function analyticsDashboard()
    {
        $currentYear = Carbon::now()->year;

        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');
        $averageOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $pendingOrders = Order::where('payment_status', 'not paid')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $recentOrders = Order::select('orders.*', 'users.name as customer_name')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->latest('orders.created_at')
            ->limit(8)
            ->get();

        $topProducts = OrderItem::select('products.id', 'products.title', DB::raw('SUM(orders_items.qty) as total_qty'), DB::raw('SUM(orders_items.total) as revenue'))
            ->join('products', 'products.id', '=', 'orders_items.product_id')
            ->groupBy('products.id', 'products.title')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $orderStatusBreakdown = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $monthlySales = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlySales[] = [
                'label' => Carbon::create()->month($month)->format('M'),
                'value' => Order::where('status', '!=', 'cancelled')
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->sum('grand_total'),
            ];
        }

        $bestMonth = collect($monthlySales)->sortByDesc('value')->first();

        return view('admin.analytics.index', compact([
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'paidOrders',
            'pendingOrders',
            'cancelledOrders',
            'recentOrders',
            'topProducts',
            'orderStatusBreakdown',
            'monthlySales',
            'bestMonth'
        ]));
    }

    public function AdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
