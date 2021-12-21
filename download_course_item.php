<?php

function get_result($code, $data)
{
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
if (!$username || !$student_id || !$course_id || !$upload_key) {
    echo get_result($INVALID_VALUE, null);
    die();
}

// verifying download
if ($_GET['dev']) {
    $url = "http://localhost:8000/api/tenant/student/public/download/verify";
} else {
    $url = "http://tootifa.ir/api/tenant/student/public/download/verify";
}

$postRequest = array(
    'student_id' => $student_id,
    'course_id' => $course_id,
    'upload_key' => $upload_key
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
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
    die();
}

curl_close($ch);

$result = json_decode($postResult, true);
$result_code = $result['result_code'];
$data = $result['data'];

if ($result_code == null) {
    var_dump($postResult);
    echo get_result($INVALID_USER_NAME, null);
    die();
}

if ($result_code != $SUCCESS) {
    echo get_result($result_code, null);
    die();
}

$type = $data['file_type'];
$filepath = "http://dltest.tootifa.ir/course_media/$username/$upload_key.$type";

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));
flush(); // Flush system output buffer

$handle = fopen($path, 'rb');
while (!feof($handle)) {
    echo fread($handle, 8192);
    flush();
}
fclose($handle);

die();


// // Process download
// if (file_exists($filepath)) {
   
// } else {
//     http_response_code(404);
//     die();
// }
