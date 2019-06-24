<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes\Memsource;

class Project extends \BrunoFontes\Memsource\BaseApi
{
    private $_url = '/api2/v1/projects';

    /**
     * List projects
     *
     * @param string $queryParams An array with the Query parameters to filter projects
     *
     * @return string The JSON answer from Memsource
     */
    public function listProjects(array $queryParams = []): string
    {
        return $this->fetchApi->fetch('get', $this->_url, $queryParams);
    }

    /**
     * Return details about a single project
     *
     * @param string $projectUid The project UID
     *
     * @return string A json string with all project info
     */
    public function getProject(string $projectUid): string
    {
        return $this->fetchApi->fetch('get', "{$this->_url}/{$projectUid}");
    }
}
