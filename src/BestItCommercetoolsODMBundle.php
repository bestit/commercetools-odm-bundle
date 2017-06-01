<?php

namespace BestIt\CommercetoolsODMBundle;

use BestIt\CommercetoolsODMBundle\DependencyInjection\Compiler\EventListenerPass;
use BestIt\CommercetoolsODMBundle\DependencyInjection\Compiler\FilterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class BestItCommercetoolsODMBundle.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle
 * @version $id$
 */
class BestItCommercetoolsODMBundle extends Bundle
{
    /**
     * Registed the compiler pass.
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EventListenerPass());
        $container->addCompilerPass(new FilterPass());
    }
}
