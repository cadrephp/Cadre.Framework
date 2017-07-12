<?php
declare(strict_types=1);

namespace Cadre\Framework\Module;

use Aura\Di\Container;
use Cadre\Framework\Delivery\DefaultInput;
use Cadre\Framework\Delivery\DefaultResponder;
use Cadre\Module\Module;
use Psr7Middlewares\Middleware\AttributeMapper;
use Psr7Middlewares\Middleware\Robots;
use Psr7Middlewares\Middleware\TrailingSlash;
use Radar\Adr\Config as Radar;
use Radar\Adr\Handler\RoutingHandler;
use Radar\Adr\Handler\ActionHandler;
use Cadre\Framework\Delivery\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response;

class Web extends Module
{
    public function require()
    {
        return [
            Radar::class,
            Twig::class,
        ];
    }

    public function define(Container $di)
    {
        /** DefaultResponder */

        $di->params[DefaultResponder::class] = [
            'twig' => $di->lazyGet('twig:environment'),
            'debugbar' => null,
        ];

        /** ExceptionHandler */

        $di->params[ExceptionHandler::class] = [
            'exceptionResponse' => $di->lazyNew(Response::class),
        ];

        /** Robots */

        $di->params[Robots::class] = [
            'allow' => !$this->loader()->isEnv('development'),
        ];

        /** TrailingSlash */

        $di->params[TrailingSlash::class] = [
            'addSlash' => true,
        ];

        $di->setters[TrailingSlash::class] = [
            'redirect' => 301,
        ];

        /** AttributeMapper */

        $di->params[AttributeMapper::class] = [
            'mapping' => [
            ],
        ];
    }

    public function modify(Container $di)
    {
        $adr = $di->get('radar/adr:adr');

        $adr->middle(ResponseSender::class);
        $adr->middle(Robots::class);
        $adr->middle(ExceptionHandler::class);
        $adr->middle(TrailingSlash::class);
        $adr->middle(AttributeMapper::class);
        $adr->middle(RoutingHandler::class);
        $adr->middle(ActionHandler::class);

        $adr->input(DefaultInput::class);
        $adr->responder(DefaultResponder::class);
    }
}
