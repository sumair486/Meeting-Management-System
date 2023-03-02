<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Meeting;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class UserController extends Controller
{
    public function loadRegister()
    {

        if(Auth::check()){
            return redirect('/home');

        }
        return view('register');
    }

    public function userRegister(Request $request)
    {
        $request->validate([
            'name'=>'string|required|min:1',
            'email'=>'string|required|email|max:100|unique:users',
            'password'=>'string|required|min:6|confirmed'

        ]);

        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('success','Your Register has been successfull');


    }

    public function loadLogin()
    {

        if(Auth::check()){
            return redirect('/home');

        }
        return view('login');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email'=>'string|required|email',
            'password'=>'string|required'

        ]);

        $userCredentail=$request->only('email','password');
        if(Auth::attempt($userCredentail))
        {
            return redirect('/home');
        }
        else{
            return redirect()->back()->with('error','email and password incorrect');
        }
    }


    public function home()
    {
        if(Auth::check()){
            $meetings=Meeting::where('user_id',Auth::id())->get();
            return view('home',compact('meetings'));

        }
        else{
            return redirect('/');
        }

    }



    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
}
