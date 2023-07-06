<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginPostRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    function login(LoginPostRequest $request){
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if($this->attemptLogin($request)){
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            $request->session()->regenerate();
            return redirect()->intended('person');
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    function attemptLogin(LoginPostRequest $request) : bool{
        return Auth::guard()->attempt($request->only('email', 'password'), $request->boolean('remember'));
    }

    function sendFailedLoginResponse(LoginPostRequest $request)
    {
        $email = $request->input('email');
        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
        throw ValidationException::withMessages([
            'password' => [trans('auth.password')],
        ]);
    }

    public function username() : string
    {
        return 'email';
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
