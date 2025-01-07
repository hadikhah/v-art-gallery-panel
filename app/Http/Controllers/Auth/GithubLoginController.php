<?php

namespace App\Http\Controllers\Auth;

use App\Actions\SocialLoginAction;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Throwable;

class GithubLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * This method uses Laravel Socialite to redirect the user to GitHub's OAuth page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGithub()
    {
        // Use Socialite's GitHub driver to redirect the user to GitHub's OAuth page
        return Socialite::driver('github')->redirect();
    }

    /**
     * Handle the GitHub OAuth callback.
     *
     * This method processes the callback from GitHub after the user has authenticated.
     * It retrieves the user's information, handles the login process, and redirects the user.
     *
     * @param SocialLoginAction $action The action to handle social login logic.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGithubCallback(SocialLoginAction $action)
    {
        try {
            // Retrieve the user's information from GitHub
            $githubUser = Socialite::driver('github')->user();
        } catch (Throwable $e) {
            // Handle errors during the authentication process
            return redirect()->route("home")->with('error', __('GitHub authentication failed.'));
        }

        // Use the SocialLoginAction to find or create the user
        $user = $action->handle($githubUser, "github_id");

        // Log the user into the application
        Auth::login($user);

        // Redirect the user to the home page
        return redirect()->route("home");
    }
}
