<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $correctUser = auth()->user()->isRole([
            'master', 'admin', 'doctor', 'patient', 'user'
        ]);
        if( $correctUser ){
            if( auth()->user()->isRole(['master', 'admin', 'doctor']) ){
                return redirect()->intended(route('admin.home'));
            }else{
                return redirect()->intended(route('user.home'));
            }
        }else{
            auth()->logout();
            return redirect()->route('login')->with('alert', "Invalid User! Contact with support for more info.");
        }
        return redirect()->route('login');
    }

    public function routes()
    {
        $role = auth()->user()->isAdmin() ? "admin" : "user";
        $routes = array_map(function($item){
            if( has_route($item['route']??'') ){
                $item['url'] = route($item['route']);
            }
            return $item;
        }, config("navigation.{$role}", []));
        return response()->json($routes);
    }
}
