<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    //this method will show user registration page
    public function registration(){
return view('front.account.registration');
    }
    //this will save a user
public function processRegistration(Request $request) {

    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:5|confirmed',
    ]);

    if ($validator->passes()) {

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('success','you have registered successfully.');

        return response()->json([
            'status' => true,
            'errors' => []
        ]);
    } else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }
}

    //this will show user login page
public function login() {
return view('front.account.login');
}

//auth
public function authenticate(Request $request) {
    $validator = Validator::make($request->all(),[
        'email' => 'required|email',
        'password' => 'required'
    ]);
    if ($validator->passes()) {
        if (Auth::attempt([
            'email'=>$request->email,
            'password' => $request->password
             ])) {
        return redirect()->route('account.profile');
        } else{
            return redirect()->route('account.login')->with('error','Either Email/Passsword is incorrect');

        }

    }
    else{
       return redirect()->route('account.login')
       ->withErrors($validator)
       ->withInput($request->only('email'));
    }
}
public function profile() {
    $id = Auth::user()->id;

    $user = User::where('id',$id)->first();

    return view('front.account.profile',[
    'user' => $user
    ]);
}

public function updateProfile(Request $request) {
 $id = Auth::user()->id;

$validator= Validator::make($request->all(),[
    'name' => 'required|min:5|max:20',
    'email' => 'required|email|unique:users,email,' . $id,
]);
if($validator->passes()) {

$user = User::find($id);
$user->name = $request->name;
$user->email = $request->email;
$user->mobile = $request->mobile;
$user->designation = $request->designation;
$user->save();

session()->flash('success','Profile updated successfully');

 return response()->json([
        'status' => true,
        'errors' => []

    ]);

}
else {
    return response()->json([
        'status' => false,
        'errors' => $validator->errors()

    ]);
}

}

public function logout() {
    Auth::logout();
    return redirect()->route('account.login');
}

};
