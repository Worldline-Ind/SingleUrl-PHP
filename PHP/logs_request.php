<?php
ob_start();
error_reporting(E_ALL);

date_default_timezone_set('Asia/Calcutta');
$strCurDate = date('Y-m-d');

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
$resHostNew =  implode('/', $resHostNew);

$file = 'logs/request/log_'.date("j.n.Y").'.log';
if(file_exists($file)){
	$fn = fopen($file,"r");  
	while(! feof($fn))
	{
		$result = fgets($fn);
		$data[] = $result;
	}
	array_pop($data);
	fclose($fn);

	$log_data = array();
	$i=0;
	foreach ($data as $key => $value) {
		$log = explode("; ", $value);
		foreach ($log as $k => $v) {
			$details = explode(" : ", $v);
			$log_data[$i][$details[0]]=$details[1];
		}
		$i++;
	}
}
else{
	$log_data = array();
}

?>

<html>
<head>
    <title>Request Logs</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" / />
    <link rel="stylesheet" href="<?php echo $resHostNew .'/assets/css/bootstrap.min.css';?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $resHostNew . '/assets/js/bootstrap.min.js';?>"></script>
</head>
<body>
    <div class="container">
    	<div class="row">
			<h2>Request Logs</h2>
			<div class="col-md-12">
				<form>
					<div class="form-group">
					    <label for="date">Select Log Date :</label>
					    <input type="date" class="form-control" name="date" id="date" required >
					    <input type="hidden" class="form-control" name="type" value="request">
					</div>
					<button type="submit" class="btn btn-default">Search Log</button>
				</form>
    		</div>
    		<div id="search_result">
    			
    		</div>
			<?php if(isset($log_data) && !empty($log_data)){ ?>

				<table class="table table-hover table-bordered table-striped mytable">
				    <thead>
				      	<tr>
				        	<th>Sr. No</th>
				        	<th>Date</th>
				        	<th>Name</th>
				        	<th>Log Data</th>
				      	</tr>
				    </thead>
				    <tbody>
				    <?php $i = 1; foreach ($log_data as $logs) { ?>
					    <tr>
					        <td><?php echo $i; ?></td>
					        <td><?php echo $logs['Date']; ?></td>
					        <td><?php echo $logs['Name']; ?></td>
					        <td><?php echo $logs['Request Data']; ?></td>
					    </tr>
					<?php $i++; } ?>
				    </tbody>
			  	</table>

			<?php }else{ ?>

				<h2 class="mytable">No logs data..</h2>	

			<?php } ?>
       	</div>
	</div>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

    $(function () {
        $('form').on('submit', function (e) {
          	e.preventDefault();
	        $.ajax({
	            type: 'post',
	            url: 'process_log.php',
	            data: $('form').serialize(),
	            dataType: 'html',
	            success: function (response) {
	              	//alert('form was submitted');
	              	$('.mytable').hide();
	              	$('#search_result').html('');
	              	$('#search_result').html(response);
	            }
	        });
        });
    });
</script>

</html>