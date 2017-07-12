<?php
declare(strict_types=1);

namespace Cadre\Framework\Module;

use Aura\Di\Container;
use Cadre\Module\Module;

class Core extends Module
{
    public function require()
    {
        if ($this->loader()->isContext('web')) {
            return [Web::class];
        } elseif ($this->loader()->isContext('cli')) {
            return [Cli::class];
        }
    }
}
