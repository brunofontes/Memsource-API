<?php
session_start();

if (!isset($_SESSION['token'])) {
    header('Location: /oauth.php');
}

define('BASE_URL', parse_ini_file('config.ini')['base_url']);

// print_r(listProjects()); die();
print_r(getProject('dOVoecVbGYq85VwGYkJgY0')); die();
// print_r(listJobs('dOVoecVbGYq85VwGYkJgY0')); die();
downloadBilingualFiles('dOVoecVbGYq85VwGYkJgY0', ['tRJYN7LHPmaS8BVL3Vx8p2', 'DJbSRDTC7Jo004AvIb1V1M'], 'download.mxliff');
die();

$resource = getResource($_SESSION['token']);
print_r($resource); die();

function listProjects()
{
    $url = '/api2/v1/projects';
    $apiResponse = apiGet($_SESSION['token'], $url);
    if ($apiResponse['totalPages'] > 1) {
        //TODO: Repeat request to get other pages
    }
    $projects = [];
    foreach ($apiResponse['content'] as $apiProject) {
        $projects[] = [
            'uid' => $apiProject['uid'],
            'name' => $apiProject['name'],
            'status' => $apiProject['status'],
            'dateCreated' => $apiProject['dateCreated']
        ];
    }
    return $projects;
}

function getProject(string $projectUid)
{
    $url = '/api2/v1/projects/' . $projectUid;
    return apiGet($_SESSION['token'], $url);
}

function listJobs(string $projectUid)
{
    $url = '/api2/v1/projects/' . $projectUid . '/jobs/';
    return apiGet($_SESSION['token'], $url);
}

function downloadBilingualFiles(string $projectUid, array $jobsUid, string $filename)
{
    $url = '/api2/v1/projects/'.$projectUid.'/jobs/bilingualFile?format=MXLF';
    foreach ($jobsUid as $key => $jobUid) {
        $postFields[] = ['uid' => $jobUid];
    }
    return apiDownloadFile($_SESSION['token'], $url, ['jobs' => $postFields], $filename);
}

//	we can now use the access_token as much as we want to access protected resources
function apiGet(string $access_token, string $url)
{
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        [
            CURLOPT_URL => BASE_URL . $url,
            CURLOPT_HTTPHEADER => ["Authorization: Bearer {$access_token}"],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ]
    );
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

function apiJsonPost(string $access_token, string $url, array $postFields, array $extraHeader = [])
{
    $header = array_merge(
        ["Authorization: Bearer {$access_token}", 'Content-type: application/json'],
        $extraHeader
    );
    $curl = curl_init();
    curl_setopt_array(
        $curl, 
        [
            CURLOPT_URL => BASE_URL . $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_RETURNTRANSFER => true
        ]
    );
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

function apiDownloadFile($access_token, string $url, array $postFields, string $filename)
{
    $file = fopen($filename, 'w+');
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => BASE_URL . $url,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer {$access_token}", 'Content-type: application/json'],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 500,
        CURLOPT_FILE => $file,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postFields)
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}