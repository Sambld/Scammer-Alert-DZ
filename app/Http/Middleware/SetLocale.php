<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLanguages = ['en', 'fr', 'ar'];
        
        // Check if language is set in session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            // Try to detect from browser
            $locale = $request->getPreferredLanguage($supportedLanguages) ?? 'en';
        }
        
        // Validate the locale
        if (in_array($locale, $supportedLanguages)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en');
        }
        
        return $next($request);
    }
}
