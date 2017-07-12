<?php
declare(strict_types=1);

namespace Cadre\Framework;

use Aura\Di\ContainerBuilder;
use Cadre\Framework\Traits\Cacheable;
use Cadre\Module\ModuleLoader;

class CliKernel
{
    use Cacheable;

    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function adr(array $modules = [], $autoResolve = false)
    {
        $modules = array_merge($this->project->getModules(), $modules);

        $di = $this->getContainer($modules, $autoResolve);

        return $di->get('cadre:cliadr/adr');
    }

    private function getContainer(array $modules, $autoResolve = false)
    {
        $cache = $this->getCache()->get('cadre-framework.cli-di', null);

        if (is_null($cache)) {
            $builder = new ContainerBuilder();
            $di = $builder->newInstance($autoResolve);
            $loader = new ModuleLoader($modules, $this->project->getEnv(), 'cli');

            $di->set('cadre:framework/project', $this->project);
            $loader->define($di);
            $di->lock();
            $loader->modify($di);
        } else {
            $di = unserialize($cache);
        }

        $this->getCache()->set('cadre-framework.cli-di', serialize($di));

        return $di;
    }
}
