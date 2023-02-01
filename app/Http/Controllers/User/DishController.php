<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DIshController extends Controller
{
    public function create (): View
    {
        $user = Auth::user();
        return view('recipes.create', compact('user'));
    }

}