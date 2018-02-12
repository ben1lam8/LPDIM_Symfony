<?php

namespace AppBundle;

use AppBundle\DependencyInjection\ShowSearcherCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ShowSearcherCompilerPass());
    }
}
