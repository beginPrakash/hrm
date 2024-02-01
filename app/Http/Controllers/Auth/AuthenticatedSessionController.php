<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if((isset($_COOKIE['email']) && !empty($_COOKIE['email'])) && (isset($_COOKIE['password']) && !empty($_COOKIE['password']))):
            if( Auth::attempt(['email'=>$_COOKIE['email'],'password'=>$_COOKIE['password']])):
                $user = Auth::user();
                Log::debug( auth()->loginUsingId($user->id) );
                if(Auth::check()){
                    $this->setUserSession($user);
                    return redirect()->to(url('/dashboard'));
                }else{
                    return view('auth.login');
                }
            else:
                return view('auth.login');
            endif;
        endif;
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        setcookie("email",$request->email,time()+ 365 * 24 * 60 * 60);
        setcookie("password",$request->password,time()+ 365 * 24 * 60 * 60);
       
        $user = Auth::user();  
        $request->session()->regenerate();
        $this->setUserSession($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    protected function setUserSession($user)
    {
        session(
            [
                'company_id' => $user->company_id,
                'user_id'    => $user->id,
                'is_admin'   => $user->is_admin,
                'username'   => $user->name,
            ]
        );
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        setcookie("email","");
        setcookie("password","");

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
