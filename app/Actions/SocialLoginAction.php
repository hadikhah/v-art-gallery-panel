<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as SocialUser;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;

class SocialLoginAction
{
    /**
     * Handle the social login logic.
     *
     * This method finds or creates a user based on the social provider's user data.
     * It also marks the user's email as verified if it hasn't been verified yet.
     *
     * @param SocialUser $socialUser The user data from the social provider.
     * @param string $socialIdField The database column name for the social provider's ID (e.g., 'google_id', 'github_id').
     * @return User The found or created user.
     */
    public function handle(SocialUser $socialUser, string $socialIdField)
    {
        // Use a database transaction to ensure data consistency
        $user = DB::transaction(function () use ($socialUser, $socialIdField): User {
            // Try to find the user by email and social provider ID
            $user = User::where('email', $socialUser->email)
                ->where($socialIdField, $socialUser->id)
                ->first();

            // If the user doesn't exist, create or update the user
            if (!$user) {
                $user = User::updateOrCreate([
                    'email' => $socialUser->email, // Match the user by email
                ], [
                    'name' => $socialUser->name, // Update or set the user's name
                    'password' => Hash::make(uniqid()), // Set a random password (not used for social login)
                    $socialIdField => $socialUser->id, // Store the social provider's ID for future logins
                ]);
            }

            // Mark the email as verified if it's not already verified
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                event(new Verified($user)); // Trigger the Verified event
            }

            return $user;
        });

        return $user;
    }
}
