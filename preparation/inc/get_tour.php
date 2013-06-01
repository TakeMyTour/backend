<?php require_once("/usr/local/tmt/etc/tmt.conf");

function get_nodes($userdb = null, $tour_id) {
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
	        $sql ="select id,tour_id,name,description,address,longitude,latitude from nodes where tour_id = " . $tour_id;
	        foreach ($db->query($sql) as $row) {
	                $result[] = $row;
	        }
		if (is_null($userdb)) {
			$db = null;
		}
	}

	return $result;
}

function get_tour($userdb = null, $tour_id) {
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
	        $sql ="select id,name,type,description from tours where id = " . $tour_id;
	        foreach ($db->query($sql) as $row) {
	                $result[] = $row;
	        }
		if (is_null($userdb)) {
			$db = null;
		}
	}

	return $result;
}

function get_node_images($userdb = null, $node_id) {
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
	        $sql ="select id,url,description,longitude,latitude from images i, images_nodes n where i.id=n.image_id and n.node_id = " . $node_id;
	        foreach ($db->query($sql) as $row) {
	                $result[] = $row;
	        }
		if (is_null($userdb)) {
			$db = null;
		}
	}

	return $result;
}

