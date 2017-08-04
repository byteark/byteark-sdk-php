<?php

namespace ByteArk\Request;

class RequestInfo
{
    protected $requestEnv;

    public function __construct($requestEnv = null)
    {
        $this->requestEnv = $requestEnv
            ? $requestEnv
            : $_SERVER;
    }

    public function get($name)
    {
        $clientIp = $this->getEnv('REMOTE_ADDR');

        switch ($name) {
            case 'client_ip':
                return $clientIp;
            case 'client_subnet16':
                return $clientIp
                    ? long2ip(ip2long($clientIp) & ip2long('255.255.0.0'))
                    : null;
            case 'client_subnet24':
                return $clientIp
                    ? long2ip(ip2long($clientIp) & ip2long('255.255.255.0'))
                    : null;
            case 'user_agent':
                return $this->getEnv('HTTP_USER_AGENT');
            default:
                return null;
        }
    }

    public function getCurrentUrl()
    {
        return ($this->getEnv('HTTPS') ? 'https://' : 'http://')
            . $this->getEnv('HTTP_HOST')
            . $this->getEnv('REQUEST_URI');
    }

    protected function getEnv($key, $defaultValue = null)
    {
        return isset($this->requestEnv[$key])
            ? $this->requestEnv[$key]
            : $defaultValue;
    }
}