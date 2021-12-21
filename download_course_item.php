<?php

function get_result($code, $data){
    $result = ["result_code" => $code, "data" => $data];
    return json_encode($result);
}

// Constants
$SUCCESS = 1000;
$INVALID_USER_NAME = 1155;
$INVALID_PHONE_NUMBER = 1101;
$STUDENT_NOT_FOUND = 1139;
$COURSE_NOT_FOUND = 1145;
$INVALID_UPLOAD_KEY = 1142;
$INVALID_VALUE = 1130;
$NOT_REGISTERED_IN_COURSE = 1143;
$NO_ACCESS_TO_COURSE = 1144;

// fetching inputs
$username = $_GET['username'];
$student_id = $_GET['student_id'];
$course_id  = $_GET['course_id'];
$upload_key = $_GET['upload_key'];


// checking if inputs are set
if(!$username || !$student_id || !$course_id || !$upload_key){
    echo get_result($INVALID_VALUE, null);
    exit();
} 

// verifying download
if($_GET['dev']){
    $url = "http://localhost:8000/api/tenant/student/public/download/verify";
}else{
    $url = "http://tootifa.ir/api/tenant/student/public/download/verify";
}

$postRequest = array(
    'student_id' => $student_id,
    'course_id' => $course_id,
    'upload_key' => $upload_key 
);

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url ); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "X-TENANT: $username"
));
$postResult = curl_exec($ch); 

if (curl_errno($ch)) { 
    curl_close($ch); 
    exit();
} 

curl_close($ch); 

$result = json_decode($postResult, true);
$result_code = $result['result_code'];
$data = $result['data'];

if($result_code == null){
    var_dump($postResult);
    echo get_result($INVALID_USER_NAME, null);
    exit();
}

if($result_code != $SUCCESS) {
    echo get_result($result_code, null);
    exit();
}

$type = $data['file_type'];

$file_url = "http://dltest.tootifa.ir/course_media/$username/$upload_key.$type";
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url); 
