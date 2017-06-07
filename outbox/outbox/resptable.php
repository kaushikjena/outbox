<?php 
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new User();
//page titlevariable
$pageTitle="Welcome To Out Of The Box";
include 'applicationtop.php';
//logout for users other than admin and user
/*if($_SESSION['usertype']!='admin' && $_SESSION['usertype']!='user'){
	header("location:logout");exit;
}*/
?>
<link rel="stylesheet" href="css/innermain.css" type="text/css" />
<link rel="stylesheet" href="css/innermedium.css" type="text/css" />
<link rel="stylesheet" href="css/innernarrow.css" type="text/css" />
<link rel="stylesheet" href="css/respmenu.css" type="text/css" />
<link rel="stylesheet" href="css/no_more_table.css" type="text/css" />
<script  type="text/javascript" src="js/dragtable.js"></script>
<script  type="text/javascript" src="js/sorttable.js"></script>
<script type="text/javascript">
function showgroupjobs(){
	//$("#loader").show();
	
	var url="ajax-resptable.php";
	//var cond = $("#cond").val();
	$.post(url,{},function(res){	
	//alert(res);	
		$("#resTable").html(res);
		//$("#DivGrp").hide();
		//$("#loader").hide();	
	});
}
</script>
<body onLoad="document.frmPrice.ServiceName.focus();">
    <div id="maindiv">
        <!-------------header--------------->
     	<?php include_once 'header.php';?>
   		<!-------------header--------------->
        
        <!-------------top menu--------------->
     	<?php include_once 'top-menu.php';?>
   		<!-------------top menu--------------->
         <div id="contentdiv">
                <!-------------Left menu--------------->
				<?php //include_once 'left-menu.php';?>
                <!-------------left menu--------------->
                <!-------------Main Body--------------->
                <div class="rightcolumjobboard">
            		<div class="rightcoluminner">
                        <div class="headerbg">Add Service Price</div>
                        <div class="spacer"></div>
                        <div id="contenttable">
                        	<!-----Table area start------->
                            	<div align="right" style="background-color:#eee; padding-right:5px;" class="formtext">
                            	<a href="javascript:void();" onClick="showgroupjobs()">View Job Groups</a></div>
                                <div id="resTable">
                          		<table id="no-more-tables" class="draggable sortable">
                                    <thead>
                                        <tr>
                                            <th width="6%">Code</th>
                                            <th width="32%">Company</th>
                                            <th width="11%">Price</th>
                                            <th width="10%">Change</th>
                                            <th width="10%">Change %</th>
                                            <th width="11%">Open</th>
                                            <th width="6%">High</th>
                                            <th width="6%">Low</th>
                                            <th width="8%">Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-title="Code">AAC</td>
                                            <td data-title="Company">AUSTRALIAN AGRICULTURAL COMPANY LIMITED.</td>
                                            <td data-title="Price">$1.38</td>
                                            <td data-title="Change">-0.01</td>
                                            <td data-title="Change %">-0.36%</td>
                                            <td data-title="Open" >$1.39</td>
                                            <td data-title="High" >$1.39</td>
                                            <td data-title="Low" >$1.38</td>
                                            <td data-title="Volume" >9,395</td>
                                        </tr>
                                        <tr>
                                            <td data-title="Code">AAC</td>
                                            <td data-title="Company">BUSTRALIAN AGRICULTURAL COMPANY LIMITED.</td>
                                            <td data-title="Price">$1.38</td>
                                            <td data-title="Change" >-0.01</td>
                                            <td data-title="Change %">-0.36%</td>
                                            <td data-title="Open">$1.39</td>
                                            <td data-title="High">$1.39</td>
                                            <td data-title="Low">$1.38</td>
                                            <td data-title="Volume">9,395</td>
                                        </tr>
                                        <tr>
                                            <td data-title="Code">AAC</td>
                                            <td data-title="Company">CUSTRALIAN AGRICULTURAL COMPANY LIMITED.</td>
                                            <td data-title="Price">$1.38</td>
                                            <td data-title="Change">-0.01</td>
                                            <td data-title="Change %">-0.36%</td>
                                            <td data-title="Open">$1.39</td>
                                            <td data-title="High">$1.39</td>
                                            <td data-title="Low">$1.38</td>
                                            <td data-title="Volume">9,395</td>
                                        </tr>
                                    </tbody>
                               </table>
                               </div>
                            <!-----Table area start-------> 
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