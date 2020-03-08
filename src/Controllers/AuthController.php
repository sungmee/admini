<?php

namespace Sungmee\Admini;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if ( (new Admini)->auth() ) {
            return redirect()->route('admini.posts.index', ['type' => 'news']);
        }

        return view('admini::login');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        abort_unless( (new Admini)->attempt($request->only('email', 'password') ), 403);
        session(['auth' => $request->email]);

        return redirect()->route('admini.posts.index', ['type' => 'news']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('auth');
        return redirect()->route('home');
    }
}