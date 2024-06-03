<?php

//print_r($_POST);
$newdate = date("j.n.Y", strtotime($_POST['date']));
//echo $newdate;die();
if($_POST['type'] == "response")
{
	$file = 'logs/response/log_'.$newdate.'.log';
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

		$html = '<table class="table table-hover table-bordered table-striped mytable">
					    <thead>
					      	<tr>
					        	<th>Sr. No</th>
					        	<th>Date</th>
					        	<th>Log Data</th>
					      	</tr>
					    </thead>
					    <tbody>';
		$j = 1; 
		foreach ($log_data as $logs) {
			$html .= '<tr>
		        <td>'.$j.'</td>
		        <td>'.$logs['Date'].'</td>
		        <td>'.$logs['Response Data'].'</td>
		    </tr>';
		    $j++;
		}

		$html .= '</tbody>
				  	</table>';
		echo $html;die();
	}
	else{
		$html = '<h2 class="mytable">No logs data..</h2>';
		echo $html;die();
	}
}else{
	$file = 'logs/request/log_'.$newdate.'.log';
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

		$html = '<table class="table table-hover table-bordered table-striped mytable">
					    <thead>
					      	<tr>
					        	<th>Sr. No</th>
					        	<th>Date</th>
					        	<th>Name</th>
					        	<th>Log Data</th>
					      	</tr>
					    </thead>
					    <tbody>';
		$j = 1; 
		foreach ($log_data as $logs) {
			$html .= '<tr>
		        <td>'.$j.'</td>
		        <td>'.$logs['Date'].'</td>
		        <td>'.$logs['Name'].'</td>
		        <td>'.$logs['Request Data'].'</td>
		    </tr>';
		    $j++;
		}

		$html .= '</tbody>
				  	</table>';
		echo $html;die();
	}
	else{
		$html = '<h2 class="mytable">No logs data..</h2>';
		echo $html;die();
	}
}

?>
