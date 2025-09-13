<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Logout
{
    public function handle()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return Redirect::to('/login'); // Ganti sesuai route login Anda
    }
}
