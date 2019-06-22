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
}
