<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes\Memsource;

class Oauth extends \BrunoFontes\Memsource\FetchApi
{
    private $_url = '/oauth';

    /**
     * Get the URL to generate the Authorization Code from Memsource
     *
     * @param string $client_id     Memsource client ID
     * @param string $client_secret Memsource client secret
     * @param string $callback_uri  URL that Memsource will redirect to
     *
     * @return string the authorization code
     */
    public function getAuthorizationCodeUrl(string $client_id, string $callback_uri)
    {
        $authorize_url = $this->base_url . $this->_url . '/authorize';
        return $authorize_url . '?response_type=code&client_id=' . $client_id . '&redirect_uri=' . $callback_uri . '&scope=openid';
    }

    public function getAccessToken(string $authorization_code, string $client_id, string $client_secret, string $callback_uri)
    {
        $token_url = $this->_url . '/token';
        $authorization = base64_encode("$client_id:$client_secret");
        $header = ["Authorization: Basic {$authorization}", 'Content-Type: application/x-www-form-urlencoded'];
        $content = "grant_type=authorization_code&code=$authorization_code&redirect_uri=$callback_uri";
        $params = [
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        ];
        $response = json_decode($this->curl($token_url, $params), true);
        return $response['access_token'];
    }
}
