Laravel Static Html Cache
=========================

Here are instructions to create static HTML files from your Laravel application and serve them directly and improve load time and lower CPU usage.

You can easily cache pages by adding the `cache.html` middleware to the route.

## How To
1. Setup your `.htaccess`
2. Add the middleware

### Setup your `.htaccess`

```
# Rewrite to html cache if it exists and the request is of a static page
# (only for get requests without url query params)
RewriteCond %{REQUEST_METHOD} GET
RewriteCond %{QUERY_STRING} !.*=.*
RewriteCond %{DOCUMENT_ROOT}/cache/html%{REQUEST_URI}/index.html -f
RewriteRule ^(.*) cache/html%{REQUEST_URI}/index.html [L]
```
<!-- 
or for Ngix (output from http://www.anilcetin.com/convert-apache-htaccess-to-nginx/)

```
if ($request_method ~ "GET") {
	set $rule_0 1$rule_0;
}
if ($args !~ ".*=.*") {
	set $rule_0 2$rule_0;
}
if ($http_cookie !~ "^.*(cartalyst_sentry).*$") {
	set $rule_0 3$rule_0;
}
if (-f $document_root/cache/html/%{TIME_DAY}/%{TIME_HOUR}/$http_host/$1/index.html) {
	set $rule_0 4$rule_0;
}
if ($rule_0 = "4321") {
	rewrite ^/(.*) /cache/html/%{TIME_DAY}/%{TIME_HOUR}/$http_host/$1/index.html last;
}
```
 -->

### Add the middleware

1. Add `CacheHtml.php` to `app/Http/Middleware/CacheHtml.php`
1. Add to route middleware in `app/Http/Kernel.php`
	```php
	protected $routeMiddleware = [
		// ...
        'cache.html' => \App\Http\Middleware\CacheHtml::class,
	];
	```

For every route you would like to cache as static html add the middleware like so:

```php
Route::view('/', 'index')->middleware('cache.html');
```
