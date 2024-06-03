<?php
if ($_POST) {
    $response = explode("|", $_POST['response']);
    echo '<table class="tbl" width="40%" border="1" cellpadding="2" cellspacing="0">';
    foreach ($response as $val) {
        $response1 = explode("=", $val);
        $data = getdetails($response1[0]);
        if (!empty($data)) {
            $colum_name = $data;
        } else {
            $colum_name = $response1[0];
        }
        echo "<tr>";
        echo "<td> $colum_name </td>";
        echo "<td> $response1[1] </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No Response Received";
}

function getdetails($code)
{
    $column_value = [
        "txn_status"         => "Transaction Status",
        "txn_msg"             => "Message",
        "txn_err_msg"         => "Error Message",
        "clnt_txn_ref"         => "Transaction ID",
        "tpsl_bank_cd"         => "TPSL Bank Code",
        "tpsl_txn_id"         => "TPSL Transaction ID",
        "txn_amt"             => "Amount",
        "tpsl_txn_time"     => "Transaction Time",
        "tpsl_rfnd_id"         => "TPSL Refund ID",
        "bal_amt"             => "Balance Amount",
        "rqst_token" => "Request Token",
        "bank_name" => "Bank Name",
        "card_id" => "Card ID",
        "card_Type" => "Card Type",
        "Card_Expiry" => "Card Expiry",
        "MandateId" => "Mandate ID",
        "BankTransactionID" => "Bank Transaction ID",
    ];
    if (in_array($code, array_keys($column_value))) {
        return $column_value[$code];
    }
    return null;
}
