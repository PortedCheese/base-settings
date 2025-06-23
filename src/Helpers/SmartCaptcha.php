<?php

namespace PortedCheese\BaseSettings\Helpers;

use GuzzleHttp\Client;

class SmartCaptcha
{

    const API_URI = 'https://smartcaptcha.yandexcloud.net/captcha.js';
    const VERIFY_URI = 'https://smartcaptcha.yandexcloud.net/validate';
    
    protected $siteKey;
    protected $secretKey;
    protected $client;

    public function __construct()
    {
        $this->siteKey = siteconf()->get("base-settings", "recaptchaSiteKey");
        $this->secretKey = siteconf()->get("base-settings", "recaptchaSecretKey");
        $this->client = new Client();
    }

    /**
     * Verify invisible reCaptcha response.
     *
     * @param string $response
     * @param string $clientIp
     *
     * @return bool
     */
    public function verifyResponse($response, $clientIp, $clientToken)
    {
        if (empty($response)) {
            return false;
        }

        $response = $this->sendVerifyRequest([
            'secret' => $this->secretKey,
            'ip' => $clientIp,
            'token' => $clientToken,
            //'response' => $response
        ]);
        return isset($response['status']) && $response['status'] === "ok";
    }

    /**
     * Send verify request.
     *
     * @param array $query
     *
     * @return array
     */
    protected function sendVerifyRequest(array $query = [])
    {
        $response = $this->client->post(static::VERIFY_URI, [
            $query,
        ]);

        return json_decode($response->getBody(), true);
    }
}