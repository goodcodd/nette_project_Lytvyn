<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList;
        $router->addRoute('/', 'Home:index');
        $router->addRoute('/home', 'Home:index');
        $router->addRoute('/about', 'Home:about');
        $router->addRoute('/contacts', 'Home:contacts');

        $router->addRoute('/parliament', 'Pages:parliament');
        $router->addRoute('/passport', 'Pages:passport');

        $router->addRoute('sign/in', 'Sign:in');
        $router->addRoute('sign/out', 'Sign:out');
        $router->addRoute('sign/up', 'Sign:up');

        $router->addRoute('/news', 'News:index');

        $router->addRoute('/edit', 'News:create');

        return $router;
    }
}
