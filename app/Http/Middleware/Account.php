<?php

namespace App\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\PageTable;
use Modules\User\Models\AdminServiceBrandFeatureChildTable;

class Account
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->session()->put('currentRouteName',  Route::currentRouteName());

        $configTable = new ConfigTable();
        $config = $configTable->getInfoByKey('timezone');

        config([
            'app.timezone' => $config['value']
        ]);
        \DB::purge('timezone');

//        if (Auth::user()->is_admin == 1) {
//            return $next($request);
//        }

        if ($request->ajax() || $request->isMethod('post')) {
            return $next($request);
        }

        $currentRouteName = Route::currentRouteName();

        if (!in_array($currentRouteName, $request->session()->get('routeList'))) {
            return redirect()->route('authorization.not-have-access');
        }

        return $next($request);
    }
}
