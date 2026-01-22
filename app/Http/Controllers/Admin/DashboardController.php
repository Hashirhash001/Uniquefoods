<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            'orders'   => Order::count(),
            'revenue'  => Order::sum('total'),
            'products' => Product::count(),
            'users'    => User::count(),
        ]);
    }
}
