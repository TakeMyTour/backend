<?php require_once("/usr/local/tmt/etc/tmt.conf");
require_once("$INC/list_tours.php");
$result='';
$result = list_tours();
$display_result = var_export($result, true);

print <<<EOD
<html>
<body>
<p>result: $display_result</p>
</body>
</html>
EOD;
