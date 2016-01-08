<?php

namespace Framework\Templating;

class BracketRenderer extends AbstractRenderer
{
    /**
     * Renders a view and returns a string.
     *
     * @param string $view The template name
     * @param array  $vars The template variables
     *
     * @return string
     */
    public function render($view, array $vars = [])
    {
        $template = $this->loadTemplate($view);

        $mapping = $this->getVariablesMapping($vars);

        return str_replace(array_keys($mapping), array_values($mapping), $template);
    }

    private function getVariablesMapping(array $vars)
    {
        $mapping = [];
        foreach ($vars as $name => $value) {
            $key = sprintf('[[%s]]', $name);
            $mapping[$key] = $value;
        }

        return $mapping;
    }

    private function loadTemplate($view)
    {
        return file_get_contents($this->getTemplatePath($view));
    }
}
