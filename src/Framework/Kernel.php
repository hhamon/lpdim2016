<?php

namespace Framework;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\ServiceLocator\ServiceLocatorInterface;

class Kernel implements KernelInterface
{
    private $dic;

    public function __construct(ServiceLocatorInterface $dic)
    {
        $this->dic = $dic;
    }

    private function getService($name)
    {
        return $this->dic->getService($name);
    }

    /**
     * Converts a Request object into a Response object.
     *
     * @param Request $request
     * @return Response
     */
    final public function handle(Request $request)
    {
        return $this->getService('http_kernel')->handle($request);
    }
}
