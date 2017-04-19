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
            throw new \InvalidArgumentException("Access ID option is required.");
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
     * array['client-ip']  (Optional) Defines client IP that are allowed to access.
     * array['header-user-agent']  (Optional) Defines user agent in header that are allowed to access.
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

        return $url . '?' . http_build_query($this->makeQueryParams($url, $expires, $options));
    }

    protected function makeQueryParams($url, $expires, $options)
    {
        $queryParams = [
            'x-ark-access-id' => $this->options['access_id'],
            'x-ark-auth-type' => 'ark-v2',
            'x-ark-expires' => $expires,
            'x-ark-signature' => $this->makeSignature($url, $expires, $options),
        ];

        foreach ($options as $key => $value) {
            $queryParams["x-ark-sign-{$key}"] = 1;
        }

        ksort($queryParams);

        return $queryParams;
    }

    protected function makeSignature($url, $expires, $options)
    {
        return md5($this->makeStringToSign($url, $expires, $options));
    }

    protected function makeStringToSign($url, $expires, $options)
    {
        $urlComponents = parse_url($url);

        $linesToSign[] = 'GET';
        $linesToSign[] = $urlComponents['host'];
        $linesToSign[] = $urlComponents['path'];
        $linesToSign = array_merge($linesToSign, $this->makeCustomPolicyLines($options));
        $linesToSign[] = $expires;
        $linesToSign[] = $this->options['access_secret'];

        return implode("\n", $linesToSign);
    }

    protected function makeCustomPolicyLines($options)
    {
        $linesToSign = [];

        foreach ($options as $key => $value) {
            $linesToSign[] = "{$key}:{$value}";
        }

        sort($linesToSign);

        return $linesToSign;
    }
}
