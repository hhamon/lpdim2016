<?php

namespace Framework\Routing\Loader;

class JsonFileLoader extends AbstractFileLoader
{
    protected function assertSupportedFileType($path)
    {
        if ('json' !== pathinfo($path, PATHINFO_EXTENSION)) {
            throw new UnsupportedFileTypeException(sprintf(
                'File %s must be a valid JSON file.',
                $path
            ));
        }
    }

    protected function doParseContents($path, $contents)
    {
        if (!$data = json_decode($contents, true)) {
            throw new \RuntimeException(sprintf('Unable to parse the "%s" JSON file: %s.', $path, json_last_error_msg()));
        }

        return $data;
    }
}
