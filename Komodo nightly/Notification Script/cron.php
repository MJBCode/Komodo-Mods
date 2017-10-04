<?php
ini_set('display_errors', 1);

//Set this as a cron job to check for new updates

$url= 'http://downloads.activestate.com/Komodo/nightly/komodoide/latest/SHA1SUM';
$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$output=curl_exec($ch);
curl_close($ch);


$checksum_old_path = dirname(__FILE__).'/files/SHA1SUM';


$checksum_new = $output;
$checksum_old = file_get_contents($checksum_old_path);

if($checksum_old==$checksum_new){
    echo '<p>the same, no notification</p>';
}else{
    echo '<p>not the same, send notification of an update</p>';

    //save a copy so we can send this via email.
    file_put_contents($checksum_old_path.'.txt', $output);


   $filePath=$checksum_old_path.'.txt';

    $curl_post_data=array(
        'from'    => 'Name <noreply@domain.com>',
        'to'      => 'user@domain.com',
        'subject' => 'New Nightly Build from Komodo IDE',
        'html'    => 'Download Nightly Build here: <a href="http://downloads.activestate.com/Komodo/nightly/komodoide/latest/">http://downloads.activestate.com/Komodo/nightly/komodoide/latest/</a>',
        'attachment[1]' => curl_file_create($filePath, 'text/plain', 'SHA1SUM'),
    );

    $service_url = 'https://api.mailgun.net/v3/domain.com/messages';
    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "api:mailgun-key");

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


    $curl_response = curl_exec($curl);
    $response = json_decode($curl_response);
    curl_close($curl);

    var_dump($response);

   //save new file
   file_put_contents($checksum_old_path, $output);

}



echo '<pre>';
echo $output;
echo '</pre>';
