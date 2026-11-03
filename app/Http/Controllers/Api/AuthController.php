<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller {
    public function login(Request $request) {
        $credentials = $request->validate(['email'=>'required|email','password'=>'required|string']);
        if (!Auth::attempt($credentials, false) || !$request->user()->is_active) {
            Auth::logout();
            throw ValidationException::withMessages(['email'=>['The provided credentials are incorrect or the account is inactive.']]);
        }
        $request->session()->regenerate();
        ActivityLog::record($request->user(), 'login', $request->user(), 'Signed in to the clinic dashboard.');
        return response()->json(['data'=>$request->user()]);
    }
    public function me(Request $request){ return response()->json(['data'=>$request->user()]); }
    public function logout(Request $request){
        ActivityLog::record($request->user(), 'logout', $request->user(), 'Signed out of the clinic dashboard.');
        Auth::guard('web')->logout();
        $request->session()->invalidate(); $request->session()->regenerateToken();
        return response()->json(['message'=>'Logged out successfully.']);
    }
}
