<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <style type="text/css">
      html { height: 100%; zoom:"200%" }
      body { height: 100%; margin: 0px; padding: 0px; }
      #map_canvas { height: 700px;width: 700px }
    </style>

    <title>test</title>
    <script type="text/javascript">
    $(document).ready(function() {
        var orientation = 0;
		map_scale = 1
    });
    
    function initialize() {
        mapCentre = new google.maps.LatLng(-34.92168953422568,138.5994815826416)
        centre_point = mapCentre;
        var myOptions = {
          zoom: 14,
          center: mapCentre,
          mapTypeId: google.maps.MapTypeId.SATELLITE,
          draggableCursor: 'crosshair'
        }
        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

        var polyOptions = {
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 3
        }
        poly = new google.maps.Polyline(polyOptions);
        poly.setMap(map);

        
        // Add a listener for the click event
        google.maps.event.addListener(map, 'click', addLocation);
        google.maps.event.addListener(poly, 'click', clearPaths);
        google.maps.event.addListener(map, 'mouseout', function() { })

      }
      
    function addLocation(event) {
        var path = poly.getPath();
        var pathSize = path.getLength();
        var dist;
		msg = "location: " + event.latLng.lat() + "," + event.latLng.lng()
		debugMessage(msg);
        var ll = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
        bounds = map.getBounds();
        tl = [bounds.getNorthEast().lat(), bounds.getSouthWest().lng()];
        ll = new google.maps.LatLng((ll.lat()-tl[0]) / map_scale + tl[0], (ll.lng()-tl[1]) / map_scale + tl[1])
        if (pathSize>1) {
            dist = Math.abs(google.maps.geometry.spherical.computeDistanceBetween(path.getAt(pathSize-1), ll));
        }
        else 
            dist = 1.0;
        if (dist>0.1) {
        	var path = poly.getPath();
        	//path.push(ll);
        }
     }
    
    function clearPaths() {
        var path = poly.getPath();
        while (path.getLength()) {
            path.pop();
        }
    }

    function loadScript() {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://maps.google.com/maps/api/js?sensor=false&libraries=geometry&callback=initialize";
        document.body.appendChild(script);
    }

    // find the geometrical centre of the selected polygon
    function findCentre() {
        var path = poly.getPath();
        var pathSize = path.getLength();
        var minLat = 1000, maxLat = -1000, minLng = 1000, maxLng = -1000;
        if (pathSize < 5) return;
        for (var i=0; i<pathSize; i++) {
            lat = path.getAt(i).lat();
            lng = path.getAt(i).lng();
            if (lat < minLat) minLat = lat;
            if (lat > maxLat) maxLat = lat;
            if (lng < minLng) minLng = lng;
            if (lng > maxLng) maxLng = lng;
            centre_point = new google.maps.LatLng( (minLat + maxLat) / 2.0, (minLng + maxLng) / 2.0 );
        }
    }
    
    function debugMessage(msg) {
			$("#debug_messages").html("<div>" + msg + "</div>");
    }
    
    window.onload = function() {
        document.getElementById("debug_data").style.display="block";
        loadScript();
    }
    </script>
</head>
<body id="test" style="background-color:#666;">
	<div style="width:100%">
    <div style="width:700px;height:700px;margin:auto;">
    <div id="map_canvas"></div>
    <div id="debug_data" style="color:white;margin-top:20px"><div id="debug_messages"></div> </div>
	</div>
	</div>
</body>
</html>
