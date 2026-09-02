<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBusinessRole
{
    /**
     * Handle an incoming request for Business Owner actions.
     * Ensures strict role separation:
     * - Job Seekers / Candidates are NOT permitted to create, edit, or manage businesses.
     * - Only dedicated Business Users can access business management tools.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 1. If not logged in at all (Guest)
        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'Please log in with a Business Account to continue.'
                ], 401);
            }

            flash(__('To add or manage a business, please log in with a Business Account.'))->info();
            return redirect()->route('business.login');
        }

        $user = Auth::user();

        // 2. If logged in as Job Seeker / Candidate (NOT a Business User)
        if ($user->isJobSeeker()) {
            // For API / AJAX requests (POST, PUT, DELETE, etc.)
            if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete') || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => "Unauthorized. You are currently logged in as a Job Seeker. Business listings cannot be created or managed with a Job Seeker account. Please log in with a Business Account."
                ], 403);
            }

            // For standard GET navigation (e.g. Add Business, Business Dashboard, etc.)
            // Render the dedicated Business Account prompt view
            return response()->view('business.auth.switch_account', [
                'currentUser' => $user,
                'targetAction' => $request->path()
            ]);
        }

        // 3. User is an authorized Business User
        return $next($request);
    }
}
