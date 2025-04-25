<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $classifications = Classification::where('user_id', Auth::id())->get();
        // Lấy danh sách người dùng có rating > 0
        $allUserRatings = User::where('rating', '>', 0)
            ->select('name', 'rating', 'feedback')
            ->orderBy('rating', 'desc')
            ->get();

        return view('dashboard', compact('classifications', 'allUserRatings'));
    }
}