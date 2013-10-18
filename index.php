<?php	
	require 'Slim/Slim/Slim.php';
	\Slim\Slim::registerAutoloader();
	
	$app = new \Slim\Slim();
	
	$app->get('/', function () use ($app){
		$road = getTrafficStateArray();
		include('home.php');
	});

	$app->post('/traffic/:ranges', function ($rangeValues) use ($app) {
    	updateTraffic($rangeValues,1);
		$response = $app->response();
		$response->header('Content-Type', 'application/json');
    	$response->write(json_encode(getTrafficStateArray()));	
	});
	
	$app->delete('/traffic/:ranges', function ($rangeValues) use ($app) {	
    	updateTraffic($rangeValues,0);
		$response = $app->response();
		$response->header('Content-Type', 'application/json');
    	$response->write(json_encode(getTrafficStateArray()));
	});
	
	$app->run();
	
	function updateTraffic($rangeValues, $traffic) {
		$dbconn = pg_connect("host=localhost port=5432 dbname=appleProj user=marcrossi");
		$ranges = split(',', $rangeValues);
		foreach ($ranges as $range) {
			$section = split('-', $range);
			for($i=$section[0]; $i<=$section[1]; $i++) {
				$query = "UPDATE road SET traffic=".$traffic."WHERE segment=".$i.";";
				pg_query($dbconn, $query);
			}
		}	
		pg_close($dbconn);
	}
	
	function getTrafficStateArray() {
		$dbconn = pg_connect("host=localhost port=5432 dbname=appleProj user=marcrossi");
		$road = array();
    	$result = pg_query($dbconn, "SELECT * FROM road ORDER BY segment ASC");
		if (!$result) {
  			echo "An error occurred.\n";
  		exit;
		}
		while ($row = pg_fetch_row($result)) {
		 	$road[] = $row;
		}
		pg_close($dbconn);
		return $road;
	}
?>