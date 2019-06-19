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

    /**
     * Fetch API data using curl
     *
     * @param string $method     Should be 'get', 'post', 'jsonPost' or 'download'
     * @param string $url        The api url
     * @param array  $parameters Array ['key' => 'value'] of get or post fields or structured array for json requests
     * @param string $filename   [optional] Specified file in which the download request will be saved
     * 
     * @return void
     */
    protected function fetch(string $method, string $url, array $parameters, $filename = '')
    {
        switch ($method) {
        case 'get':
            $parameters = http_build_query($parameters);
            break;
        case 'post':
            $parameters = http_build_query($parameters);
            $setopt = $this->getPostParam($parameters);
            break;
        case 'jsonPost':
            $setopt = $this->getJsonPostParam($parameters);
            break;
        case 'download':
            if (empty($filename)) {
                throw new Exception("You need to specify a filename to download a file.", 1);
            }
            $setopt = $this->getDownloadFileParam($filename) 
                    + $this->getJsonPostParam($parameters);
            break;
        }
        return $this->curl($url, $setopt);
    }

    private function getDownloadFileParam(string $filename)
    {
        $file = fopen($filename, 'w+');
        return [
            CURLOPT_FILE => $file,
            CURLOPT_FOLLOWLOCATION => true,
        ];
    }

    private function getJsonPostParam(array $postFields)
    {
        return [
            CURLOPT_HTTPHEADER => ['Content-type: application/json'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postFields)
        ];
    }

    private function getPostParam(string $postFields)
    {
        return [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields
        ];
    }

    protected function curl(string $url, array $curl_extra_setopt=[])
    {
        $header = $this->token ? ["Authorization: Bearer {$this->token}"] : [];
        $curl_setopt = [
            CURLOPT_URL => $this->base_url . $url,
            CURLOPT_HTTPHEADER => ($header + $curl_extra_setopt[CURLOPT_HTTPHEADER]),
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