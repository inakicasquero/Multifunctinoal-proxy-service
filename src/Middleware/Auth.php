<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\Auth as AuthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use function in_array;

final class Auth implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = AuthService::getUser();
        if (! $user->isLogin) {
            return AppFactory::determineResponseFactory()->createResponse(302)->withHeader('Location', '/auth/login');
        }
        $enablePages = ['/user/banned', '/user/backtoadmin', '/user/logout'];
        if ($user->is_banned === 1 && ! in_array($request->getUri()->getPath(), $enablePages)) {
            return AppFactory::determineResponseFactory()->createResponse(302)->withHeader('Location', '/user/banned');
        }
        $request = $request->withAttribute('user', $user);
        return $handler->handle($request);
    }
}
