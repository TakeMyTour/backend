<?php require_once("/usr/local/tmt/etc/tmt.conf");

function find_tours($userdb = null, $search = null) {
	if ( is_null($search) ) {
		require_once("$INC/list_tours");
		return list_tours($userdb);
	}

	global $DBNAME, $DBUSER, $DBPASS;
	global $debug;
	$result = '';
	$db = $userdb;
	try {
	        if (is_null($db)) $db = new PDO("pgsql:host=localhost;port=5432;dbname=$DBNAME;user=$DBUSER;password=$DBPASS");
	}
	catch (PDOException $e) {
	        $result = "Failed to get DB handle: " . $e->getMessage() . "\n";
	}
	if ( !is_null($db)) {

		$where = '';
		$sep = ' ';
		if (array_key_exists('id', $search)) { $where .= "id = " . $search['id']; $sep = ' and '; }
		if (array_key_exists('name', $search)) { $where .= $sep . "name ilike '%" .  $search['name'] . "%'"; $sep = ' and '; }
		if (array_key_exists('description', $search)) { $where .= $sep . "description ilike '%" .  $search['description'] . "%'"; $sep = ' and '; }
		if (array_key_exists('type', $search)) { $where .= $sep . "type ilike '%" .  $search['type'] . "%'"; $sep = ' and '; }
		if (array_key_exists('lat',$search) && array_key_exists('lon',$search)) {
			if (array_key_exists('dist',$search)) 
				$dist = $search['dist'] * 1000;
			else $dist = 3000;
			$where .= $sep 
				. "id in (select distinct tour_id from nodes "
				. "where ST_DWithin(location, ST_GeographyFromText('SRID=4326;POINT(" . $search['lon'] ." " . $search['lat'] .")'), $dist))";
		}

		if ($where != '') $where = 'where ' . $where;
	        $sql ="select distinct id,name,type from tours " . $where ;
		$debug[] = "search: " . $sql;
	        foreach ($db->query($sql) as $row) {
	                $result[] = $row;
	        }
		if (is_null($userdb)) {
			$db = null;
		}
	}

	return $result;
}

