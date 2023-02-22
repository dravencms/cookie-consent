<?php declare(strict_types = 1);

namespace Dravencms\CookieConsent;

use Dravencms\Application\IRouterFactory;
use Nette\Application\Routers\RouteList;

/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class RouteFactory implements IRouterFactory
{
    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter(): RouteList
    {
        $router = new RouteList();

        $frontEnd = new RouteList('Front');

        $frontEnd->addRoute('cookieconsent-init.js', 'CookieConsent:Settings:default');

        $router->add($frontEnd);
        
        return $router;
    }
}
