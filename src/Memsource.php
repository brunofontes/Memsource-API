<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes;

use \BrunoFontes\Memsource\BilingualFile;
use \BrunoFontes\Memsource\FetchApi;
use \BrunoFontes\Memsource\Jobs;
use \BrunoFontes\Memsource\Oauth;
use \BrunoFontes\Memsource\Project;
use \BrunoFontes\Memsource\TM;
use \BrunoFontes\Memsource\Async;

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
    private $_tm;
    private $_async;
    private $_fetchApi;

    public function __construct(string $token = null, string $memsourceBaseUrl = 'https://cloud.memsource.com/web')
    {
        $this->_fetchApi = new FetchApi($token, $memsourceBaseUrl);
    }

    /**
     * Memsource Oauth functions
     *
     * @return Oauth
     */
    public function oauth(): Oauth
    {
        return $this->_oauth ?? $this->_oauth = new oauth($this->_fetchApi);
    }

    /**
     * Memsource API BilingualFile related functions
     *
     * @return BilingualFile
     */
    public function bilingualFile(): BilingualFile
    {
        return $this->_bilingualFile ?? $this->_bilingualFile = new BilingualFile($this->_fetchApi);
    }

    /**
     * Memsource API Jobs related functions
     *
     * @return Jobs
     */
    public function jobs(): Jobs
    {
        return $this->_jobs ?? $this->_jobs = new Jobs($this->_fetchApi);
    }

    /**
     * Memsource API Project related functions
     *
     * @return Project
     */
    public function project(): Project
    {
        return $this->_project ?? $this->_project = new Project($this->_fetchApi);
    }

    /**
     * Memsource API Async related functions
     *
     * @return Async
     */
    public function async(): Async
    {
        return $this->_async ?? $this->_async = new Async($this->_fetchApi);
    }

    /**
     * Memsource API TM related functions
     *
     * @return TM
     */
    public function tm(): TM
    {
        return $this->_tm ?? $this->_tm = new TM($this->_fetchApi);
    }
}
