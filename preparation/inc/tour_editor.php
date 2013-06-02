<?php require_once('/usr/local/tmt/etc/tmt.conf');
$debug = array();
//$debug[] = getallheaders();
$debug[] = "request: " . $_SERVER["REQUEST_URI"];
$params = explode('/',$_SERVER["REQUEST_URI"]);

$debug[] = "parameters: " . implode(" ", $params);

// if no params were given, display the workspacepage
if (count($params) < 2 || $params[1] == '') {
	require_once('inc/workspace.php');
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
$my_tours = array();

if (in_array('json', $params) || array_key_exists('CONTENT_TYPE', $_SERVER) && $_SERVER['CONTENT_TYPE'] == 'application/json') { 
	header('Content-Type: application/json');
	print json_encode( $results );
	return;
}

require_once("$INC/my_tours.php");
$results = my_tours();

foreach ($results as $row) {
	foreach ($fld as $k => $v) {
	}
}

$display_my_tours = '<table>';
foreach($my_tours as $tour) {
	$r = '<tr><td>' . $tour['name'] . "</td><td><a href=\"#\">Edit</a></td></tr>\n";
	$display_my_tours .= $r;
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
<title>Make My Tour</title>
<script type="text/javascript" src="/js/xml2json.js"></script>
<style>
.spacer { height:200px; }
</style>
</head>
<body>
<h1>Make my Tour</h1>
<h2>Tours</h2>
$display_my_tours
<h2>Sources</h2>
<ul>
<li><a href="http://data.sa.gov.au/dataset/library-locations">Library Locations</a></li>
<li><a href="http://data.sa.gov.au/dataset/grave-records">Grave Records</a></li>
<li><a href="http://data.sa.gov.au/dataset/park-facilities">Park Facilities</a></li>
<li><a href="http://data.sa.gov.au/dataset/historic-photos-of-adelaide">Historic Photos of Adelaide</a></li>
<li><a href="http://data.sa.gov.au/dataset/tourist-information-for-port-adelaide-enfield-area">Tourist Information for Port Adelaide Enfield</a></li>
<li><a href="http://data.sa.gov.au/dataset/south-australian-photographs">South Australian Photographs</a></li>
</ul>
<div class="spacer"></div>
<div style="display:none">
<h2>Debug</h2>
<pre>
$debug_messages
</pre>
</div>
EOD;

//require_once('info.php');

print <<<EOD
</body>
</html>
EOD;
