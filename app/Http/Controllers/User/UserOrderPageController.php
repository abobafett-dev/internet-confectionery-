<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserOrderPageController extends Controller
{
    public function create(){


        return view('order')->with([]);
    }
}
