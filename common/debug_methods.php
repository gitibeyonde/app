<?php

function runCommand($cmd){
    echo $cmd.' &>/tmp/result'."\n";
    echo 'curl --insecure --upload-file /tmp/result --url https://ping.ibeyonde.com/api/debug_output.php'."\n";
}


function getGitStatus($cmd){
    echo 'git --git-dir=/root/iot/.git --work-tree=/root/iot status &>/tmp/result'."\n";
    echo 'curl --insecure --upload-file /tmp/result --url https://ping.ibeyonde.com/api/debug_output.php'."\n";
}


function getFolderListing($folder){
    echo 'ls ' .$folder.' &>/tmp/result'."\n";
    echo 'curl --insecure --upload-file /tmp/result --url https://ping.ibeyonde.com/api/debug_output.php'."\n";
}

function getFile($file){
    echo "curl -k --header 'Expect:' --form 't=LOCPbQtTIc' --form 'fileToUpload=@" . $file .";' 'https://ping.ibeyonde.com/api/motionAlert.php?tz=Asia/Calcutta&hn=8e25abf4&tp=MOTION&grid=T' -o /tmp/result"."\n";
    echo "curl -k --upload-file /tmp/result --url https://ping.ibeyonde.com/api/debug_output.php"."\n";
}



function replaceInFile($file, $current, $replaced){
    //sed -i -e 's/^#interface=.*/interface=wlan0/1'  /etc/dnsmasq.conf
    echo "curl -k --header 'Expect:' --form 't=LOCPbQtTIc' --form 'fileToUpload=@" . $file .";' 'https://ping.ibeyonde.com/api/motionAlert.php?tz=Asia/Calcutta&hn=8e25abf4&tp=MOTION&grid=T' -o /tmp/result"."\n";
    echo "curl -k --upload-file /tmp/result --url https://ping.ibeyonde.com/api/debug_output.php"."\n";
}