<?php 

if(isset($_GET['msg']))
{
    echo $_GET['msg'];die();
    $implodedResp = explode("|", $_GET['msg']);

    $hashCodeString = end($implodedResp);
    array_pop($implodedResp);

    $explodedHashValue = explode("=", $hashCodeString);
    $hashValue = trim($explodedHashValue[1]);

    $responseDataString = implode("|", $implodedResp);
    $generatedHash = sha1($responseDataString);

    if ($generatedHash == $hashValue) {
        echo $implodedResp[3].'|'.$implodedResp[5].'|1';
    } else {
        echo $implodedResp[3].'|'.$implodedResp[5].'|0';
    }
}
else
{
    echo "ERROR !!! Invalid Input.";
}

?>