<?php

namespace App\Http\Controllers;

use App\Models\PointMap;
use Illuminate\Http\Request;

class PointMapController extends Controller
{
    /**
     * Show the map view.
     */
    public function showMap()
    {
        return view('map');
    }

    /**
     * Get all map points as JSON.
     */
    public function getAll()
    {
        $points = PointMap::all();
        return response()->json($points);
    }

    /**
     * Store a new map point.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $point = PointMap::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Titik berhasil disimpan',
            'pointmap_id' => $point->pointmap_id
        ]);
    }

    /**
     * Update an existing map point.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'pointmap_id' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $point = PointMap::findOrFail($request->pointmap_id);
        $point->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Titik berhasil diperbarui'
        ]);
    }

    public function updateDetails(Request $request)
    {
        $validated = $request->validate([
            'pointmap_id' => 'required|integer',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $point = PointMap::findOrFail($request->pointmap_id);
        $point->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail titik berhasil diperbarui'
        ]);
    }

    /**
     * Delete a map point.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'pointmap_id' => 'required|integer',
        ]);

        $point = PointMap::findOrFail($request->pointmap_id);
        $point->delete();

        return response()->json([
            'success' => true,
            'message' => 'Titik berhasil dihapus'
        ]);
    }
}