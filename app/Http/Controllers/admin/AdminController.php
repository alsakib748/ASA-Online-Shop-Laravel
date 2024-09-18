<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    //
    public function AdminLogin()
    {
        return view('auth.login');
    }


    public function AdminDashboard()
    {

        $totalOrders = Order::where('status', '!=', 'cancelled')->count();

        $totalProducts = Product::count();

        $totalCustomers = User::where('role', 'user')->count();

        // Total revenue

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        // This month revenue

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentData = Carbon::now()->format('Y-m-d');

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentData)
            ->sum('grand_total');

        // Last month revenue

        $lastMonthStartData = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');

        $lastMonthEndData = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastMonthStartData)
            ->whereDate('created_at', '<=', $lastMonthEndData)
            ->sum('grand_total');

        // Last 30 days Sale

        $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
            ->whereDate('created_at', '<=', $currentData)
            ->sum('grand_total');

        // todo: Delete temp Images 

        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');

        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();

        foreach ($tempImages as $tempImage) {

            $path = public_path('/temp/' . $tempImage->name);

            $thumbPath = public_path('/temp/thumb/' . $tempImage->name);

            // todo: Delete Main Image

            if (File::exists($path)) {
                File::delete($path);
            }

            // todo: Delete Thumb Image
            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            // todo: Delete on database
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

    public function AdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


}