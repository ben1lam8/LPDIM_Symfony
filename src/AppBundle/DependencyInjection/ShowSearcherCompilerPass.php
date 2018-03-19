<?php


namespace AppBundle\DependencyInjection;

use AppBundle\Search\ShowSearcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ShowSearcherCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $showSearcherDefinition = $container->findDefinition(ShowSearcher::class);

        $showFinderServiceMatches = $container->findTaggedServiceIds('show.finder');

        foreach ($showFinderServiceMatches as $id => $tags) {
            $serviceRef = new Reference($id);
            $showSearcherDefinition->addMethodCall('addFinder', [$serviceRef]);
        }
    }
}
