<?php 


// fetching inputs
$username = $_GET['username'];
$student_id = $_GET['student_id'];
$upload_key = $_GET['upload_key'];

// verifying download
$url = "http://tootifa.ir/api/tenant/student/public/download/verify";

$postRequest = array(
    'student_id' => $student_id,
    'upload_key' => $upload_key 
);

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url ); 
curl_setopt($ch, CURLOPT_POST, 1 ); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "X-TENANT: $username"
));
$postResult = curl_exec($ch); 


if (curl_errno($ch)) { 
   print curl_error($ch); 
} 

echo "5";

curl_close($ch); 

// header('Content-Type: application/json; charset=utf-8');
$r = array($postResult)['result_code'];
$t = 5;

