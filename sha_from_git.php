<?php
if (isset($argv[1])) {
    if(($val = getopt(null, ["service:"]))) {
        if ($val['service'] != "github"){
            echo "This version use Github only\n";
        } elseif(isset($argv[3])) {
            ApiByCUrl($argv[2], $argv[3]);
        } else {
            echo "The script requires \"owner/repo\" and \"branch\" as arguments\n";
        }
    } elseif(isset($argv[2])) {
        ApiByCUrl($argv[1], $argv[2]);
    } else {
        echo "The script requires \"owner/repo\" and \"branch\" as arguments\n";
    }
}
else {
    echo "No arguments passed\n";
    echo "Specify \"--service=xxx\" as the first argument (not required)\n";
    echo "Specify \"owner/repo\" as the second argument\n";
    echo "Specify \"branch\" as the third argument\n";
}

function ApiByCUrl($owner_repo, $branch)
{
    $ch = curl_init();
    $url = sprintf("https://api.github.com/repos/%s/commits/%s", $owner_repo, $branch);

    $http_header = array(
        'User-Agent: Secret-User'
    );

    $optArray = array(
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $http_header,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $response = curl_exec($ch);
    $j_response = json_decode($response);
    if (isset($j_response->message)) {
        echo $j_response->message."\n";
    } elseif (isset($j_response->sha)) {
        echo $j_response->sha."\n";
    } else {
        echo "Connection error";
    }
    curl_close($ch);
}



