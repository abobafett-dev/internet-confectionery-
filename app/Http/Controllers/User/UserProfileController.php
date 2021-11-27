<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User_status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function create(){
        $userStatus = User_status::find(Auth::user()->id_user_status);
        $user = Auth::user();
        if($user['avatar'] != null)
            $user['avatar'] = Storage::url($user['avatar']) . "?r=" . rand(0,1000);
        if($user['birthday'] != null)
            $user['birthday'] = date("Y-m-d", strtotime($user['birthday']));

        return view('dashboard')->with(['user'=> $user, 'userStatus'=>$userStatus]);
    }

    public function update(Request $request){
        $validated = $request->validate([
            'name' => ['filled', 'string', 'max:255'],
            'phone' => ['filled', 'string', 'regex:/^8\d{10,10}$/'],
            'gender' => ['filled','string'],
            'birthday' => ['filled', 'date'],
            'from' => ['filled', 'string'],
            'avatarFile' => ['filled', 'image','mimes:jpeg,jpg,png','max:5500'],
        ]);

        if(isset($validated['avatarFile'])){
            $path = Storage::putFileAs('public/userAvatar', $validated['avatarFile'], Auth::user()->id . ".png");
            unset($validated['avatarFile']);
            $validated['avatar'] = $path;
        }

        User::find(Auth::user()->id)->update($validated);

        return redirect('dashboard')->with(['was_updated'=>'Изменения прошли успешно!']);
    }

    public function delete(){
        User::find(Auth::user()->id)->delete();

        return redirect()->route('main');
    }
}
