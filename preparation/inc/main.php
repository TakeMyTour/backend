<?php require_once('/usr/local/tmt/etc/tmt.conf');
$debug = array();
//$debug[] = getallheaders();
$debug[] = "request: " . $_SERVER["REQUEST_URI"];
$params = explode('/',$_SERVER["REQUEST_URI"]);

$debug[] = "parameters: " . implode(" ", $params);

// if no params were given, display the welcome page
if (count($params) < 2 || $params[1] == '') {
	require_once('inc/welcome.php');
	return;
}


$search=array();
foreach ($params as $p) {
  if (substr($p,0,7) == 'search?') {
     $tags = explode(',', substr($p,7));
     foreach ($tags as $tag) {
	$kv = explode('=',$tag);
	if (count($kv) != 2) break; //TBD bad search string
	$search[ $kv[0] ] = $kv[1];
     }
     $debug[] .= "search: " . var_export($search, true);

     break;
  }
}

$results = array();

// Tours
if (in_array('tours',$params)) {

	if ( count($search) == 0 ) 
	{
		require_once("$INC/list_tours.php");
		$tours = list_tours();
	}
	else
	{
		require_once("$INC/find_tours.php");
		$tours = find_tours(null, $search);
	}


	foreach($tours as $tour) {
		$curr = array();
		$curr['id'] = $tour['id'];
		$curr['name'] = $tour['name'];
		$curr['type'] = $tour['type'];
		$results[] = $curr;
	}

}
else if (in_array('tour', $params)) {
	require_once("$INC/get_tour.php");
	$tour = get_tour(null, $params[2]);
	$results = array();
	foreach($tour as $t) {
		$results['id'] = $t['id'];
		$results['name'] = $t['name'];
		$results['type'] = $t['type'];
		$results['desc'] = $t['description'];
		break;
	}
	$results['nodes'] = array();
	$nodes = get_nodes(null, $params[2]);
	foreach($nodes as $node) {
		$curr = array();
		$curr['id'] = $node['id'];
		$curr['tour_id'] = $node['tour_id'];
		$curr['name'] = $node['name'];
		$curr['desc'] = $node['description'];
		$curr['address'] = $node['address'];
		$curr['lon'] = $node['longitude'];
		$curr['lat'] = $node['latitude'];
		$curr['images'] = array();
		$node_images = get_node_images(null, $node['id']);
		$curr['images'] = array();

		foreach($node_images as $image) {
			$img = array();
			$img['id'] = $image['id'];
			$img['url'] = $image['url'];
			$img['description'] = $image['description'];
			$img['address'] = $image['address'];
			$img['lon'] = $image['longitude'];
			$img['lat'] = $image['latitude'];
			$curr['images'][] = $img;
		}
	
		$results['nodes'][] = $curr;
	}

/*
	if ($params[2] == 1) {
		$dummy_tour_desc = file_get_contents("$INC/tour1_desc.html");
		$results = array( 'id' => 1, 'name' => 'Test1', 'desc' => $dummy_tour_desc, 
				'address' => '182 Victoria Square, Adelaide, SA 5000', 'lat' => '34.927', 'lon' => '138.602', 'images' => array());
	}
	else if ($params[2] == 2) {
		$dummy_tour_desc = file_get_contents("$INC/tour2_desc.html");
		$results = array( 'id' => 2, 'name' => 'Test2', 'desc' => $dummy_tour_desc, 
				'address' => '184 Victoria Square, Adelaide, SA 5000', 'lat' => '34.928', 'lon' => '138.601', 'images' => array());
	}
	else
		$results = array();
*/
}

if (in_array('json', $params) || array_key_exists('CONTENT_TYPE', $_SERVER) && $_SERVER['CONTENT_TYPE'] == 'application/json') { 
	header('Content-Type: application/json');
	print json_encode( $results );
	return;
}

// The viewer/debug website
$display_results = '';
foreach($results as $fld => $val) 
	if (is_array($val))
		$display_results .= "<p>$fld: ". var_export($val,true) . "</p>\n";
	else
		$display_results .= "<p>$fld: ". $val . "</p>\n";

$debug[] = "\nEnvironment variables:";
$debug[] = $_SERVER;
$debug_messages = '';
foreach($debug as $val ) $debug_messages .= var_export($val, true) . "\n";
print <<<EOD
<html>
<head>
<title>Working Title r1</title>
<script type="text/javascript" src="/js/xml2json.js"></script>
<style>
.spacer { height:200px; }
</style>
</head>
<body>
<h2>Results</h2>
$display_results
<div class="spacer"></div>
<h2>Debug</h2>
<pre>
$debug_messages
</pre>
EOD;

//require_once('info.php');

print <<<EOD
</body>
</html>
EOD;
