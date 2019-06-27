<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes\Memsource;

class Jobs extends \BrunoFontes\Memsource\BaseApi
{
    /**
     * List jobs of a project
     * The API request returns a MAX of 50 Jobs.
     * To get more jobs you need to make more requests for next pages
     *
     * @param string $projectUid The project uid on which has the jobs to be shown
     * @param array  $parameters List of Memsource Parameters
     *
     * @return string The JSON answer from Memsource
     */
    public function list(string $projectUid, array $parameters = []): string
    {
        $url = "/api2/v2/projects/{$projectUid}/jobs";
        $response =  $this->fetchApi->fetch('get', $url, $parameters);
        if ($this->hasError($response)) {
            throw new \Exception("Error listing projects: " . $this->getError($response), 1);
        }
        return $response;
    }
}
