<?php

namespace App\Http\Controllers;

use App\Models\RechargePackage;
use Illuminate\Http\Request;

class RechargePackageController extends Controller
{
    public function index()
    {
        $packages = RechargePackage::all();
        return view('admin', compact('packages')); // View admin hiện tại
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|unique:recharge_packages,amount|min:1000',
            'tokens' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        RechargePackage::create($request->all());

        return redirect()->back()->with('success', 'Gói nạp tiền đã được tạo thành công!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer|unique:recharge_packages,amount,' . $id . '|min:1000',
            'tokens' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $package = RechargePackage::findOrFail($id);
        $package->update($request->all());

        return redirect()->back()->with('success', 'Gói nạp tiền đã được cập nhật!');
    }

    public function destroy($id)
    {
        $package = RechargePackage::findOrFail($id);
        $package->delete();

        return redirect()->back()->with('success', 'Gói nạp tiền đã được xóa!');
    }
}