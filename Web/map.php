<?php 
	// include database connection functions
	include 'functions/db_functions.php';
	
?>	
    <html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title>Google Maps</title>
        <style type="text/css">
            body { font: normal 10pt Helvetica, Arial; }
            #map { width: 900px; height: 600px; border: 0px; padding: 0px; }
        </style>
        <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
        <script type="text/javascript">
            // courtesy of stackOverFlow. to display the map
            var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/red.png",
                       new google.maps.Size(32, 32), 
					   new google.maps.Point(0, 0),
                       new google.maps.Point(16, 32));
            var center = null;
            var map = null;
            var currentPopup;
            var bounds = new google.maps.LatLngBounds();
            function addMarker(lat, lng, info) {
                var pt = new google.maps.LatLng(lat, lng);
                bounds.extend(pt);
                var marker = new google.maps.Marker({
                    position: pt,
                    icon: icon,
                    map: map
                });
                var popup = new google.maps.InfoWindow({
                    content: info,
                    maxWidth: 700
                });
                google.maps.event.addListener(marker, "click", function() {
                    if (currentPopup != null) {
                        currentPopup.close();
                        currentPopup = null;
                    }
                    popup.open(map, marker);
                    currentPopup = popup;
                });
                google.maps.event.addListener(popup, "closeclick", function() {
                    map.panTo(center);
                    currentPopup = null;
                });
            }           
            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: new google.maps.LatLng(0, 0),
                    maxZoom: 18,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
                    },
                    navigationControl: true,
                    navigationControlOptions: {
                        style: google.maps.NavigationControlStyle.ZOOM_PAN
                    }
                });
<?php
				
			
				if($_SERVER['REQUEST_METHOD']=='POST'){
					// read the POSTs
					$cid = $_POST['company'];
					$bid = $_POST['branch'];
					$rid = $_POST['room'];	
					
					// connect to the database
					$conn = db_connect();		
					
					// get company name query
					$cquery = "SELECT company_name cname from company where company_id = '$cid'";			 
				
					if(!$result = $conn->query($cquery)){
						die('There was an error running the query 1 ['. $conn->error .']');
					}else {	
						$row = $result->fetch_assoc();
						$name = $row['cname'];
					}
					// get branch address query
					$bquery = "SELECT branch_address as baddr, longitude as longi, 
								latitude as lat from branch where branch_id = '$bid'";			 
				
					if(!$result = $conn->query($bquery)){
						die('There was an error running the query 2['. $conn->error .']');
					}else {	
						$row = $result->fetch_assoc();
						$lat = $row['lat'];
						$lon = $row['longi'];
						$addr = $row['baddr'];
					}
					// get room number query
					$rquery = "SELECT room_number rnum, max_capacity max, people_in pin, people_out pout from room
								where room_id = '$rid'";			 
				
					if(!$result = $conn->query($rquery)){
						die('There was an error running the query 3 ['. $conn->error .']');
					}else {	
						$row = $result->fetch_assoc();
						$room = $row['rnum'];
						$pin = $row['pin'];
						$pout = $row['pout'];
						$max = $row['max'];
					}
						
						  
			// calculate occupancy			  
			$occupancy = (($pin-$pout)/$max) * 100;
			
			// add marker for javascript
			echo("addMarker($lat, $lon, '<b>$name</b>, $addr<br/>Occupancy = ". substr($occupancy,0,5) ."');\n");
						
			}
	
?>
				center = bounds.getCenter();
				map.fitBounds(bounds);
		
			 }
     </script>
     <?php include 'includes/header.php'; ?>
	</head>
     <body onLoad="initMap()"  style="background-color:#333; width:100%; color:#00C; margin:0px; border:0px; padding:0px; text-align:center; float:center;">
     <div id="map" style="float:inherit; margin-left:200px" align="center"></div>
     
    <?php include 'includes/footer.php'; ?>	
