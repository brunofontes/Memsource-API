<?php
session_start();

if (!isset($_SESSION['token'])) {
    header('Location: /oauth.php');
}

define('BASE_URL', 'https://cloud.memsource.com/web');

print_r(listProjects()); die();
// print_r(getProject('dOVoecVbGYq85VwGYkJgY0')); die();

$resource = getResource($_SESSION['token']);
print_r($resource); die();

echo "<br>\nuserName: {$resource['user']['userName']}";
echo "<br>\nName: {$resource['user']['firstName']}";
echo "<br>\nLast name: {$resource['user']['lastName']}";
echo "<br>\nEmail: {$resource['user']['email']}";
echo "<br>\nEdition: {$resource['edition']['name']}";
echo "<br>\nOrganization: {$resource['organization']['name']}";

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
    $url = '/api2/v1/projects/'.$projectUid;
    return apiGet($_SESSION['token'], $url);
}

function listJobs(string $projectUid)
{
    $url = '/api2/v1/projects/'.$projectUid.'/jobs/';
    return apiGet($_SESSION['token'], $url);
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

function apiJsonPost(string $access_token, string $url, array $postFields)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => BASE_URL . $url,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer {$access_token}", "Content-type: application/json"],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postFields),
        CURLOPT_RETURNTRANSFER => true
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

function getResource($access_token)
{
    //$test_api_url = 'https://cloud.memsource.com/web/api2/v1/auth/whoAmI';
    //$test_api_url = 'https://cloud.memsource.com/web/api2/v1/projects';
    // $test_api_url = 'https://cloud.memsource.com/web/api2/v1/projects/dOVoecVbGYq85VwGYkJgY0';
    // $test_api_url = 'https://cloud.memsource.com/web/api2/v2/projects/dOVoecVbGYq85VwGYkJgY0/jobs';
    $test_api_url = 'https://cloud.memsource.com/web/api2/v1/projects/dOVoecVbGYq85VwGYkJgY0/jobs/bilingualFile';
    $array_content = ['jobs' => [['uid' => 'tRJYN7LHPmaS8BVL3Vx8p2']]];
    $content = json_encode($array_content);

    $header = ["Authorization: Bearer {$access_token}", "Content-type: application/json"];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $test_api_url,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_SSL_VERIFYPEER => false,
        // CURLOPT_CUSTOMREQUEST => 'DELETE', //   <-- This is how to send a DELETE request
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $content,
        CURLOPT_RETURNTRANSFER => true
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}