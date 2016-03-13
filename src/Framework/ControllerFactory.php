<?php

namespace Framework;

class ControllerFactory implements ControllerFactoryInterface
{
    private $parser;

    /**
     * Constructor.
     *
     * @param ControllerNameParserInterface $parser
     */
    public function __construct(ControllerNameParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Creates an invokable controller.
     *
     * @param array $params
     *
     * @return \callable
     */
    public function createController(array $params)
    {
        if (empty($params['_controller'])) {
            throw new \RuntimeException('No _controller parameter found.');
        }

        $class = $this->parser->getClass($params['_controller']);

        $action = new $class();
        if (!method_exists($action, '__invoke')) {
            throw new \RuntimeException('Controller is not a valid PHP callable object. Make sure the __invoke() method is implemented!');
        }

        return $action;
    }
}
