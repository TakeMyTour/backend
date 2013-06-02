<?php require_once("/usr/local/tmt/etc/tmt.conf");

function explore($userdb = null, $search = null) {
	if ( is_null($search) ) {
		return array();
	}

	global $DBNAME, $DBUSER, $DBPASS;
	global $debug;
	$result = array();
	$db = $userdb;
	try {
	        if (is_null($db)) $db = new PDO("pgsql:host=localhost;port=5432;dbname=$DBNAME;user=$DBUSER;password=$DBPASS");
	}
	catch (PDOException $e) {
	        $result[] = "Failed to get DB handle: " . $e->getMessage() . "\n";
	}
	if ( !is_null($db)) {

		$where = '';
		$sep = ' ';
		if (array_key_exists('feature', $search)) { $where = $sep . "category ilike '%" . $search['category']; $sep = "%' and "; }
		if (array_key_exists('name', $search)) { $where .= $sep . "name ilike '%" .  $search['name'] . "%'"; $sep = ' and '; }
		if (array_key_exists('description', $search)) { $where .= $sep . "description ilike '%" .  $search['description'] . "%'"; $sep = ' and '; }
		if (array_key_exists('lat',$search) && array_key_exists('lon',$search)) {
			if (array_key_exists('dist',$search)) 
				$dist = $search['dist'] * 1000;
			else $dist = 1000;
			$where .= $sep 
				. "ST_DWithin(location, ST_GeographyFromText('SRID=4326;POINT(" . $search['lon'] ." " . $search['lat'] .")'), $dist)";
		}

		if ($where != '') $where = 'where ' . $where;
	        $sql ="select distinct id,name,category,description,address from exploration " . $where;
		$debug[] = "search: " . $sql;
		try {
			$rows = $db->query($sql);
	        	if ($rows !== false) foreach ($rows as $row) {
	        	        $result[] = $row;
	        	}
		}
		catch (PDOException $e) {
		        $result[] = "sql failed: " . $e->getMessage() . "\n";
		}
		if (is_null($userdb)) {
			$db = null;
		}
	}

	return $result;
}

