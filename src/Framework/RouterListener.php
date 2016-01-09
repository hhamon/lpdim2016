<?php

namespace Framework;

use Framework\Routing\RequestContext;
use Framework\Routing\RouterInterface;

class RouterListener
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(KernelEvent $event)
    {
        $request = $event->getRequest();
        $context = RequestContext::createFromRequest($request);
        $request->setAttributes($this->router->match($context));
    }
}
