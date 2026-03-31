<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    //Display the registration view.
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            
            // PERUBAHAN DISINI: Tambah 'ends_with:@gmail.com'
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:'.User::class, 
                'ends_with:@gmail.com' // <--- INI KUNCINYA (Wajib Gmail)
            ],
            
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'], 
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //Simpan ke Database
        $user = User::create([  // <--- INI DIA USER::CREATE NYA
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',  // <--- INI JUGA SUDAH ADA (Role Customer)
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }
}

