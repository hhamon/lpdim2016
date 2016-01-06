<?php

namespace Framework\Http;

interface MessageInterface
{
    const HTTP = 'HTTP';
    const HTTPS = 'HTTPS';

    const VERSION_1_0 = '1.0';
    const VERSION_1_1 = '1.1';
    const VERSION_2_0 = '2.0';

    public function getScheme();
    public function getSchemeVersion();
    public function getHeaders();
    public function getHeader($name);
    public function getBody();
    public function getMessage();
}
