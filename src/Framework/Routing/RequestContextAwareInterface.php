<?php

namespace Framework\Routing;

interface RequestContextAwareInterface
{
    /**
     * Sets the RequestContext object.
     *
     * @param RequestContext $context
     */
    public function setRequestContext(RequestContext $context);
}
