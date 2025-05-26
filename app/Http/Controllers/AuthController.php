<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $baseUrl = 'https://gisapis.manpits.xyz/api';

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $request->username, 'email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Username, Email atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showLoginruasjalan()
    {
        return view('auth.loginruasjalan');
    }

    public function showRegisterruasjalan()
    {
        return view('auth.registerruasjalan');
    }

    public function registerruasjalan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            Log::info('Registration attempt:', [
                'name' => $request->name,
                'email' => $request->email,
                'url' => $this->baseUrl . '/register'
            ]);

            $response = Http::timeout(30)->asForm()->post($this->baseUrl . '/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            Log::info('Registration response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->route('loginruasjalan')->with('success', 'Registrasi berhasil! Silakan login.');
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Registrasi gagal.';
                
                Log::error('Registration failed:', [
                    'status' => $response->status(),
                    'error' => $errorData
                ]);
                
                return back()->withErrors(['error' => $errorMessage])->withInput();
            }
            
        } catch (\Exception $e) {
            Log::error('Registration exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan koneksi: ' . $e->getMessage()])->withInput();
        }
    }

    public function loginruasjalan(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            Log::info('Login attempt:', [
                'email' => $request->email,
                'url' => $this->baseUrl . '/login'
            ]);

            $loginData = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            // Method 1: Form data
            $response = Http::timeout(30)
                ->asForm()
                ->post($this->baseUrl . '/login', $loginData);

            Log::info('Login response (Form):', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                Log::info('Trying JSON login...');
                
                $response = Http::timeout(30)
                    ->acceptJson()
                    ->asJson()
                    ->post($this->baseUrl . '/login', $loginData);
                
                Log::info('Login response (JSON):', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body()
                ]);
            }

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Login successful, parsing response:', $data);
                
                $token = $this->extractToken($data);
                $user = $this->extractUser($data);
                
                if ($token) {
                    Session::put('user', $user ?? []);
                    Session::put('token', $token);
                    
                    Log::info('Login Success:', [
                        'token_preview' => substr($token, 0, 20) . '...',
                        'token_length' => strlen($token),
                        'user_id' => $user['id'] ?? null,
                        'user_email' => $user['email'] ?? null
                    ]);
                    
                    return redirect()->route('dashboardruasjalan')->with('success', 'Login berhasil!');
                } else {
                    Log::error('Token not found in login response:', $data);
                    return back()->withErrors(['error' => 'Token tidak ditemukan dalam response. Response: ' . json_encode($data)])->withInput();
                }
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Login gagal.';
                
                Log::error('Login Failed:', [
                    'status' => $response->status(),
                    'response' => $errorData
                ]);
                
                return back()->withErrors(['error' => $errorMessage . ' (Status: ' . $response->status() . ')'])->withInput();
            }
            
        } catch (\Exception $e) {
            Log::error('Login Exception:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan koneksi: ' . $e->getMessage()])->withInput();
        }
    }

    private function extractToken($data)
    {
        $tokenKeys = [
            'token',
            'access_token',
            'bearer_token',
            'auth_token',
            'data.token',
            'data.access_token',
            'result.token',
            'response.token',
            'meta.token',
            'meta.access_token',
            'meta.auth_token'
        ];

        foreach ($tokenKeys as $key) {
            $token = data_get($data, $key);
            if ($token && is_string($token)) {
                Log::info('Token found at key: ' . $key, ['token_preview' => substr($token, 0, 20) . '...']);
                return $token;
            }
        }

        Log::error('Token not found in response structure:', [
            'response_keys' => array_keys($data),
            'full_response' => $data
        ]);

        return null;
    }

    private function extractUser($data)
    {
        $userKeys = [
            'user',
            'data.user',
            'data',
            'result.user',
            'response.user',
            'meta.user'
        ];

        foreach ($userKeys as $key) {
            $user = data_get($data, $key);
            if ($user && is_array($user)) {
                Log::info('User found at key: ' . $key);
                return $user;
            }
        }

        if (isset($data['meta']['token'])) {
            return [
                'email' => request('email'),
                'logged_in' => true
            ];
        }

        return null;
    }

    public function logoutruasjalan()
    {
        Session::flush();
        return redirect()->route('loginruasjalan')->with('success', 'Logout berhasil!');
    }

    public function dashboardruasjalan()
    {
        if (!Session::has('user')) {
            return redirect()->route('loginruasjalan');
        }
        
        return view('dashboardruasjalan');
    }
}