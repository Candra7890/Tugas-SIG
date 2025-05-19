<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shape;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ShapeController extends Controller
{
    public function getShapesJson()
    {
        $shapes = Shape::all();
        return response()->json($shapes);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tipe' => 'required|in:polyline,polygon,circle,rectangle',
                'color' => 'nullable|string|max:7'
            ]);

            $data = [
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'tipe' => $request->tipe,
                'color' => $request->color ?? '#3388ff',
                'created_at' => now(),
                'updated_at' => now()
            ];

            switch ($request->tipe) {
                case 'polyline':
                case 'polygon':
                    $request->validate([
                        'koordinat' => 'required|string'
                    ]);
                    $data['koordinat'] = $request->koordinat;
                    break;

                case 'circle':
                    $request->validate([
                        'center_lat' => 'required|numeric',
                        'center_lng' => 'required|numeric',
                        'radius' => 'required|numeric|min:0'
                    ]);
                    $data['center_lat'] = $request->center_lat;
                    $data['center_lng'] = $request->center_lng;
                    $data['radius'] = $request->radius;
                    break;

                case 'rectangle':
                    $request->validate([
                        'north' => 'required|numeric',
                        'south' => 'required|numeric',
                        'east' => 'required|numeric',
                        'west' => 'required|numeric'
                    ]);
                    $data['north'] = $request->north;
                    $data['south'] = $request->south;
                    $data['east'] = $request->east;
                    $data['west'] = $request->west;
                    break;
            }

            $shapeId = DB::table('tb_shapes')->insertGetId($data);

            return response()->json([
                'success' => true,
                'message' => 'Shape berhasil disimpan',
                'shape_id' => $shapeId,
                'data' => $data
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan shape: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDetails(Request $request)
    {
        try {
            $request->validate([
                'shape_id' => 'required|integer|exists:tb_shapes,shape_id',
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'color' => 'nullable|string|max:7'
            ]);

            $updated = DB::table('tb_shapes')
                ->where('shape_id', $request->shape_id)
                ->update([
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi,
                    'color' => $request->color ?? '#3388ff',
                    'updated_at' => now()
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shape berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Shape tidak ditemukan'
                ], 404);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui shape: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateGeometry(Request $request)
    {
        try {
            $request->validate([
                'shape_id' => 'required|integer|exists:shapes,shape_id',
                'tipe' => 'required|in:polyline,polygon,circle,rectangle'
            ]);

            $updateData = ['updated_at' => now()];

            switch ($request->type) {
                case 'polyline':
                case 'polygon':
                    $request->validate(['koordinat' => 'required|string']);
                    $updateData['koordinat'] = $request->koordinat;
                    $updateData['center_lat'] = null;
                    $updateData['center_lng'] = null;
                    $updateData['radius'] = null;
                    $updateData['north'] = null;
                    $updateData['south'] = null;
                    $updateData['east'] = null;
                    $updateData['west'] = null;
                    break;

                case 'circle':
                    $request->validate([
                        'center_lat' => 'required|numeric',
                        'center_lng' => 'required|numeric',
                        'radius' => 'required|numeric|min:0'
                    ]);
                    $updateData['center_lat'] = $request->center_lat;
                    $updateData['center_lng'] = $request->center_lng;
                    $updateData['radius'] = $request->radius;
                    $updateData['koordinat'] = null;
                    $updateData['north'] = null;
                    $updateData['south'] = null;
                    $updateData['east'] = null;
                    $updateData['west'] = null;
                    break;

                case 'rectangle':
                    $request->validate([
                        'north' => 'required|numeric',
                        'south' => 'required|numeric',
                        'east' => 'required|numeric',
                        'west' => 'required|numeric'
                    ]);
                    $updateData['north'] = $request->north;
                    $updateData['south'] = $request->south;
                    $updateData['east'] = $request->east;
                    $updateData['west'] = $request->west;
                    $updateData['koordinat'] = null;
                    $updateData['center_lat'] = null;
                    $updateData['center_lng'] = null;
                    $updateData['radius'] = null;
                    break;
            }

            $updated = DB::table('tb_shapes')
                ->where('shape_id', $request->shape_id)
                ->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Geometri shape berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Shape tidak ditemukan'
                ], 404);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui geometri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'shape_id' => 'required|integer|exists:tb_shapes,shape_id'
            ]);

            $deleted = DB::table('tb_shapes')
                ->where('shape_id', $request->shape_id)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shape berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Shape tidak ditemukan'
                ], 404);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus shape: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $shape = DB::table('tb_shapes')
                ->where('shape_id', $id)
                ->first();

            if (!$shape) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shape tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $shape
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data shape: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStatistics()
    {
        try {
            $statistics = DB::table('tb_shapes')
                ->select('tipe', DB::raw('count(*) as count'))
                ->groupBy('tipe')
                ->get();

            $total = DB::table('tb_shapes')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'by_type' => $statistics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = DB::table('tb_shapes');

            if ($request->has('nama') && !empty($request->nama)) {
                $query->where('nama', 'LIKE', '%' . $request->nama . '%');
            }

            if ($request->has('tipe') && !empty($request->tipe)) {
                $query->where('tipe', $request->tipe);
            }

            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->where('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
            }

            $shapes = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $shapes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian: ' . $e->getMessage()
            ], 500);
        }
    }
}