<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;

/**
 * Class BaseBackend.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Http\Controllers
 */
abstract class BaseBackend extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $routes = array();

    protected $slugs = array();

    protected $views = array();

    protected $breadcrumb = null;

    protected $route;

    protected $slug;

    protected $view;

    protected $menu = null;

    protected $accessToken = null;

    protected $refreshToken = null;

    protected $baseUrl = null;

    public function __construct()
    {
        $this->routes = [
            'frontend' => 'frontend::',
            'backend' => 'backend::',
        ];

        $this->slugs = [
            'frontend' => '',
            'backend' => 'admin/',
        ];

        $this->views = [
            'frontend' => 'frontend.',
            'backend' => 'backend.',
        ];

        // Base Url
        $this->baseUrl = config('app.url');

        //Inject accesstoken
        $this->middleware(function ($request, $next) {
            $this->accessToken = Session::get('access_token');
            $this->refreshToken = Session::get('refresh_token');
            return $next($request);
        });
    }

    protected function breadcrumbs($children = null)
    {
        $breadcrumb = null;
        if (!empty($children)) {
            $breadcrumb .= '<ol class="breadcrumb">';
            $breadcrumb .= '<li><a href="' . route($this->routes['backend'] . 'home') . '"><i class="fa fa-home fa-fw"></i>' . 'Home' . '</a></li>';
            $breadcrumb .= $children;
            $breadcrumb .= '</ol>';
        }
        return $breadcrumb;
    }

    protected function viewShare()
    {
        view()->share([
            'menu' => $this->menu,
            'route' => $this->route,
            'slug' => $this->slug,
            'view' => $this->view,
        ]);
    }
}
