<?php
declare(strict_types=1);

namespace Cadre\Framework\Module;

use Aura\Di\Container;
use Cadre\DomainSession\SessionManager;
use Cadre\DomainSession\Storage\Files;
use Cadre\Module\Module;

class Session extends Module
{
    public function define(Container $di)
    {
        /** Services */

        $di->set('cadre:session/manager', $di->lazyNew(SessionManager::class));

        /** SessionManager */

        $di->params[Files::class] = [
            'path' => $di->lazyGetCall('cadre:framework/project', 'getPath', 'sessions'),
        ];

        $di->params[SessionManager::class] = [
            'storage' => $di->lazyNew(Files::class),
        ];
    }

    public function modify(Container $di)
    {
    }
}
