<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrdersPageController extends Controller
{

    function create()
    {
        if (Auth::user()->id_user_status != 2)
                abort(403);



        return view('adminOrders')->with([]);
    }

}
