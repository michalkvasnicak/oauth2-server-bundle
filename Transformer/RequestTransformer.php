<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Transformer;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Http\Request;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class RequestTransformer
{

    /**
     * Transforms symfony request to oauth request
     *
     * @param SymfonyRequest $request
     *
     * @return Request
     */
    public function transform(SymfonyRequest $request)
    {
        return new Request($request);
    }

}
 