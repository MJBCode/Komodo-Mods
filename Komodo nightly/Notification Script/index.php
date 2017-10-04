<?php
//This script simply allows you to see the SHA1SUM on demand

$url= 'http://downloads.activestate.com/Komodo/nightly/komodoide/latest/SHA1SUM';
$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$output=curl_exec($ch);
curl_close($ch);


echo '<pre>';
echo $output;
echo '</pre>';
