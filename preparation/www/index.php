<?php
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

$results = array();

if (in_array('tours',$params)) {
	$results = array( 
		array('name' => 'Test1'), 
		array('name' => 'Test2') 
	) ;
}
else if (in_array('tour', $params)) {
	
}

if (in_array('json', $params) || array_key_exists('CONTENT_TYPE', $_SERVER) && $_SERVER['CONTENT_TYPE'] == 'application/json') { 
	print json_encode( $results );
	return;
}

// The viewer/debug website
$display_results = '';
foreach($results as $msg) $display_results .= "<p>". var_export($msg,true) . "</p>\n";

$debug[] = "\nEnvironment variables:";
$debug[] = $_SERVER;
$debug_messages = '';
foreach($debug as $msg) $debug_messages .= var_export($msg, true) . "\n";
print <<<EOD
<html>
<head>
<title>Working Title r1</title>
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
