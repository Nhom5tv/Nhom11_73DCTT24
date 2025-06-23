<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Khoa;

class KhoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Khoa::all());
    }

    public function timkiem(Request $request)
    {
        $keyword = $request->query('q');
        $ketQua = Khoa::where('ten_khoa', 'like', "%$keyword%")->get();
        return response()->json($ketQua);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_khoa' => 'required|max:100',
            'lien_he' => 'nullable|max:50',
            'ngay_thanh_lap' => 'nullable|date',
            'tien_moi_tin_chi' => 'nullable|numeric'
        ]);

        $khoa = Khoa::create($validated);
        return response()->json($khoa, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $khoa = Khoa::findOrFail($id);
        return response()->json($khoa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $khoa = Khoa::findOrFail($id);

        $validated = $request->validate([
            'ten_khoa' => 'required|max:100',
            'lien_he' => 'nullable|max:50',
            'ngay_thanh_lap' => 'nullable|date',
            'tien_moi_tin_chi' => 'nullable|numeric'
        ]);

        $khoa->update($validated);
        return response()->json($khoa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $khoa = Khoa::findOrFail($id);
        $khoa->delete();

        return response()->json(['message' => 'Đã xoá khoa thành công']);
    }
}
