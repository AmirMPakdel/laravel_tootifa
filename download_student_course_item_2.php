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
$INVALID_TOKEN = 1103;
$INVALID_LICENSE_KEY = 1160;

// fetching inputs
$username = $_GET['username'];
$token = $_GET['token'];
$lk = $_GET['lk'];
$content_id = $_GET['content_id'];
$course_id = $_GET['course_id'];
$upload_key = $_GET['upload_key'];

// echo $username . "\n";
// echo $token. "\n";
// echo $lk. "\n";
// echo $course_id. "\n";
// echo $content_id. "\n";
// echo $upload_key. "\n";

// checking if inputs are set
if (!$username || !$course_id || !$upload_key || !$content_id || (!$token && !$lk)) {
    echo get_result($INVALID_VALUE, null);
    die();
}

// verifying download
if ($_GET['dev']) {
    $url = "http://localhost:8000/api/tenant/student/public/download/verify/v2";
} else {
    $url = "http://minfo.ir/api/tenant/student/public/download/verify/v2";
}

$postRequest = array(
    'course_id' => $course_id,
    'upload_key' => $upload_key,
    'lk' => $lk,
    'token' => $token,
    'content_id' => $content_id
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
$filepath = "./course_media/$username/$upload_key.$type";


function DownloadFileAsResumable($file)
{
    $filesize = filesize($file);

    $offset = 0;
    $length = $filesize;

    if (isset($_SERVER['HTTP_RANGE'])) {
        // if the HTTP_RANGE header is set we're dealing with partial content

        $partialContent = true;

        // find the requested range
        // this might be too simplistic, apparently the client can request
        // multiple ranges, which can become pretty complex, so ignore it for now
        preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);

        $offset = intval($matches[1]);
        $length = intval($matches[2]) - $offset;
    } else {
        $partialContent = false;
    }

    if ($partialContent) {
        // output the right headers for partial content

        header('HTTP/1.1 206 Partial Content');

        header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $filesize);
    }

    // output the regular HTTP headers
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();

    $handle = fopen($file, 'rb');
    fseek($file, $offset);

    while (!feof($handle)) {
        echo fread($handle, 8192);
        flush();
    }
    fclose($handle);
    die;

    // don't forget to send the data too
    // print($data);
}

function DownloadFile($file)
{ // $file = include path
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    } else {
        echo "file not found";
    }
}

function serveFile($file_path){

    date_default_timezone_set('GMT');

    //1- file we want to serve :
    $data_size = filesize($file_path); //Size is not zero base

    $mime = 'application/octect-stream'; //Mime type of file. to begin download its better to use this.

    $filename = basename($file_path); //Name of file, no path included

    //2- Check for request, is the client support this method?
    if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {

        $ranges_str = (isset($_SERVER['HTTP_RANGE']))?$_SERVER['HTTP_RANGE']:$HTTP_SERVER_VARS['HTTP_RANGE'];

        $ranges_arr = explode('-', substr($ranges_str, strlen('bytes=')));

        //Now its time to check the ranges
        if ((intval($ranges_arr[0]) >= intval($ranges_arr[1]) && $ranges_arr[1] != "" && $ranges_arr[0] != "" )
            || ($ranges_arr[1] == "" && $ranges_arr[0] == "")){

            //Just serve the file normally request is not valid :( 
            $ranges_arr[0] = 0;

            $ranges_arr[1] = $data_size - 1;
        }

    } else { //The client dose not request HTTP_RANGE so just use the entire file

        $ranges_arr[0] = 0;

        $ranges_arr[1] = $data_size - 1;
    }

    //Now its time to serve file 
    $file = fopen($file_path, 'rb');

    $start = $stop = 0;

    if ($ranges_arr[0] === "") { //No first range in array

        //Last n1 byte
        $stop = $data_size - 1;

        $start = $data_size - intval($ranges_arr[1]);

    } elseif ($ranges_arr[1] === "") { //No last

        //first n0 byte
        $start = intval($ranges_arr[0]);

        $stop = $data_size - 1;

    } else {

        // n0 to n1
        $stop = intval($ranges_arr[1]);

        $start = intval($ranges_arr[0]);
    }    
    //Make sure the range is correct by checking the file

    fseek($file, $start, SEEK_SET);

    $start = ftell($file);

    fseek($file, $stop, SEEK_SET);

    $stop = ftell($file);

    $data_len = $stop - $start;

    //Lets send headers 

    if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {

        header('HTTP/1.0 206 Partial Content');

        header('Status: 206 Partial Content');
    }

    header('Accept-Ranges: bytes');

    header('Content-type: ' . $mime);

    header('Content-Disposition: attachment; filename="' . $filename . '"');

    header("Content-Range: bytes $start-$stop/" . $data_size );

    header("Content-Length: " . ($data_len + 1));

    //Finally serve data and done ~!
    fseek($file, $start, SEEK_SET);

    $bufsize = 2048000;

    ignore_user_abort(true);

    @set_time_limit(0);

    while (!(connection_aborted() || connection_status() == 1) && $data_len > 0) {

        echo fread($file, $bufsize);

        $data_len -= $bufsize;

        flush();
    }

    fclose($file);
}

serveFile($filepath);
