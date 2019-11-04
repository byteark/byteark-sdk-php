<?php

namespace Tests\Unit\Signer;

use ByteArk\Request\RequestInfo;
use Tests\TestCase;

class RequestInfoTest extends TestCase
{
    public function testGetClientIp()
    {
        $requestInfo = new RequestInfo([
            'REMOTE_ADDR' => '103.64.253.56'
        ]);
        $this->assertEquals('103.64.253.56', $requestInfo->get('client_ip'));
    }

    public function testGetUserAgent()
    {
        $requestInfo = new RequestInfo([
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36'
        ]);
        $this->assertEquals(
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
            $requestInfo->get('user_agent')
        );
    }

    public function testGetNotExistsField()
    {
        $requestInfo = new RequestInfo([
            'REMOTE_ADDR' => '103.64.253.56'
        ]);
        $this->assertEquals(null, $requestInfo->get('not_exists'));
    }

    public function testGetCurrentUrl()
    {
        $requestInfo = new RequestInfo([
            'HTTP_HOST' => 'byteark.com',
            'REQUEST_URI' => '/videos/first?autoplay=true',
        ]);
        $this->assertEquals('http://byteark.com/videos/first?autoplay=true', $requestInfo->getCurrentUrl());
    }

    public function testGetCurrentUrl_withHttps()
    {
        $requestInfo = new RequestInfo([
            'HTTPS' => 1,
            'HTTP_HOST' => 'byteark.com',
            'REQUEST_URI' => '/videos/first?autoplay=true',
        ]);
        $this->assertEquals('https://byteark.com/videos/first?autoplay=true', $requestInfo->getCurrentUrl());
    }
}