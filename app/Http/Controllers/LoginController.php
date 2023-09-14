<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login(Request $r)
    {
        $loginRules = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $loginErrorMessage = [];
        $validator = Validator::make($r->all(), $loginRules, $loginErrorMessage);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            try {
                //session login
                $validated = $r->only('email', 'password');
                if (Auth::attempt($validated)) {
                    $r->session()->regenerate();

                    $intendedUrl = session('url.intended', '/dashboard');

                    return response()->json([
                        'intendedUrl' => $intendedUrl,
                    ]);
                } else {
                    return response()->json(['LoginError' => 'Email atau Password tidak ditemukan']);
                }
            } catch (Exception $e) {
                return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
            }
        }
    }
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
