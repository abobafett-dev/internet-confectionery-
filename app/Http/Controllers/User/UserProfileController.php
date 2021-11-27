<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User_status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function create(){
        $userStatus = User_status::find(Auth::user()->id_user_status);

        return view('dashboard')->with(['userStatus'=>$userStatus]);
    }

    public function update(Request $request){
        $validated = $request->validate([
            'name' => ['filled', 'string', 'max:255'],
            'email' => ['filled', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['filled', 'string', 'regex:/^8\d{10,10}$/'],
            'password' => ['filled', 'confirmed', Rules\Password::defaults()],
            'gender' => ['filled','string'],
            'birthday' => ['filled', 'date'],
            'from' => ['filled', 'string'],
            'avatar' => ['filled', 'image'],
        ]);

        if(isset($validated['avatar'])){
            $path = Storage::putFileAs('userAvatar', $validated->file('avatar'), Auth::user()->id);
            $validated->avatars = $path;
        }

        User::find(Auth::user()->id)->update($validated);

        return redirect()->route('dashboard', ['was_updated'=>'Изменения прошли успешно!']);
    }

    public function delete(){
        User::find(Auth::user()->id)->delete();

        return redirect()->route('main');
    }
}
