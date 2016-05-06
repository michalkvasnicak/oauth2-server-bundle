<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantTypesPass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\StoragePass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\TokenTypePass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\UserProviderPass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Security\Factory\OAuth2Factory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2ServerBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var SecurityExtension $extension */
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuth2Factory());


        $container->addCompilerPass(new TokenTypePass());

        // those two are moved to later phase because in before optimization there aren't resolved parameters in configs
        $container->addCompilerPass(new StoragePass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new UserProviderPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new GrantTypesPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }

}
