<?php

function get_result($code, $data)
{
    $result = ["result_code" => $code, "data" => $data];
    return json_encode($result);
}

// Constants
$SUCCESS = 1000;
$INVALID_USER_NAME = 1155;
$INVALID_UPLOAD_KEY = 1142;
$INVALID_VALUE = 1130;

// fetching inputs
$username = $_GET['username'];
$token = $_GET['token'];
$upload_key = $_GET['upload_key'];


// checking if inputs are set
if (!$username || !$token) {
    echo get_result($INVALID_VALUE, null);
    die();
}

// verifying download
if ($_GET['dev']) {
    $url = "http://localhost:8000/api/tenant/user/download/verify";
} else {
    $url = "http://tootifa.ir/api/tenant/user/download/verify";
}

$postRequest = array(
    'token' => $token,
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

DownloadFile($filepath);
