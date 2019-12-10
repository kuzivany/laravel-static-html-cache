<?php

namespace App\Http\Middleware;

use Auth;
use Config;
use Closure;
use File;

define('CACHE_HTML_PATH', \public_path().'/cache/html/');


/*
|--------------------------------------------------------------------------
| HTML Caching Middleware
|--------------------------------------------------------------------------
|
| Responsible for caching the html output of your views.
| These pages can then be served statically as HTML by setting
| up url rewriting.
|
*/

class CacheHtml
{
	const PATH = CACHE_HTML_PATH;
	
    public function handle ( $request, Closure $next )
    {
        $response = $next($request);

		if ( $request->isMethod('get') && Auth::guest() && ! Config::get('app.debug') ) {
            $directory = rtrim(self::PATH, '\\/') . '/' . rtrim($request->getRequestUri(), '\\/');

			if ( ! File::isDirectory($directory)) {
				File::makeDirectory($directory, 0777, true);
			}
		
			File::put($directory.'/index.html', $response->getContent());
		}

        return $response;
    }
}
