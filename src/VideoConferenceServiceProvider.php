<?php

namespace Codenidus\VideoConference;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Http\Kernel;
use Codenidus\VideoConference\Http\Middleware\BaseVideoConferenceAuthorize;


class VideoConferenceServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Kernel $kernel): void
    {
        // set middleware in router
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('videoconferenceAuthorize', BaseVideoConferenceAuthorize::class);

        $this->registerRoutes();
        $this->registerResources();
        $this->registerVueAssetsPublish();
        $this->registerConfigFilePublish();
        $this->registerMiddlewareFilePublish();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'videoconference');
    }

    public function registerVueAssetsPublish()
    {
        $this->publishes([
            __DIR__.'/../resources/js/app-video-conference.js' => resource_path('js/app-video-conference.js'),
            __DIR__.'/../resources/js/App-video-conference.vue' => resource_path('js/App-video-conference.vue'),
            __DIR__.'/../resources/js/router/router-video-conference.js' => resource_path('js/router/router-video-conference.js'),
            __DIR__.'/../resources/js/router/webrtc.js' => resource_path('js/router/webrtc.js'),
        ], 'videoconference-vue');
    }

    public function registerConfigFilePublish()
    {
      $this->publishes([
        __DIR__.'/../config/video-conference.php' => config_path('video-conference.php'),
      ], 'videoconference-config');
    }

    public function registerMiddlewareFilePublish()
    {
        $this->publishes([
            __DIR__.'/Http/Middleware/VideoConferenceAuthorize.php' => app_path('Http/Middleware/VideoConferenceAuthorize.php'),
        ], 'videoconference-middleware');
    }

    public function registerRoutes()
    {
        Route::group($this->webRouteConfiguration(), function () {
           $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });


        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/video-conference.php', 'video-conference');

        $this->commands([
            Console\ProcessCommand::class
        ]);

        // Register the service the package provides.
        $this->app->singleton('video-conference', function ($app) {
            return new VideoConference;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['video-conference'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    protected function webRouteConfiguration()
    {
        $route = [
            'prefix' => config('video-conference.prefix'),
            'middleware' => []
        ];

        $middlewareList = array_merge(['web'],  config('video-conference.routes.web.middleware', []));

        if(!config('video-conference.demo_user')) {
            $route['middleware'] = array_merge($route['middleware'], $middlewareList);
        }

        $route['middleware'][] = 'videoconferenceAuthorize';

        return $route;
    }

    protected function apiRouteConfiguration()
    {
        $route = [
            'prefix' => 'api/'. config('video-conference.prefix'),
            'middleware' => []
        ];

        $middlewareList = array_merge(['api'],  config('video-conference.routes.api.middleware', []));

        if(!config('video-conference.demo_user')) {
            $route['middleware'] = array_merge($route['middleware'], $middlewareList);
        }

        $route['middleware'][] = 'videoconferenceAuthorize';

        return $route;
    }
}
