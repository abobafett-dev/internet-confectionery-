<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserCartController extends Controller
{
    public function create(){


        return view('cart')->with([]);
    }
}
