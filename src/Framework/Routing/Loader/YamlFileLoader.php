<?php

namespace Framework\Routing\Loader;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;

class YamlFileLoader extends AbstractFileLoader
{
    private $parser;

    public function __construct(YamlParser $parser)
    {
        $this->parser = $parser;
    }

    protected function assertSupportedFileType($path)
    {
        if (!in_array(pathinfo($path, PATHINFO_EXTENSION), ['yml', 'yaml'])) {
            throw new UnsupportedFileTypeException(sprintf(
                'File %s must be a valid YAML file.',
                $path
            ));
        }
    }

    protected function doParseContents($path, $contents)
    {
        try {
            $data = $this->parser->parse($contents);
        } catch (ParseException $e) {
            throw new \RuntimeException(sprintf('Unable to parse the YAML file: %s.', $path), $e->getCode(), $e);
        }

        return ['routes' => $data];
    }
}
