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
	$pickup_zip_code = $dbf->getDataFromTable("assign_tech at,work_order wo","pickup_zip_code"," at.wo_no=wo.wo_no  AND wo.job_status='Assigned' AND at.start_date=CURDATE()");//exit;
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/tablejob.css" type="text/css" />
<!-- Requied for Calendar --->
<link rel="stylesheet" type="text/css" href="css/calendar.css" />
<link rel="stylesheet" type="text/css" href="css/custom_2.css" />
<link rel="stylesheet" type="text/css" href="css/demo.css" />
<script src="js/modernizr.custom.63321.js"></script>
<!-- Requied for Calendar --->
<style type="text/css">
*,
*:after,
*:before {
	-webkit-box-sizing: border-box;	
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 0;
	margin: 0;
}	
</style>
<body>
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
                                <div id="did" style="width:44%;float:left; border:solid 1px #999;">
                                <iframe id="map_canvas" width="100%" height="536" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $pickup_zip_code; ?>&amp;output=embed"></iframe>
                                </div>
                                <!----map section start----->
                                <!----calender section start----->
                                <div style="float:right;width:54%;border:solid 1px #999;">
                                     <section class="main">
                                        <div class="custom-calendar-wrap">
                                           <div id="custom-inner" class="custom-inner">
                                                <div class="custom-header clearfix">
                                                    <nav>
                                                      <span id="custom-prev" class="custom-prev"></span>
                                                      <span id="custom-next" class="custom-next"></span>
                                                    </nav>
                                                    <h2 id="custom-month" class="custom-month"></h2>
                                                    <h3 id="custom-year" class="custom-year"></h3>
                                                </div>
                                               <div id="calendar" class="fc-calendar-container"></div>
                                            </div>
                                         </div>
                                      </section>
                                    <script type="text/javascript" src="js/jquery.calendario.js"></script>
                                        <script type="text/javascript">	
                                            var codropsEvents = {
                                            <?php 
                                                $resArrayDate=array();
												foreach($dbf->fetchOrder("assign_tech at,work_order wo","at.wo_no=wo.wo_no AND job_status='Assigned'","","at.wo_no,at.start_date","") as $val){
												   array_push($resArrayDate,$val['start_date']);
												 }
												$resArrayDate= array_unique($resArrayDate);
												foreach($resArrayDate as $v){
												$resArray = $dbf->fetchOrder("assign_tech at,clients c,work_order wo"," at.wo_no=wo.wo_no AND wo.client_id=c.id AND at.start_date='$v'","","at.start_date,wo.id,at.wo_no,c.name,c.city");
												$string='';
												if(count($resArray) > 1){
													foreach($resArray as $resevent) {
														$eventdt= date("m-d-Y",strtotime($resevent['start_date']));
														$linkhref="view-job-board?id=$resevent[id]&src=cal"; $target = "_self";
														$string.= "<a href=".$linkhref." target=".$target.">".$resevent['wo_no'].'--'.$resevent['name'].'--'.$resevent['city']."</a>";
													}
														echo "'$eventdt' : '".$string."',";	
												}else{
													foreach($resArray as $resevent) {
														$eventdt= date("m-d-Y",strtotime($resevent['start_date']));
														$linkhref="view-job-board?id=$resevent[id]&src=cal"; $target = "_self";
														echo "'$eventdt' : '<a href=".$linkhref." target=".$target.">".$resevent['wo_no'].'--'.$resevent['name'].'--'.$resevent['city']."</a>',";
													}
												}
												}
                                            ?>
                                            };
                                            $(function() {
                                                var transEndEventNames = {
                                                        'WebkitTransition' : 'webkitTransitionEnd',
                                                        'MozTransition' : 'transitionend',
                                                        'OTransition' : 'oTransitionEnd',
                                                        'msTransition' : 'MSTransitionEnd',
                                                        'transition' : 'transitionend'
                                                    },
                                                    transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ],
                                                    $wrapper = $( '#custom-inner' ),
                                                    $calendar = $( '#calendar' ),
                                                    cal = $calendar.calendario( {
                                                        onDayClick : function( $el, $contentEl, dateProperties ) {
                                
                                                            if( $contentEl.length > 0 ) {
                                                                showEvents( $contentEl, dateProperties );
                                                            }
                                                        },
                                                        caldata : codropsEvents,
                                                        displayWeekAbbr : true
                                                    } ),
                                                    $month = $( '#custom-month' ).html( cal.getMonthName() ),
                                                    $year = $( '#custom-year' ).html( cal.getYear() );
                                
                                                $( '#custom-next' ).on( 'click', function() {
                                                    cal.gotoNextMonth( updateMonthYear );
                                                } );
                                                $( '#custom-prev' ).on( 'click', function() {
                                                    cal.gotoPreviousMonth( updateMonthYear );
                                                } );
                                
                                                function updateMonthYear() {				
                                                    $month.html( cal.getMonthName() );
                                                    $year.html( cal.getYear() );
                                                }
                                                // just an example..
                                                function showEvents( $contentEl, dateProperties ) {
                                                    hideEvents();
                                                    var $events = $( '<div id="custom-content-reveal" class="custom-content-reveal"><h4>Works for ' + dateProperties.monthname + ' ' + dateProperties.day + ', ' + dateProperties.year + '</h4><span>Scheduled Works</span></div>' ),
                                                    $close = $( '<span class="custom-content-close"></span>' ).on( 'click', hideEvents );
                                                    $events.append( $contentEl.html() , $close ).insertAfter( $wrapper );
                                                    setTimeout( function() {
                                                        $events.css( 'top', '0%' );
                                                    }, 25 );
                                                }
                                                function hideEvents() {
                                                    var $events = $( '#custom-content-reveal' );
                                                    if( $events.length > 0 ) {
                                                        $events.css( 'top', '100%' );
                                                        Modernizr.csstransitions ? $events.on( transEndEventName, function() { $( this ).remove(); } ) : $events.remove();
                                                    }
                                                }
                                            });
                                        </script>
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