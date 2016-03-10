<?php

namespace Framework;

use Framework\Routing\RequestContext;
use Framework\Routing\RequestContextAwareInterface;
use Framework\Routing\UrlMatcherInterface;

class RouterListener
{
    private $router;
    private $urlGenerator;

    public function __construct(UrlMatcherInterface $router, RequestContextAwareInterface $urlGenerator = null)
    {
        $this->router = $router;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(KernelEvent $event)
    {
        $request = $event->getRequest();
        $context = RequestContext::createFromRequest($request);
        $request->setAttributes($this->router->match($context));

        if ($this->urlGenerator) {
            $this->urlGenerator->setRequestContext($context);
        }
    }
}
