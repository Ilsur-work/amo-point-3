<?php
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\TrackerController;
use App\Controllers\StatsController;
use App\Middleware\AuthMiddleware;
use App\Services\DatabaseService;

require __DIR__ . '/../vendor/autoload.php';

// Загрузка .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

DatabaseService::init();

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->add(function (Request $request, $handler) use ($app) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->post('/api/track', [TrackerController::class, 'track']);
$app->post('/api/login', [StatsController::class, 'login']);
$app->get('/api/stats', [StatsController::class, 'getStats'])->add(AuthMiddleware::class);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents(__DIR__ . '/../templates/test.html'));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/admin', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents(__DIR__ . '/../templates/auth.html'));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/admin/stats', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents(__DIR__ . '/../templates/stats.html'));
    return $response->withHeader('Content-Type', 'text/html');
});

$app->run();