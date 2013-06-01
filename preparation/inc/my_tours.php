<?php require_once("/usr/local/tmt/etc/tmt.conf");

function my_tours($userdb = null, $user_id = null) {
	global $DBNAME, $DBUSER, $DBPASS;
	$result = '';
	$db = $userdb;
	try {
	        if (is_null($db)) $db = new PDO("pgsql:host=localhost;port=5432;dbname=$DBNAME;user=$DBUSER;password=$DBPASS");
	}
	catch (PDOException $e) {
	        $result = "Failed to get DB handle: " . $e->getMessage() . "\n";
	}
	if ( !is_null($db)) {
	        $sql ="select id,name,type from user_tours where user_id=1"; //tbd
	        foreach ($db->query($sql) as $row) {
	                $result[] = $row;
	        }
		if (is_null($userdb)) {
			$db = null;
		}
	}

	return $result;
}

