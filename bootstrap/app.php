<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->configure('app');
$app->configure('api');
$app->configure('jwt');
$app->configure('auth');

class_alias(Tymon\JWTAuth\Facades\JWTAuth::class, 'JWTAuth');
class_alias(Tymon\JWTAuth\Facades\JWTFactory::class, 'JWTFactory');

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Auth\AuthManager::class,
    function ($app) {
        return $app->make('auth');
    }
);
$app->singleton(
    Illuminate\Cache\CacheManager::class,
    function ($app) {
        return $app->make('cache');
    }
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
//$app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);
//$app->register(\Dingo\Api\Provider\LumenServiceProvider::class);

// JWTAuth Dependencies

$app->register(\Dingo\Api\Provider\LumenServiceProvider::class);
$app->make(\Dingo\Api\Auth\Auth::class)->extend('jwt', function ($app) {
    return new \Dingo\Api\Auth\Provider\JWT($app->make(Tymon\JWTAuth\JWTAuth::class));
});

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

/*
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});
*/

$router = app( \Dingo\Api\Routing\Router::class );

$router->version('v1', function ($router) {
    require_once __DIR__.'/../routes/api.php';
});

return $app;
