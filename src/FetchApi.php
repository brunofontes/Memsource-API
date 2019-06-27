<?php
/**
 * A very compact and simple Memsource API library
 * 
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */
namespace BrunoFontes\Memsource;

class FetchApi
{
    protected $base_url;
    protected $token;
    
    /**
     * BaseAPI needs at least the Memsource Token to use it's API
     *
     * @param string $token
     * @param string $memsourceBaseUrl [Optional] A non-standard Memsource URL base for the API to work
     */
    public function __construct(string $token = null, string $memsourceBaseUrl = 'https://cloud.memsource.com/web')
    {
        $this->base_url = $memsourceBaseUrl;
        $this->token = $token;
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
    public function fetch(string $method, string $url, array $parameters = [], $filename = '') : string
    {
        $setopt = [];
        switch ($method) {
        case 'get':
            $this->checkAccessToken();
            $parameters = http_build_query($parameters);
            $url = $url . ($parameters ? '?'.$parameters : '');
            break;
        case 'put':
            $this->checkAccessToken();
            $setopt = $this->getPutParam()+$this->getPostParam(implode("", $parameters));
            break;
        case 'post':
            $this->checkAccessToken();
            $parameters = http_build_query($parameters);
            $setopt = $setopt + $this->getPostParam($parameters);
            break;
        case 'jsonPost':
            $this->checkAccessToken();
            $setopt = $this->getJsonPostParam($parameters);
            break;
        case 'download':
            $this->checkAccessToken();
            if (empty($filename)) {
                throw new Exception('You need to specify a filename to download a file.', 1);
            }
            $setopt = $this->getDownloadFileParam($filename)
                    + $this->getJsonPostParam($parameters);
            break;
        default:
            throw new \Exception("Method {$method} is invalid on Fetch", 1);
        }
        return $this->curl($url, $setopt);
    }

    private function checkAccessToken()
    {
        if (empty($this->token)) {
            throw new \Exception("Missing Access Token", 1);
        }
    }

    private function getDownloadFileParam(string $filename)
    {
        return [
            CURLOPT_FILE => $file,
        ];
    }

    private function getJsonPostParam(array $postFields = [])
    {
        return [
            CURLOPT_HTTPHEADER => ['Content-type: application/json'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postFields)
        ];
    }

    private function getPostParam(string $postFields = '')
    {
        return [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields
        ];
    }

    private function getPutParam()
    {
        return [
            CURLOPT_HTTPHEADER => ['Content-type: application/octet-stream'],
            CURLOPT_CUSTOMREQUEST => "PUT"
        ];
    }

    protected function curl(string $url, array $curl_extra_setopt = [])
    {
        if (empty($url)) {
            throw new \Exception('URL not defined', 1);
        }

        $header = ($this->token ? ["Authorization: Bearer {$this->token}"] : []);
        $header = array_merge($header, $curl_extra_setopt[CURLOPT_HTTPHEADER]??[]);
        $curl_setopt = [
            CURLOPT_URL => $this->base_url . $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 500,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true
        ] + $curl_extra_setopt;
        $curl = curl_init();
        curl_setopt_array($curl, $curl_setopt);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
