<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Lấy user đăng nhập hiện tại (admin)
        $admin = auth()->user();

        // Truy vấn dữ liệu user đã đăng ký theo ngày, trừ admin
        $registrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('id', '!=', $admin->id)
            ->groupBy('date')
            ->get();

        // Tạo mảng dữ liệu cho Chart.js
        $labels = $registrations->pluck('date');
        $data = $registrations->pluck('count');

        return view('admin.admin', compact('labels', 'data'));
    }

}
