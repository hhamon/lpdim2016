<?php

namespace Framework;

use Framework\Routing\RequestContext;
use Framework\Routing\UrlMatcherInterface;

class RouterListener
{
    private $router;

    public function __construct(UrlMatcherInterface $router)
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
