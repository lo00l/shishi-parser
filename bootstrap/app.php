<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

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

$app = new App\Application(
    realpath(__DIR__.'/../')
);

 $app->withFacades();

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
//    App\Http\Middleware\ExampleMiddleware::class
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

 $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

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

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../routes/web.php';
});

$app->configureMonologUsing(function ($monolog) {
    $infoHandler = new RotatingFileHandler(
        env('INFO_LOG_PATH') . DIRECTORY_SEPARATOR . 'info.log',
        0,
        Logger::INFO,
        false
    );
    $infoHandler->setFilenameFormat('{filename}-{date}', RotatingFileHandler::FILE_PER_DAY);
    $monolog->pushHandler($infoHandler);

    $errorHandler = new RotatingFileHandler(
        env('ERROR_LOG_PATH') . DIRECTORY_SEPARATOR . 'error.log',
        0,
        Logger::ERROR,
        false
    );
    $infoHandler->setFilenameFormat('{filename}-{date}', RotatingFileHandler::FILE_PER_DAY);
    $monolog->pushHandler($errorHandler);

    return $monolog;
});

$app->alias('Log', \Illuminate\Support\Facades\Log::class);

return $app;
