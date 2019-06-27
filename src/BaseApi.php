<?php
/**
 * A very compact and simple Memsource API library
 * 
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */
namespace BrunoFontes\Memsource;

class BaseApi
{
    protected $fetchApi;
    
    public function __construct(\BrunoFontes\Memsource\FetchApi $fetchApi)
    {
        $this->fetchApi = $fetchApi;
    }

    protected function hasError(string $jsonResponse): bool
    {
        return isset(json_decode($jsonResponse, true)['errorCode']);
    }

    protected function getError(string $jsonResponse): string
    {
        return json_decode($jsonResponse, true)['errorDescription'];
    }

}
