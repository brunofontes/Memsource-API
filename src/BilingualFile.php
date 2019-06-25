<?php
/**
 * A very compact and simple Memsource API library
 *
 * @author Bruno Fontes <developer@brunofontes.net>
 * @link   https://github.com/brunofontes
 */

namespace BrunoFontes\Memsource;

class BilingualFile extends \BrunoFontes\Memsource\BaseApi
{
    private $_url = '/api2/v1/bilingualFiles';

    /**
     * Download one or more bilingual files
     *
     * As Memsource limits downloading files into 100 jobs per time, this script
     * will prevent that by making all the necessary fetchs and saving each on
     * a different file.
     *
     * An array with all the files used will be returned.
     *
     * @param string $projectUid The project uid which contain the jobs
     * @param array  $jobUids    A simple array of Job uids: ['job_uid1', 'job_uid2']
     * @param string $filename   File that will be created on server to store the
     *                           downloaded jobs
     *
     * @return array A list of the downloaded files
     */
    public function download(string $projectUid, array $jobUids, string $filename): array
    {
        $url = "/api2/v1/projects/{$projectUid}/jobs/bilingualFile";

        $groupedJobUids = array_chunk($jobUids, 100);
        for ($i = 0; $i < count($groupedJobUids); $i++) {
            $apiReadyArray = $this->_convertUidArrayToApiRequest($groupedJobUids[$i]);
            $filenames[$i] = count($groupedJobUids) > 1 ? "{$i}_{$filename}" : $filename;
            $filecontent = $this->fetchApi->fetch('jsonPost', $url, $apiReadyArray);
            $this->_saveIntoFile($filenames[$i], $filecontent);
        }
        return $filenames;
    }

    /**
     * Convert a simple Array of uids provided by the user into the array
     * format required by Memsource API
     *
     * @param array $uids A simple array of UIDs
     *
     * @return array The API ready array
     */
    private function _convertUidArrayToApiRequest(array $uids): array
    {
        foreach ($uids as $jobUid) {
            $convertedArray[] = ['uid' => $jobUid];
        }
        return ['jobs' => $convertedArray];
    }

    private function _saveIntoFile(string $filename, string $filecontent): void
    {
        try {
            $f = fopen($filename, 'w+');
            fwrite($f, $filecontent);
            fclose($f);
        } catch (\Exception $e) {
            throw new \Exception("File could not be saved: {$e->error}", 1);
        }
    }

    /**
     * Upload a bilingual file to Memsource
     *
     * @param string $filename The filename to be uploaded
     * @param array  $params   Any API (HTTP GET) parameters as ['key' => 'value'] format
     *
     * @return string The http request responde from API in json format
     */
    public function upload(string $filename, array $params): string
    {
        $urlParams = http_build_query($params);
        $fileContent = file_get_contents($filename);
        if ($fileContent === false) {
            throw new \Exception('File for upload inexistent or invalid', 1);
        }
        return $this->fetchApi->fetch('put', $this->_url . "?{$urlParams}", [$fileContent]);
    }
}
