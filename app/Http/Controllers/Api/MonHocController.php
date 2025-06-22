<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonHoc;

class MonHocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(MonHoc::all(),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_mon' => 'required|string|unique:mon_hoc,ma_mon',
            'ten_mon' => 'required|string|max:100',
            'ma_nganh' => 'required|integer',
            'so_tin_chi' => 'nullable|integer',
            'so_tiet' => 'nullable|integer',
        ]);

        $monHoc = MonHoc::create($validated);

        return response()->json($monHoc, 201);


    }

    /**
     * Display the specified resource.
     */
    public function show($ma_mon)
    {
        $monHoc = MonHoc::find($ma_mon);
        if(!$monHoc) return response()->json(['message'=>'khong tìm thấy môn học'], 404);
        return response()->json($monHoc, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ma_mon)
    {
        $monHoc = MonHoc::find($ma_mon);
        if (!$monHoc) return response()->json(['message' => 'Không tìm thấy'], 404);

        $validated = $request->validate([
            'ten_mon' => 'required|string|max:100',
            'ma_nganh' => 'required|integer',
            'so_tin_chi' => 'nullable|integer',
            'so_tiet' => 'nullable|integer',
        ]);

        $monHoc->update($validated);
        return response()->json($monHoc, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ma_mon)
    {
        $monHoc = MonHoc::find($ma_mon);
        if (!$monHoc) return response()->json(['message' => 'Không tìm thấy'], 404);

        $monHoc->delete();
        return response()->json(['message' => 'Đã xóa thành công'], 200);
    }
}
