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
    public function list(array $queryParams = []): string
    {
        $response =  $this->fetchApi->fetch('get', $this->_url, $queryParams);
        if ($this->hasError($response)) {
            throw new \Exception("Error listing projects: " . $this->getError($response), 1);
        }
        return $response;
    }

    /**
     * Return details about a single project
     *
     * @param string $projectUid The project UID
     *
     * @return string A json string with all project info
     */
    public function get(string $projectUid): string
    {
        $response = $this->fetchApi->fetch('get', "{$this->_url}/{$projectUid}");
        if ($this->hasError($response)) {
            throw new \Exception("Error getting project {$projectUid}: " . $this->getError($response), 1);
        }
    }

    /**
     * Edit the project status
     *
     * @param string $projectUid The project UID
     * @param string $status The new status
     */
    public function editStatus(string $projectUid, string $status): void
    {
        $queryParam = ['status' => $status];
        $response = $this->fetchApi->fetch('jsonPost', "{$this->_url}/{$projectUid}/setStatus", $queryParam);
        if ($this->hasError($response)) {
            throw new \Exception("Error editing project status on project: {$projectUid}: " . $this->getError($response), 1);
        }
    }
}
