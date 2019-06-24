<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes;

/**
 * Memsource API class
 * 
 * Instructions: https://github.com/brunofontes/Memsource-API/
 * Memsource API details: https://cloud.memsource.com/web/docs/api
 */
class Memsource
{
    private $_oauth;
    private $_bilingualFile;
    private $_jobs;
    private $_project;
    private $_fetchApi;

    public function __construct(string $token = null, string $memsourceBaseUrl = 'https://cloud.memsource.com/web')
    {
        $this->_fetchApi = new \BrunoFontes\Memsource\FetchApi($token, $memsourceBaseUrl);
    }

    /**
     * Memsource Oauth functions
     *
     * @return \BrunoFontes\Memsource\Oauth
     */
    public function oauth(): \BrunoFontes\Memsource\Oauth
    {
        return $this->_oauth ?? $this->_oauth = new \BrunoFontes\Memsource\oauth();
    }

    /**
     * Memsource API BilingualFile related functions
     *
     * @return \BrunoFontes\Memsource\BilingualFile
     */
    public function bilingualFile(): \BrunoFontes\Memsource\BilingualFile
    {
        return $this->_bilingualFile ?? $this->_bilingualFile = new \BrunoFontes\Memsource\BilingualFile($this->_fetchApi);
    }

    /**
     * Memsource API Jobs related functions
     *
     * @return \BrunoFontes\Memsource\Jobs
     */
    public function jobs(): \BrunoFontes\Memsource\Jobs
    {
        return $this->_jobs ?? $this->_jobs = new \BrunoFontes\Memsource\Jobs($this->_fetchApi);
    }


    /**
     * Memsource API Project related functions
     *
     * @return \BrunoFontes\Memsource\Project
     */
    public function project(): \BrunoFontes\Memsource\Project
    {
        return $this->_project ?? $this->_project = new \BrunoFontes\Memsource\Project($this->_fetchApi);
    }
}
