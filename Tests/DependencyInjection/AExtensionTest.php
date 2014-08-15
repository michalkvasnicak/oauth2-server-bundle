<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class AExtensionTest extends BaseTestCase
{

    protected function getContainer()
    {
        return new ContainerBuilder(
            new ParameterBag(
                [
                    'kernel.debug' => false,
                    'kernel.bundles' => [],
                    'kernel.cache_dir' => sys_get_temp_dir(),
                    'kernel.environment' => 'test',
                    'kernel.root_dir' => __DIR__ . '/../../' // src dir
                ]
            )
        );
    }

}
