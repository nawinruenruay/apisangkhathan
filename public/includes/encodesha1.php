<?php
$ss = "password";
function encodesha1($ss){
    $convert = base64_encode(md5(base64_encode(md5(sha1($ss))))) ;
    return $convert;
}

?>