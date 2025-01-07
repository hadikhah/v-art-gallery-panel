<?php

namespace App\Http\Controllers\Auth;

use App\Actions\SocialLoginAction;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Throwable;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @param SocialLoginAction $action The action to handle social login logic.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        // Use Socialite's Google driver to redirect the user to Google's OAuth page
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google OAuth callback.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback(SocialLoginAction $action)
    {

        try {
            // Retrieve the user's information from Google
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route("home")->with('error', __('Google authentication failed.'));
        }

        // Use the SocialLoginAction to find or create the user
        $user = $action->handle($googleUser, "google_id");

        // Log the user into the application
        Auth::login($user);

        // Redirect the user to the dashboard or another desired route
        return redirect()->route("home");
    }
}
