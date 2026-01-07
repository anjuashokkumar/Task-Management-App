<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\Todo;
use Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }  

    public function registration()
    {
        return view('auth.registration');
    }

    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
            ->withSuccess('You have Successfully loggedin!');
        }
        return redirect("login")->withErrors('Oppes! You have entered invalid credentials');
    }

    public function postRegistration(RegistrationRequest $request)
    {  
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin!');
    }

    public function dashboard(Request $request)
    {
        if(Auth::check()){

            $query = Todo::where('user_id', auth()->id());

            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('due_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('due_date', '<=', $request->to_date);
            }

            $todos = $query->latest()->paginate(5);

            return view('dashboard', compact('todos'));
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function logout() 
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully!');;
    }
}
