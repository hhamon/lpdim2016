<?php

namespace Framework\Templating;

use Framework\Http\Response;
use Framework\Http\ResponseInterface;

class PhpRenderer extends AbstractRenderer
{
    /**
     * Evaluates a template view file.
     *
     * @param string $view The template filename
     * @param array  $vars The view variables
     *
     * @return string
     */
    public function render($view, array $vars = [])
    {
        $path = $this->getTemplatePath($view);

        if (in_array('view', $vars)) {
            throw new \RuntimeException('The "view" template variable is a reserved keyword.');
        }

        $vars['view'] = $this;

        extract($vars);
        ob_start();
        include $path;

        return ob_get_clean();
    }

    public function e($var)
    {
        if (!is_string($var)) {
            throw new \InvalidArgumentException('$var must be a string.');
        }

        return htmlspecialchars($var, ENT_QUOTES);
    }
}
