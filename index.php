<?php
session_start();
if (!isset($_SESSION['token'])) {
    header('Location: /oauth.php');
}

$resource = getResource($_SESSION['token']);
// print_r($resource); die();
echo "<br>\nuserName: {$resource['user']['userName']}";
echo "<br>\nName: {$resource['user']['firstName']}";
echo "<br>\nLast name: {$resource['user']['lastName']}";
echo "<br>\nEmail: {$resource['user']['email']}";
echo "<br>\nEdition: {$resource['edition']['name']}";
echo "<br>\nOrganization: {$resource['organization']['name']}";

//	we can now use the access_token as much as we want to access protected resources
function getResource($access_token)
{
    $test_api_url = 'https://cloud.memsource.com/web/api2/v1/auth/whoAmI';
    // $test_api_url = 'https://cloud.memsource.com/web/api2/v1/projects';
    // $test_api_url = 'https://cloud.memsource.com/web/api2/v1/projects/zFbwYXoMLoa3cJ60Vvldo2?purge=true';

    $header = ["Authorization: Bearer {$access_token}"];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $test_api_url,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true
        // CURLOPT_CUSTOMREQUEST => 'DELETE'   <-- This is how to send a DELETE request
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}
