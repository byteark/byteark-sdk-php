<?php

namespace ByteArk\Signer;

class ByteArkV2UrlSigner
{
    protected $options;

    /**
     * Create a new URL Signer instance.
     *
     * Available options:
     * array['access_id']  Defines Access ID for signing.
     * array['access_secret']  Defines Access Secret for signing.
     * array['default_age']  (Optinal) Default Signed URL age in seconds (default = 900).
     *
     * @param options array URL Signer options (See above)
     * @return void
     */
    public function __construct($options = [])
    {
        $this->options = $options;

        if (!isset($this->options['access_id'])) {
            $this->options['access_id'] = '';
        }

        if (!isset($this->options['access_secret'])) {
            throw new \InvalidArgumentException("Access secret option is required.");
        }

        if (!isset($this->options['default_age'])) {
            $this->options['default_age'] = 900;
        }
    }
    /**
     * Create a new URL Signer instance.
     *
     * Available signed URL options:
     * array['client_ip']   (Optional) Defines client IP that are allowed to access.
     * array['method']     (Optional) Defines HTTP method that allows to use (Default is 'GET').
     * array['user_agent']  (Optional) Defines user agent in header that are allowed to access.
     *
     * @param  string   $url  Original URL to sign
     * @param  int  $expires  The time that signed URL should expires in Unix timestamp in seconds
     * @param  array  $options  Signed URL options (See above)
     * @return string  Signed URL
     */
    public function sign($url, $expires = null, $options = [])
    {
        if (parse_url($url, PHP_URL_QUERY)) {
            throw new \InvalidArgumentException('This signer do not accept URL with query string');
        }

        if (!$expires) {
            $expires = time() + $this->options['default_age'];
        }

        return $url . '?' . $this->makeQueryString($this->makeQueryParams($url, $expires, $options));
    }

    protected function makeQueryParams($url, $expires, $options)
    {
        $queryParams = [
            'x_ark_access_id' => $this->options['access_id'],
            'x_ark_auth_type' => 'ark-v2',
            'x_ark_expires' => $expires,
            'x_ark_signature' => $this->makeSignature($url, $expires, $options),
        ];

        foreach ($options as $key => $value) {
            $canonicalKey = $this->makeCanonicalKey($key);

            if ($this->shouldOptionExistsInQuery($canonicalKey)) {
                if ($this->shouldOptionValueExistsInQuery($canonicalKey)) {
                    $queryParams["x_ark_{$canonicalKey}"] = $value;
                } else {
                    $queryParams["x_ark_{$canonicalKey}"] = 1;
                }
            }
        }

        ksort($queryParams);

        return $queryParams;
    }

    protected function makeQueryString($queryParams)
    {
        ksort($queryParams);

        if (!isset($this->options['skip_url_encoding']) || !$this->options['skip_url_encoding']) {
            return http_build_query($queryParams);
        }

        $pairs = [];
        foreach ($queryParams as $key => $value) {
            $pairs[] = "{$key}={$value}";
        }
        return implode('&', $pairs);
    }

    public function makeSignature($url, $expires, $options)
    {
        $stringToSign = $this->makeStringToSign($url, $expires, $options);

        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode(md5($stringToSign, true))
        );
    }

    public function makeStringToSign($url, $expires, $options)
    {
        $urlComponents = parse_url($url);

        $linesToSign[] = isset($options['method']) && $options['method']
            ? strtoupper($options['method'])
            : 'GET';
        $linesToSign[] = $urlComponents['host'];
        $linesToSign[] = (isset($options['path_prefix']) && $options['path_prefix'])
            ? $options['path_prefix']
            : $urlComponents['path'];
        $linesToSign = array_merge($linesToSign, $this->makeCustomPolicyLines($options));
        $linesToSign[] = $expires;
        $linesToSign[] = $this->options['access_secret'];

        return implode("\n", $linesToSign);
    }

    protected function makeCustomPolicyLines($options)
    {
        $linesToSign = [];

        foreach ($options as $key => $value) {
            $canonicalKey = $this->makeCanonicalKey($key);

            if ($this->shouldOptionExistsInCustomPolicyLine($canonicalKey)) {
                $linesToSign[] = "{$canonicalKey}:{$value}";
            }
        }

        sort($linesToSign);

        return $linesToSign;
    }

    protected function makeCanonicalKey($key)
    {
        return str_replace('-', '_', strtolower($key));
    }

    protected function shouldOptionExistsInCustomPolicyLine($key)
    {
        return !in_array($key, ['method', 'path_prefix']);
    }

    protected function shouldOptionExistsInQuery($key)
    {
        return $key != 'method';
    }

    protected function shouldOptionValueExistsInQuery($key)
    {
        return !in_array($key, [
            'client_ip',
            'user_agent'
        ]);
    }
}
