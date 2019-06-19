<?php
/**
 * A very compact and simple Memsource API library
 * 
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */
namespace BrunoFontes\Memsource;

class Auth
{
    protected $base_url;
    private $_client_id;
    private $_client_secret;
    
    function __construct(string $base_url)
    {
        $this->base_url = $base_url;
    }

    /**
     * Directly login and get a token valid for 24h
     *
     * @param string $username Memsource username
     * @param string $password Memsource password
     * 
     * @return string Authorization code valid for 24h only
     */
    function login(string $username, string $password)
    {
        $authorize_url = $this->base_url . '/oauth/authorize';
        return $authorize_url . '?response_type=code&client_id=' . $client_id . '&redirect_uri=' . $callback_uri . '&scope=openid';
    }
}
