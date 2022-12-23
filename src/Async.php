<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes\Memsource;

class Async extends \BrunoFontes\Memsource\BaseApi
{
    private $_url = '/api2/v1/async';

    /**
     * Get asynchronous request
     *
     * @param string $queryParams An array with the Query parameters to filter projects
     *
     * @return string The JSON answer from Memsource
     */
    public function getAsyncRequest(string $asyncRequestId, array $queryParams = []): string
    {
        $response = $this->fetchApi->fetch('get', "{$this->_url}/{$asyncRequestId}", $queryParams);
        if ($this->hasError($response)) {
            throw new \Exception("Error listing AsyncRequest: " . $this->getError($response), 1);
        }
        return $response;
    }


}
