<?php 

// fetching inputs
$username = $_GET['username'];
$student_id = $_GET['student_id'];
$upload_key = $_GET['upload_key'];

// verifying download
$url = "https://tootifa.ir/api/tenant/student/public/download/verify";

$postRequest = array(
    'student_id' => $student_id,
    'upload_key' => $upload_key 
);

curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "X-TENANT: $username"
));

$apiResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($apiResponse);