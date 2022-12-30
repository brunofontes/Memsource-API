<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes\Memsource;

enum ExportFormat 
{
    case TMX;
    case XLSX;
}

class TM extends \BrunoFontes\Memsource\BaseApi
{
    private $_url = '/api2/v2/transMemories';

    /**
     * List projects
     *
     * @param string $queryParams An array with the Query parameters to filter projects
     *
     * @return string The JSON answer from Memsource
     */
    public function list(array $queryParams = []): string
    {
        $response = $this->fetchApi->fetch('get', '/api2/v1/transMemories/', $queryParams);
        if ($this->hasError($response)) {
            throw new \Exception("Error listing TMs: " . $this->getError($response), 1);
        }
        return $response;
    }

    /**
     * Export a TM in an async way
     *
     * @param string $tmUid The TM UID
     * @param array[string] $targetLangs The language pairs to export
     *
     * @return string A json string with all translation memories info
     */
    public function export(string $tmUid, array $targetLangs): string
    {
        $queryParam['exportTargetlangs'] = $targetLangs;
        $response = $this->fetchApi->fetch('jsonPost', "{$this->_url}/{$tmUid}/export", $queryParam);
        if ($this->hasError($response)) {
            throw new \Exception("Error getting tm {$tmUid}: " . $this->getError($response), 1);
        }
        return $response;
    }

    /**
     * Download a TM. You need to export the TM first in order to obtain the
     * asyncExport id
     *
     * @param string $asyncId The asyncId obtainable by export function
     * @param ExportFormat $format The file format that will be exported
     * @param string $filename The filename that will be created to store the
     * downloaded TM
     */
    public function download(string $asyncId, string $filename, ExportFormat $fileFormat = ExportFormat::TMX)
    {
        $queryParam['format'] = $fileFormat->name;
        $filecontent = $this->fetchApi->fetch('get', "/api2/v1/transMemories/downloadExport/{$asyncId}/", $queryParam);
        if ($this->hasError($filecontent)) {
            throw new \Exception("Error downloading TM asyncID {$asyncId}: " . $this->getError($filecontent), 1);
        }
        Helper::saveIntoFile($filename, $filecontent);
    }

}
