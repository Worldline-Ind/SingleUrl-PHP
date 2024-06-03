<?php
ob_start();
error_reporting(E_ALL);
$strNo = rand(1, 1000000);

date_default_timezone_set('Asia/Calcutta');

$strCurDate = date('Y-m-d');

require_once 'TransactionRequestBean.php';

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
$resHostNew = $resHost;
array_push($resHost, 'response.php');
$resUrl = implode('/', $resHost);

if ($_POST && isset($_POST['submit'])) {
    $val = $_POST;
    $transactionRequestBean = new TransactionRequestBean();
    //Setting all values here
    $transactionRequestBean->merchantCode = $val['mrctCode'];
    $transactionRequestBean->ITC = $val['itc'];
    $transactionRequestBean->customerName = $val['custname'];
    $transactionRequestBean->requestType = $val['reqType'];
    $transactionRequestBean->merchantTxnRefNumber = $val['mrctTxtID'];
    $transactionRequestBean->amount = $val['amount'];
    $transactionRequestBean->currencyCode = $val['currencyType'];
    $transactionRequestBean->returnURL = $val['returnURL'];
    $transactionRequestBean->shoppingCartDetails = $val['reqDetail'];
    $transactionRequestBean->TPSLTxnID = $val['tpsl_txn_id'];
    $transactionRequestBean->mobileNumber = $val['mobile'];
    $transactionRequestBean->txnDate = $val['txnDate'];
    $transactionRequestBean->bankCode = $val['bankCode'];
    $transactionRequestBean->custId = $val['custID'];
    $transactionRequestBean->key = $data['key'];
    $transactionRequestBean->iv = $data['iv'];
    $transactionRequestBean->accountNo = $val['accNo'];
    $transactionRequestBean->webServiceLocator = $val['locatorURL'];
    $transactionRequestBean->hashAlgo = $val['hashAlgo'];
    $transactionRequestBean->timeOut = (!empty($val['timeOut']) ? $val['timeOut'] : 30);

    //Writing in Request Log
    $log  = "Name : ".$transactionRequestBean->customerName."; Date : ".date("F j, Y, g:i a")."; Request Data : ".$transactionRequestBean->merchantCode."|".$transactionRequestBean->ITC."|".$transactionRequestBean->customerName."|".$transactionRequestBean->requestType."|".$transactionRequestBean->merchantTxnRefNumber."|".$transactionRequestBean->amount."|".$transactionRequestBean->currencyCode."|".$transactionRequestBean->returnURL."|".$transactionRequestBean->shoppingCartDetails."|".$transactionRequestBean->TPSLTxnID."|".$transactionRequestBean->mobileNumber."|".$transactionRequestBean->txnDate."|".$transactionRequestBean->bankCode."|".$transactionRequestBean->custId."|".$transactionRequestBean->key."|".$transactionRequestBean->iv."|".$transactionRequestBean->accountNo."|".$transactionRequestBean->webServiceLocator.PHP_EOL;
    
    //Saving string to log by using "FILE_APPEND" to append.
    file_put_contents('logs/request/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);

    $responseDetails = $transactionRequestBean->getTransactionToken();
    $responseDetails = (array)$responseDetails;
    $response = $responseDetails[0];
    // echo $response;die;
    echo "<script>window.location = '" . $response . "'</script>";
    ob_flush();
}
$resHostNew =  implode('/', $resHostNew);
?>

<html>
<head>
    <title>Payment Checkout</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" / />
    <link rel="stylesheet" href="<?php echo $resHostNew .'/assets/css/bootstrap.min.css';?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $resHostNew . '/assets/js/bootstrap.min.js';?>"></script>
</head>
<body>
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Payment Details</h2>
            <div class="alert alert-info">
                <strong style="color:red">Important Note :</strong> 1) Input fields marked by <span style="color:red">*</span> are mandatory. <br>2) Before testing the Payment Gateway make sure integration is done properly and none of the mandatory fields are empty <strong style="color:red">For eg :- Amount, Return URL etc</strong>. <br> 3) Live Credentials are to be inserted only after successful testing of Payment Gateway integration.
            </div>
            <form method="post">
                <table class="table table-bordered table-hover" >
                    <tr class="info">
                        <th width="40%">Field Description</th>
                        <th width="60%">Field Name</th>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Request Type <a href="#" data-toggle="tooltip" title="Type of request sent to Ingenico side"><span class="glyphicon glyphicon-info-sign"></span></a></label></td>
                        <td><input type="text" value="T" name="reqType" required />
                        </td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Merchant Code <a href="#" data-toggle="tooltip" title="Merchant Code provided by Ingenico"><span class="glyphicon glyphicon-info-sign"></span></a></label></td>
                        <td><input type="text" name="mrctCode" value="<?php echo $data['merchantCode']; ?>" required /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Merchant Transaction ID <a href="#" data-toggle="tooltip" title="Unique Transaction ID generated from merchant side"><span class="glyphicon glyphicon-info-sign"></span></a></label></td>
                        <td><input type="text" name="mrctTxtID" value="<?php echo $strNo; ?>" required /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Currency Code <a href="#" data-toggle="tooltip" title="Currency Code provided by merchant. For eg - INR, USD etc"><span class="glyphicon glyphicon-info-sign"></span></a></label></td>
                        <td><input type="text" name="currencyType" value="INR" required /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Amount <a href="#" data-toggle="tooltip" title="Amount to be processed"><span class="glyphicon glyphicon-info-sign"></span></a></label></td>
                        <td><input type="text" name="amount" id="amount" onchange="change_scheme_code()" value="1.00" required /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Client Meta Data</label></td>
                        <td><input type="text" name="itc" value="email:demo@demo.com" /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Scheme Code Details <a href="#" data-toggle="tooltip" title="Unique Request Detail i.e. combination of Scheme Code (provided by Ingenico) & Amount sent to Ingenico side from merchant"><span class="glyphicon glyphicon-info-sign"></span></a></label></td>
                        <td><input type="text" name="reqDetail" id="reqDetail" onchange="change_scheme_code()" value="<?php echo $data['schemeCode']; ?>_1.0_0.0" required /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Transaction Date</label></td>
                        <td><input type="date" name="txnDate" id="txnDate" value="<?php echo $strCurDate; ?>" /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Bank Code</label></td>
                        <td><input type="text" name="bankCode" value="470" /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Locator URL</label></td>
                        <td><select name="locatorURL">
                                <option selected value="https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl">LIVE</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Hashing Algorithm</label></td>
                        <td><select name="hashAlgo">
                                <option selected value="SHA3-512">SHA3-512</option>
                                <option value="SHA3-256">SHA3-256</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>TPSL Transaction ID</label></td>
                        <!-- <td><input type="text" name="tpsl_txn_id" value="echo 'TXN00' . rand(1, 10000)" /></td> -->
                        <td><input type="text" name="tpsl_txn_id" value="" /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red"></span> Customer ID</label></td>
                        <td><input type="text" name="custID" value="19872627" /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Card Name</label></td>
                        <td><input type="text" name="custname" value="test" /></td>
                    </tr>
                    <tr>
                        <td><label>Mobile Number</label></td>
                        <td><input type="text" name="mobile" value="" /></td>
                    </tr>
                    <tr>
                        <td><label>Account No</label></td>
                        <td><input type="text" name="accNo" value="" /></td>
                    </tr>
                    <tr>
                        <td><label><span style="color:red">*</span> Return URL <a href="#" data-toggle="tooltip" title="Return URL provided by merchant to fetch response from Ingenico "><span class="glyphicon glyphicon-info-sign"></span></a></label></td>
                        <td>
                            <input type="text" name="returnURL" value='<?php echo $resUrl; ?>' required />
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <input type="submit" name="submit" value="Submit" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
    
    function change_scheme_code() {
        var amount = document.getElementById('amount').value;
        let parseval = parseFloat(amount);
        let fixValue = parseval.toFixed(2);
        document.getElementById('amount').value = fixValue;
        var scheme_code = "<?php echo $data['schemeCode']; ?>_" + fixValue + "_0.0";
        document.getElementById("reqDetail").value = scheme_code;
    }
</script>

</html>