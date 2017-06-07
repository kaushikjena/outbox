<?php 
	ob_start();
	session_start();
	include_once '../includes/class.Main.php';
	//Object initialization
	$dbf = new User();
	//page titlevariable
	$pageTitle="Welcome To Out Of The Box";
	include 'applicationtop-tech.php';
	if($_SESSION['usertype']!='tech'){
		header("location:../logout");exit;
	}
	//for address used in gmap
	$pickup_zip_code = $dbf->getDataFromTable("assign_tech at,work_order wo","pickup_zip_code"," at.wo_no=wo.wo_no  AND at.tech_id='$_SESSION[userid]' AND at.start_date=CURDATE()");
	$site_url = $dbf->getDataFromTable("admin","site_url","id=1");
?>
<body>
<link rel="stylesheet" href="../css/innermain.css" type="text/css" />
<link rel="stylesheet" href="../css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="../css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="../css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="../css/tablejob.css" type="text/css" />
<!-- Requied for Calendar --->
<link rel='stylesheet' href='../fullcalendar-1.6.4/demos/cupertino/jquery-ui.min.css' />
<link href='../fullcalendar-1.6.4/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='../fullcalendar-1.6.4/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='../fullcalendar-1.6.4/lib/jquery.min.js'></script>
<script src='../fullcalendar-1.6.4/lib/jquery-ui.custom.min.js'></script>
<script src='../fullcalendar-1.6.4/fullcalendar/fullcalendar.min.js'></script>
<!-- Requied for Calendar --->
<script type="text/javascript">
	$(document).ready(function() {
		$('#calendar').fullCalendar({
			theme: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: true,
			events: [<?php 
				$resArray = $dbf->fetchOrder("assign_tech at,clients c,work_order wo"," at.wo_no=wo.wo_no AND wo.client_id=c.id AND at.tech_id='$_SESSION[userid]'","","at.start_date,at.start_time,wo.id,at.wo_no,c.name,c.city");
				foreach($resArray as $resevent) {
					//$eventdt= date("D M d Y H:i:s \G\M\TO (T)",strtotime($resevent['start_date']));
					$eventdt= date("D M d Y ",strtotime($resevent['start_date']));
					if($resevent['start_time']<>''){$eventdt.= date("H:i:s \G\M\TO (T)",strtotime($resevent['start_time']));}
					$linkhref= $site_url."/tech/tech-view-job-board?id=$resevent[id]&src=cal"; $target = "_self";
					$string =$resevent['wo_no'].'\n '.$resevent['name'].'\n '.$resevent['city'];
					echo "{ title:'".$string."', start:'".$eventdt."', url:'".$linkhref."',allDay: false},";
				}
			?>]
		});
	});
</script>
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header-tech.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'tech-top-menu.php';?>
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
                                <div id="did"  class="divMap">
                                <iframe id="map_canvas" width="100%" height="536" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $pickup_zip_code; ?>&amp;output=embed"></iframe>
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
        <?php include_once 'footer-tech.php'; ?>
    </div>
</body>
</html>