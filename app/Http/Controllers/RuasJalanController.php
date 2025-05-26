<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RuasJalanController extends Controller
{
    private $baseUrl = 'https://gisapis.manpits.xyz/api';

    public function ruasJalan()
    {
        if (!Session::has('user')) {
            return redirect()->route('loginruasjalan');
        }
        
        return view('ruasjalan');
    }

    public function getRuasJalanData()
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $token = Session::get('token');
            $user = Session::get('user');
            
            Log::info('Attempting to fetch ruas jalan data', [
                'token_exists' => !empty($token),
                'token_preview' => $token ? substr($token, 0, 20) . '...' : 'null',
                'token_length' => strlen($token ?? ''),
                'user_id' => $user['id'] ?? null,
                'user_email' => $user['email'] ?? null
            ]);

            if (empty($token)) {
                return response()->json([
                    'error' => 'Token not found in session',
                    'message' => 'Silakan login ulang'
                ], 401);
            }

            Log::info('Testing API endpoint availability...');
            
            $testResponse = Http::timeout(10)->get($this->baseUrl . '/ruasjalan');
            Log::info('Test response:', [
                'status' => $testResponse->status(),
                'body' => $testResponse->body()
            ]);

            // Method 1: Standard Bearer Token
            Log::info('Trying Method 1: Bearer Token');
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/ruasjalan');

            Log::info('Method 1 Response:', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $this->processSuccessfulResponse($response, 'Method 1 (Bearer)');
            }

            // Method 2: Token as Authorization header directly
            Log::info('Trying Method 2: Direct Token');
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/ruasjalan');

            Log::info('Method 2 Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $this->processSuccessfulResponse($response, 'Method 2 (Direct)');
            }

            // Method 3: Custom header
            Log::info('Trying Method 3: X-API-TOKEN');
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-TOKEN' => $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/ruasjalan');

            Log::info('Method 3 Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $this->processSuccessfulResponse($response, 'Method 3 (X-API-TOKEN)');
            }

            // Method 4: Query parameter
            Log::info('Trying Method 4: Query Parameter');
            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/ruasjalan', [
                    'token' => $token,
                ]);

            Log::info('Method 4 Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $this->processSuccessfulResponse($response, 'Method 4 (Query)');
            }

            // Method 5: POST with token in body
            Log::info('Trying Method 5: POST with token');
            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/ruasjalan', [
                    'token' => $token,
                ]);

            Log::info('Method 5 Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $this->processSuccessfulResponse($response, 'Method 5 (POST)');
            }

            // Method 6: Try with api_token
            Log::info('Trying Method 6: api_token parameter');
            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl . '/ruasjalan', [
                    'api_token' => $token,
                ]);

            Log::info('Method 6 Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $this->processSuccessfulResponse($response, 'Method 6 (api_token)');
            }

            $errorData = $response->json();
            
            Log::error('All authentication methods failed:', [
                'final_status' => $response->status(),
                'final_response' => $errorData,
                'token_preview' => substr($token, 0, 20) . '...',
                'url' => $this->baseUrl . '/ruasjalan'
            ]);
            
            if ($response->status() === 401) {
                return response()->json([
                    'error' => 'Token expired atau tidak valid',
                    'message' => 'Silakan login ulang',
                    'redirect' => route('loginruasjalan')
                ], 401);
            }
            
            return response()->json([
                'error' => 'Gagal mengambil data ruas jalan',
                'message' => $errorData['message'] ?? 'API Error: ' . $response->status(),
                'status' => $response->status(),
                'details' => $errorData,
                'debug_info' => [
                    'token_length' => strlen($token),
                    'api_url' => $this->baseUrl . '/ruasjalan',
                    'methods_tried' => 6
                ]
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('Ruas Jalan Exception:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Terjadi kesalahan koneksi',
                'message' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    public function addRuasJalan(Request $request)
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $token = Session::get('token');

            if (empty($token)) {
                return response()->json([
                    'error' => 'Token not found in session',
                    'message' => 'Silakan login ulang'
                ], 401);
            }

            $data = [
                'paths' => $request->paths ?? '',
                'desa_id' => $request->desa_id,
                'kode_ruas' => $request->kode_ruas,
                'nama_ruas' => $request->nama_ruas,
                'panjang' => $request->panjang,
                'lebar' => $request->lebar,
                'eksisting_id' => $request->eksisting_id,
                'kondisi_id' => $request->kondisi_id,
                'jenisjalan_id' => $request->jenisjalan_id,
                'keterangan' => $request->keterangan ?? ''
            ];

            Log::info('Adding new ruas jalan:', $data);

            $response = $this->makeApiRequest('POST', '/ruasjalan', $data, $token);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruas jalan berhasil ditambahkan',
                    'data' => $response->json()
                ]);
            }

            $errorData = $response->json();
            Log::error('Failed to add ruas jalan:', [
                'status' => $response->status(),
                'response' => $errorData
            ]);

            return response()->json([
                'error' => 'Gagal menambahkan ruas jalan',
                'message' => $errorData['message'] ?? 'API Error: ' . $response->status(),
                'details' => $errorData
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Add Ruas Jalan Exception:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan saat menambahkan ruas jalan',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteRuasJalan($id, Request $request)
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $token = Session::get('token');

            if (empty($token)) {
                return response()->json([
                    'error' => 'Token not found in session',
                    'message' => 'Silakan login ulang'
                ], 401);
            }

            $data = [
                'paths' => $request->paths ?? '',
                'desa_id' => $request->desa_id ?? '',
                'kode_ruas' => $request->kode_ruas ?? '',
                'nama_ruas' => $request->nama_ruas ?? '',
                'panjang' => $request->panjang ?? '',
                'lebar' => $request->lebar ?? '',
                'eksisting_id' => $request->eksisting_id ?? '',
                'kondisi_id' => $request->kondisi_id ?? '',
                'jenisjalan_id' => $request->jenisjalan_id ?? '',
                'keterangan' => $request->keterangan ?? ''
            ];

            Log::info('Deleting ruas jalan:', ['id' => $id, 'data' => $data]);

            $response = $this->makeApiRequest('DELETE', '/ruasjalan/' . $id, $data, $token);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruas jalan berhasil dihapus',
                    'data' => $response->json()
                ]);
            }

            $errorData = $response->json();
            Log::error('Failed to delete ruas jalan:', [
                'status' => $response->status(),
                'response' => $errorData
            ]);

            return response()->json([
                'error' => 'Gagal menghapus ruas jalan',
                'message' => $errorData['message'] ?? 'API Error: ' . $response->status(),
                'details' => $errorData
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Delete Ruas Jalan Exception:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus ruas jalan',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProvinsi($id = null)
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = Session::get('token');
        $url = $id ? '/provinsi/' . $id : '/provinsi';
        
        try {
            $response = $this->makeApiRequest('GET', $url, [], $token);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch provinsi data'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getKabupaten($id = null)
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = Session::get('token');
        $url = $id ? '/kabupaten/' . $id : '/kabupaten';
        
        try {
            $response = $this->makeApiRequest('GET', $url, [], $token);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch kabupaten data'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getKecamatan($id = null)
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = Session::get('token');
        $url = $id ? '/kecamatan/' . $id : '/kecamatan';
        
        try {
            $response = $this->makeApiRequest('GET', $url, [], $token);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch kecamatan data'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDesa($id = null)
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = Session::get('token');
        $url = $id ? '/desa/' . $id : '/desa';
        
        try {
            $response = $this->makeApiRequest('GET', $url, [], $token);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch desa data'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMasterData($type)
    {
        if (!Session::has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = Session::get('token');
        $allowedTypes = ['meksisting', 'mjenisjalan', 'mkondisi'];
        
        if (!in_array($type, $allowedTypes)) {
            return response()->json(['error' => 'Invalid master data type'], 400);
        }

        try {
            $response = $this->makeApiRequest('GET', '/' . $type, [], $token);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch master data'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function makeApiRequest($method, $endpoint, $data = [], $token = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $authMethods = [
            ['Authorization' => 'Bearer ' . $token],
            ['Authorization' => $token],
            ['X-API-TOKEN' => $token]
        ];

        foreach ($authMethods as $headers) {
            try {
                $headers['Accept'] = 'application/json';
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';

                $response = Http::timeout(30)->withHeaders($headers);

                if ($method === 'GET') {
                    $response = $response->get($url, $data);
                } elseif ($method === 'POST') {
                    $response = $response->asForm()->post($url, $data);
                } elseif ($method === 'DELETE') {
                    $response = $response->asForm()->delete($url, $data);
                }

                if ($response->successful()) {
                    return $response;
                }
            } catch (\Exception $e) {
                Log::error('API Request failed:', [
                    'method' => $method,
                    'url' => $url,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        return $response ?? Http::timeout(30)->get($url);
    }

    private function processSuccessfulResponse($response, $method)
    {
        $data = $response->json();
        
        Log::info($method . ' - API Success:', [
            'data_keys' => array_keys($data),
            'data_count' => is_array($data) ? count($data) : 'not_array'
        ]);
        
        return response()->json($data);
    }
}