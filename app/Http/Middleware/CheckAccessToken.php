<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class CheckAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if( ! session('accessToken') || !session('shop'))
	    {
		    $res = $request->all();
		    $sh = App::make('ShopifyAPI');
		    $sh->setup(['API_KEY' => env('SP_API_KEY'), 'API_SECRET' => env('SP_SECRET_KEY'), 'SHOP_DOMAIN' => $res['shop']]);
		    $url_auth = $sh->installURL(['permissions' => array('write_orders', 'write_products'), 'redirect' => route('app.auth')]);
		    return redirect($url_auth);
	    }
        return $next($request);
    }
}
