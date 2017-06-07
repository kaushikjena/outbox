<?php 
	ob_start();
	session_start();
	include_once 'includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop.php';
	if($_SESSION['userid']==''){
		header("location:logout");exit;
	}
	//for address used in gmap
	$pickup_zip_code = $dbf->getDataFromTable("assign_tech at,work_order wo","pickup_zip_code"," at.wo_no=wo.wo_no  AND wo.work_status='Assigned' AND at.start_date=CURDATE()");//exit;
	//echo $today = date("D M j Y G:i:s T O");
	$site_url = $dbf->getDataFromTable("admin","site_url","id=1");
?>
<body>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<!-- Requied for Calendar --->
<link rel="stylesheet" href="fullcalendar-1.6.4/demos/cupertino/jquery-ui.min.css"/>
<link href="fullcalendar-1.6.4/fullcalendar/fullcalendar.css" rel="stylesheet" />
<link href="fullcalendar-1.6.4/fullcalendar/fullcalendar.print.css" rel="stylesheet" media="print"/>
<script src="fullcalendar-1.6.4/lib/jquery.min.js"></script>
<script src="fullcalendar-1.6.4/lib/jquery-ui.custom.min.js"></script>
<script src="fullcalendar-1.6.4/fullcalendar/fullcalendar.min.js"></script>
<!-- Requied for Calendar --->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<style type="text/css">
	#googlemap img{ max-width: none;}
</style>
<!-- Requied for Map --->
   <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg" style="height:40px;"><div style="float:left;">Jobs Calendar</div>
                        </div>
                        <div class="spacer"></div>
                        	<div id="contenttable">
                            	<!----map section start----->
                                <div id="did" class="divMap">
                                	<div id="googlemap" style="width: 100%; height: 565px;"></div>
                                </div>
                                <!----map section start----->
                                <!----calender section start----->
                                <div class="divCalendar">
                                    <div id='calendar'></div>
                                </div>
                              <!----calender section end----->
                          </div>
                        <div class="spacer"></div>
                    </div>
            	</div>
              <!-------------Main Body--------------->
         </div>
        <div class="spacer"></div>
        <?php include_once 'footer.php'; ?>
    </div>
</body>
</html>
<?php
$cond="at.wo_no=wo.wo_no AND wo.client_id=c.id"; 
//condition for users
if($implode_clients <>''){
	$cond.=" AND FIND_IN_SET(wo.created_by,'$implode_clients')";
}

?>
<!-- Requied for Map --->
<script type="text/javascript">
    var markers = [];
	var map;
    function initialize() {        
		var mapOptions = {
			zoom: 10,
			//center: new google.maps.LatLng(40.714364, -74.005972),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		 map = new google.maps.Map(document.getElementById("googlemap"), mapOptions);
		 
		var locations = [<?php
			$resArrayMap = $dbf->fetchOrder("assign_tech at,clients c,work_order wo",$cond,"id","at.start_date,at.start_time,wo.id,at.wo_no,c.name,c.address,c.city,c.latitude,c.longitude");
			foreach($resArrayMap as $resmap) {
				$string = '<b><u>'.$resmap['wo_no'].'</u></b><br/> '.addslashes($resmap['name']).'<br/> '.addslashes($resmap['city']);
				echo "['".$string."',".$resmap['latitude'].",".$resmap['longitude'].",'images/green-dot.png','".$resmap['wo_no']."'],";
			}
		?>]; 
		//alert(locations);
		var marker, i;
		var infowindow = new google.maps.InfoWindow();        
		google.maps.event.addListener(map, 'click', function() {												
			infowindow.close();				
		});  			      
		for (i = 0; i < locations.length; i++) {
			map.setCenter(new google.maps.LatLng(locations[i][1], locations[i][2])); 
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				map: map,
				icon: locations[i][3],
				title:locations[i][4]
			});    
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(locations[i][0]);
					infowindow.open(map, marker);						
				}
			})(marker, i));        
			markers.push(marker);
		}   
		//clearMarkers();  //this function is used to clear marker initially
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	
	function myClick(id){
		showMarkers();
		google.maps.event.trigger(markers[id], 'click');
	}
	//Extra function from google map
	// Add a marker to the map and push to the array.
	function addMarker(location) {
	  var marker = new google.maps.Marker({
		position: location,
		map: map
	  });
	  markers.push(marker);
	}
	
	// Sets the map on all markers in the array.
	function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	  }
	}
	
	// Removes the markers from the map, but keeps them in the array.
	function clearMarkers() {
	  setAllMap(null);
	}
	
	// Shows any markers currently in the array.
	function showMarkers() {
	  setAllMap(map);
	}
	
	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
	  clearMarkers();
	  markers = [];
	}

</script>
<!-- Requied for Map --->
<!-- Requied for Calender --->
<script type="text/javascript">
	$(document).ready(function() {
		/*var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();*/
		//alert(new Date(y, m, d, 16, 0));
		//alert(new Date(y, m, d));
		$('#calendar').fullCalendar({
			theme: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: true,
			events: [<?php
				$i=0; 
				$resArray = $dbf->fetchOrder("assign_tech at,clients c,work_order wo",$cond,"id","at.start_date,at.start_time,wo.id,at.wo_no,c.name,c.city");
				foreach($resArray as $resevent) { 
					//$eventdt= date("D M d Y H:i:s \G\M\TO (T)",strtotime($resevent['start_date']));
					$eventdt= date("D M d Y ",strtotime($resevent['start_date']));
					if($resevent['start_time']<>''){$eventdt.= date("H:i:s \G\M\TO (T)",strtotime($resevent['start_time']));}
					$linkhref= $site_url."/view-job-board?id=$resevent[id]&src=cal"; $target = "_self";
					$string =$resevent['wo_no'].'\n '.addslashes($resevent['name']).'\n '.addslashes($resevent['city']);
					echo "{id:'".$i."', title:'".$string."', start:'".$eventdt."', url:'".$linkhref."',allDay: false},";
					$i++;
				}
			?>],
			 eventMouseover: function(event, jsEvent, view){
					myClick(event.id);//call the google map function
			},
			/*viewRender: function(view, element) {
				$('#calendar').fullCalendar( 'gotoDate', date.getDate());
			 }*/
		});
	});
</script>
<!-- Requied for Calender --->
