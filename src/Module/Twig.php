<?php
declare(strict_types=1);

namespace Cadre\Framework\Module;

use Aura\Di\Container;
use Cadre\Module\Module;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

class Twig extends Module
{
    public function define(Container $di)
    {
        /** Services */

        $di->set('twig:environment', $di->lazyNew(Twig_Environment::class));

        /** Twig */

        $di->params[Twig_Loader_Filesystem::class]['paths'] = $di->lazyArray([
            $di->lazyGetCall('cadre:framework/project', 'getPath', 'resources/views'),
        ]);

        $di->params[Twig_Environment::class] = [
            'loader' => $di->lazyNew(Twig_Loader_Filesystem::class),
            'options' => ['debug' => true],
        ];

        $di->setters[Twig_Environment::class]['setExtensions'] = $di->lazyArray([
            $di->lazyNew(Twig_Extension_Debug::class),
        ]);
    }
}
