<?php
if( isset($_SERVER['HTTPS'] ) ) {
   $host ='https';
}else{
    $host = 'http';
}
require_once 'TransactionResponseBean.php';

$parameters = file_get_contents("./parameters.json");
$data = json_decode($parameters, true);

$protocolType = 'http';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $protocolType = 'https';
}

if(!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '80'){
    $hostStr = "$protocolType://$_SERVER[SERVER_NAME]$_SERVER[SCRIPT_NAME]";
}else{
    $hostStr = "$protocolType://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]$_SERVER[SCRIPT_NAME]";
}
$resHost = explode('/', $hostStr);
array_pop($resHost);
$resHost = implode('/', $resHost);
?>
<html>
<head>
    <title>Payment Response</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" / />
    <link rel="stylesheet" href="<?php echo $resHost . '/assets/css/bootstrap.min.css';?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $resHost . '/assets/js/bootstrap.min.js';?>"></script>
</head>
<body>
<?php
if ($_POST) {
    if (isset($_POST['msg'])) {
        $response = $_POST;
        if (is_array($response)) {
            $str = $response['msg'];
        } else if (is_string($response) && strstr($response, 'msg=')) {
            $outputstr = str_replace('msg=', '', $response);
            $outputArr = explode('&', $outputstr);
            $str = $outputArr[0];
        } else {
            $str = $response;
        }
        $transactionResponseBean = new TransactionResponseBean();
        $transactionResponseBean->setResponsePayload($str);
        $transactionResponseBean->key = $data['key'];
        $transactionResponseBean->iv = $data['iv'];
        $response = $transactionResponseBean->getResponsePayload();

        //Writing in Response Log
        $log  = "Date : ".date("F j, Y, g:i a")."; Response Data : ".$response.PHP_EOL;

        //Saving string to log by using "FILE_APPEND" to append.
        file_put_contents('logs/response/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);

        $response_n = explode("|", $response);
        //print_r($response_n);
        display_response($response_n,$data);
    } else if (isset($_POST['response'])) {

        //Writing in Response Log
        $log  = "Date : ".date("F j, Y, g:i a")."; Response Data : ".$_POST['response'].PHP_EOL;
        
        //Saving string to log by using "FILE_APPEND" to append.
        file_put_contents('logs/response/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);

        $response_n = explode("|", $_POST['response']);
        display_response($response_n,$data);
    }
} else {
    echo "No Response Received";
}
?>
</body>
</html>
<?php

function display_response($res, $parameters)
{
    $stat = $res[0];
    $mystatus = explode("=", $stat);
    $status = $mystatus[1];

    echo '<div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Response Data</h2>
                    <table class="table table-bordered table-hover">
                        <tr class="info">
                            <th width="40%">Field Name</th>
                            <th width="60%">Value</th>
                        </tr>';
    foreach ($res as $val) {
        $response1 = explode("=", $val);

        if( $response1[0] == 'txn_err_msg' && $status == '0300'){
            continue;
        }

        $data = getdetails($response1[0], $parameters);

        if (!(empty($data))) {
            $colum_name = $data;
        } else {
            continue;
        }

        if (empty($response1[1])) {
            $response1[1] = "Not found";
        }

        echo "<tr>";
        echo "<td> $colum_name </td>";

        if($response1[1] == "0300" || $response1[1] == "success"){
            echo "<td style='background-color: darkseagreen;color: aliceblue;'> $response1[1] </td>";
        }elseif ($response1[1] == "0399" || $response1[1] == "failure") {
            echo "<td style='background-color: red;color: white;'> $response1[1] </td>";
        }else{
            echo "<td> $response1[1] </td>";
        }
        
        echo "</tr>";
    }
    echo "</table>
                </div>
            </div>
        </div>";
}

function getdetails($code, $parameters)
{
    if($parameters['showAllResponse'] == "ON"){        
        $column_value = [
            "txn_status"            => "Transaction Status",
            "txn_msg"               => "Message",
            "txn_err_msg"           => "Error Message",
            "clnt_txn_ref"          => "Transaction ID",
            "tpsl_bank_cd"          => "TPSL Bank Code",
            "tpsl_txn_id"           => "TPSL Transaction ID",
            "txn_amt"               => "Amount",
            "tpsl_txn_time"         => "Transaction Time",
            "tpsl_rfnd_id"          => "TPSL Refund ID",
            "bal_amt"               => "Balance Amount",
            "REFUND_DETAILS"        => "Refund details",
            "rqst_token"            => "Request Token",
            "bank_name"             => "Bank Name",
            "card_id"               => "Card ID",
            "alias_name"            => "Alias Name",
            "card_Type"             => "Card Type",
            "Card_Expiry"           => "Card Expiry",
            "hash"                  => "Hash",
            "BANK_TYPE"             => "Bank Type",
            "auth"                  => "Auth"
        ];
    }
    else{
        $column_value = [
            "txn_status"            => "Transaction Status",
            "txn_msg"               => "Message",
            "txn_err_msg"           => "Error Message",
            "clnt_txn_ref"          => "Transaction ID",
            "tpsl_bank_cd"          => "TPSL Bank Code",
            "tpsl_txn_id"           => "TPSL Transaction ID",
            "txn_amt"               => "Amount",
            "REFUND_DETAILS"        => "Refund details",
            "bank_name"             => "Bank Name"
        ];
    }
    if (in_array($code, array_keys($column_value))) {
        return $column_value[$code];
    }
}

?>
