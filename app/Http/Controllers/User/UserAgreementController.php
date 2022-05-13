<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAgreementController extends Controller
{
    function create(){
        return view('userAgreement')->with([]);
    }
}
