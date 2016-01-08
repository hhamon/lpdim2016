<?php

namespace Application\Controller;

use Framework\AbstractAction;
use Framework\Http\RequestInterface;

final class HelloWorldAction extends AbstractAction
{
    public function __invoke(RequestInterface $request)
    {
        return $this->render('hello.twig', [
            'name' => 'hugo',
        ]);
    }
}
