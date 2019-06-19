<?php
/**
 * A very compact and simple Memsource API library
 * 
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */
namespace BrunoFontes;

class Memsource
{
    private $_oauth;
    protected $base_url;
    protected $token;

    public function __construct(string $memsourceBaseUrl = 'https://cloud.memsource.com/web')
    {
        $this->base_url = $memsourceBaseUrl;
    }

    public function oauth()
    {
        return $this->_oauth ?? $this->_oauth = new \BrunoFontes\Memsource\oauth();
    }

    public function get(string $url)
    {
        $response = $this->curl($url);
        return json_decode($response, true);
    }

    protected function apiDownloadFile(string $url, array $postFields, string $filename)
    {
        $file = fopen($filename, 'w+');
        $extraSetopt = [
            CURLOPT_FILE => $file,
            CURLOPT_FOLLOWLOCATION => true,
        ];
        return $this->jsonPost($url, $postFields, $extraSetopt);
    }

    protected function jsonPost(string $url, array $postFields, array $extraSetopt = [], array $extraHeader = [])
    {
        $extraHeader = (['Content-type: application/json'] + $extraHeader);
        $postFields = json_encode($postFields);
        $response = $this->post($url, $postFields, [], $extraHeader);
        return json_decode($response, true);
    }

    protected function post(string $url, string $postFields, array $extraSetopt = [], array $extraHeader = [])
    {
        $extraSetopt = (
            $extraSetopt + 
            [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields
            ]
        );
        $response = $this->curl($url, $extraSetopt, $extraHeader);
        return json_decode($response, true);
    }

    protected function curl(string $url, array $curl_extra_setopt=[], array $extraHeader=[])
    {
        $header = $this->token ? ["Authorization: Bearer {$this->token}"] : [];
        $curl_setopt = [
            CURLOPT_URL => $this->base_url . $url,
            CURLOPT_HTTPHEADER => ($header + $extraHeader),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 500,
            CURLOPT_FOLLOWLOCATION => true,
        ];
        $curl = curl_init();
        curl_setopt_array($curl, ($curl_setopt + $curl_extra_setopt));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}