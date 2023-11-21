<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $financialYears = FinancialYear::where('status', '!=', 'deleted')->get();
        return view('edbr.profile', [
            'user' => $request->user(), 'financialYears'
        ]);      
        // return view('profile.edit', [
        //     'user' => $request->user(),
        // ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function change_password(Request $request){
        return view('profile.change-password');
    }

    public function post_change_password(Request $request){
        $user_id  = Session::get('user_id');
        $user = User::where('id', $user_id)->first();
        if(isset($user) && !empty($user)):
                $update=array('password'=>Hash::make($request->password));
                User::find($user->id)->update($update);
                return back()->with('success','Your password has been changed successfully');
        else:
            return back()->with('error','Something wrong please try again later');              
        endif;
    }
}
