<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->method() === 'GET') {
            $segment = $request->segment(1);

            // dd($segment);
            if (!in_array($segment, config('app.locales'))) {
                $segments = $request->segments();
                $fallback = session('locale') ?: config('app.fallback_locale');
                $segments = array_prepend($segments, $fallback);

                return redirect()->to(implode('/' . $fallback, $segments));
            }

            session(['locale' => $segment]);
            app()->setLocale($segment);
        }


        return $next($request);
    }
}
