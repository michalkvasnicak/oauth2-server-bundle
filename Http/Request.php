<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Http;

use OAuth2\Http\IRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Request implements IRequest
{

    /**
     * @var SymfonyRequest
     */
    private $request;


    public function __construct(SymfonyRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Gets all headers or header by name
     *
     * If name is given but does not exist in headers, default value is returned
     *
     * @param null|string $name
     * @param null|mixed $default
     *
     * @return array|mixed
     */
    public function headers($name = null, $default = null)
    {
        if ($name) {
            return $this->request->headers->get($name, $default);
        }

        return $this->request->headers->all();
    }

    /**
     * Gets all query parameters or parameter by name
     *
     * If name is given but does not exist in query (GET) parameters, default value is returned
     *
     * @param null|string $name
     * @param null|mixed $default
     *
     * @return array|mixed
     */
    public function query($name = null, $default = null)
    {
        if ($name) {
            return $this->request->query->get($name, $default);
        }

        return $this->request->query->all();
    }

    /**
     * Gets all POST parameters or parameter specified by name
     *
     * If name is given but does not exist in POST parameters, default value is returned
     *
     * @param null|string $name
     * @param null|mixed $default
     *
     * @return array|mixed
     */
    public function request($name = null, $default = null)
    {
        if ($name) {
            return $this->request->request->get($name, $default);
        }

        return $this->request->request->all();
    }

    /**
     * Is request sent using given HTTP method?
     *
     * @param string $name
     *
     * @return bool
     */
    public function isMethod($name)
    {
        return $this->request->isMethod($name);
    }
}
 