<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\OAuth2ServerBundle;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use \Mockery as m;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2ServerBundleTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildMethod()
    {
        $securityExtension = new SecurityExtension();

        $bundle = new OAuth2ServerBundle();
        $container = new ContainerBuilder();
        $container->registerExtension($securityExtension);

        $bundle->build($container);
    }

    protected function tearDown()
    {
        parent::tearDown();

        m::close();
    }
}
