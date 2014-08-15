<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler\GrantType;


use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantType\ImplicitPass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ImplicitPassTest extends BaseTestCase
{

    public function testProcessMethod()
    {
        $container = new ContainerBuilder();
        $compiler = new ImplicitPass();

        $compiler->process($container);
    }

}
 