<nav>
<?php 
$arrModule =array();
if($_SESSION['usertype']=='admin'){?>
<div id="navmenu">
    <ul>
       <li class="has-sub"><a href="dashboard">Dashboard</a>
          <ul>
             <li><a href="edit-profile">My Account</a></li>
             <li><a href="change-password">Change Password</a></li>
             <li><a href="admin-manage-email-notification">Admin Email Notifications</a></li>
             <li><a href="logout.php">Logout</a></li>
          </ul>
       </li>
       <li class="has-sub"><a href="#">Clients / Customers</a>
          <ul>
             <li><a href="manage-client">Manage Client</a></li>
             <li><a href="add-client">Create Client</a></li>
             <li><a href="manage-customer">Manage Customer</a></li>
             <li><a href="credit-card-details">Credit Card Details</a></li>
          </ul>
       </li>
       <li class="has-sub"><a href="#">Technicians</a>
          <ul>
          	 <li><a href="manage-technician-active">Manage Active Techs</a></li>
             <li><a href="manage-technician">Manage Technicians</a></li>
             <li><a href="add-technician">Create Technicians</a></li>
          </ul>
       </li>
       <li class="has-sub"><a href="#">Users</a>
          <ul>
             <li><a href="manage-user">Manage Users</a></li>
             <li><a href="add-user">Create Users</a></li>
          </ul>
       </li>
       <li class="has-sub"><a href="#">Email Templates</a>
          <ul>
             <li><a href="email-template?id=1">New User Created</a></li>
             <li><a href="email-template?id=2">Forgot Password</a></li>
             <li><a href="email-template?id=3">Post Order Notification</a></li>
             <li><a href="email-template?id=16">Approve Post Order</a></li>
             <li><a href="email-template?id=4">New Technician Created</a></li>
             <li><a href="email-template?id=5">Client Notification</a></li>
             <li><a href="email-template?id=6">Assign Jobs Notification</a></li>
             <li><a href="email-template?id=7">New Client Created</a></li>
             <li><a href="email-template?id=8">Unassign Jobs Notification</a></li>
             <li><a href="email-template?id=9">Assign Jobs NotStarted</a></li>
             <li><a href="email-template?id=10">Technician Job NotStarted</a></li>
             <li><a href="email-template?id=11">Admin Schld Notification</a></li>
             <li><a href="email-template?id=12">Client Schld Notification </a></li>
             <li><a href="email-template?id=13">Client Pmt Notification </a></li>
             <li><a href="email-template?id=14">Admin Pmt Notification</a></li>
             <li><a href="email-template?id=15">Client Billing Notification</a></li>
             <li><a href="email-template?id=17">Mass Email Template</a></li>
             <li><a href="email-template?id=18">Alert Assigned Tech </a></li>
             <li><a href="email-template?id=19">Tech Contact Customer</a></li>
             <li><a href="email-template?id=20">Waiting For Parts</a></li>
             <li><a href="email-template?id=21">COD Scheduled User Notification</a></li>
             <li><a href="email-template?id=22">Tech Email Notification</a></li>
             <li><a href="email-template?id=23">Rescheduled Notification</a></li>
             <li><a href="email-template?id=24">Cancelled Order</a></li>
             <li><a href="email-template?id=25">Changed Assign Order</a></li>
          </ul>
       </li>
       <li  class="has-sub"><a href="#">Service Calls</a>
         <ul>
         	<li><a href="manage-job-board">Open Board</a></li>
            <li><a href="manage-job-board-assigned">Assigned Board</a></li>
            <li><a href="manage-job-board-wfp">Waiting For Parts Board</a></li>
            <li><a href="manage-job-board-hold">On Hold Board</a></li>
         	<li><a href="manage-job-board-dispatch">Dispatch Board</a></li>
            <li><a href="manage-job-board-completed">Completed Board</a></li>
            <li><a href="create-job">Create Order</a></li>
            <li><a href="manage-service-price">Tech Payment Price</a></li>
            <li><a href="manage-service-price-client">Client Billing Price</a></li>
            <li><a href="assign_job_technician">Assign Orders</a></li>
            <li><a href="unapprove-job">Unapproved Board</a></li>
            <li><a href="send-mass-email">Send Mass Email</a></li>
         </ul>
       </li>
       <li class="has-sub"><a href="#">Payments</a>
         <ul>
         	<li><a href="manage-client-billings">Clients Weekly Billings</a></li>
            <li><a href="manage-client-invoiced-billings">Clients Invoiced Billings</a></li>
            <li><a href="manage-technician-payments">Tech Weekly Payments</a></li>
            <li><a href="manage-technician-invoiced-payments">Tech Invoived Payments</a></li>
            <li><a href="manage-cod-billings">COD Billings</a></li>
            <li><a href="manage-client-payments-history">Clients Payments History</a></li>
            <li><a href="manage-cod-payments-history">COD Payments History</a></li>
            <li><a href="manage-technician-payments-history">Tech Payments History</a></li>
            <li><a href="manage-receivable">Manage Receivables</a></li>
         </ul>
       </li>
       <li><a href="jobs_calendar">Work Calendar</a></li>
       <li class="has-sub"><a href="#">Reports</a>
         <ul>
            <li><a href="admin_client_report">Client Report</a></li>
            <li><a href="admin_technician_report">Technician Report</a></li>
            <li><a href="admin_technician_ws_report">Technician Work Status</a></li>
            <li><a href="admin_open_job_report">Open Orders Report</a></li>
            <li><a href="admin_assigned_order_report">Assigned Orders Report</a></li>
            <li><a href="admin_schedule_job_report">Schedule Orders Report</a></li>
            <li><a href="admin_client_payment_report">Client's Payment Report</a></li>
            <li><a href="admin_cod_payment_report">COD's Payment Report</a></li>
            <li><a href="admin_tech_payment_report">Tech Payments Report</a></li>
            <li><a href="admin_job_payment_report">Total Order Payment Report</a></li>
            <li><a href="admin_service_billing_report">Total Service Payment Report</a></li>
            <li><a href="admin_client_billing_report">Client Billing Report</a></li>
            <li><a href="admin_invoiced_billing_report">Invoiced Order Report</a></li>
            <li><a href="admin_completed_job_report">Completed Orders Report(For reconcile Invoice#)</a></li>
            <li><a href="admin_client_reconcile_report">Client Reconcile Report</a></li>
            <li><a href="admin_mileage_worktype_report">Mileage Worktype Report</a></li>
            <li><a href="admin_total_job_report">Total Order Report</a></li>
         </ul>
       </li>
       <li class="has-sub"><a href="#">Settings</a>
       	<ul>
        	<li><a href="manage-service">Service</a></li>
            <li><a href="manage-worktype">Work Type</a></li>
            <li><a href="manage-equipment">Equipment</a></li>
            <li><a href="manage-jobtype">Status Type</a></li>
            <li><a href="manage-module">Modules</a></li>
            <li><a href="manage-state">States</a></li>
            <li><a href="alert_assignment">Alert Days</a></li>
            <li><a href="alert-email-manage">Alert Emails</a></li>
            <li><a href="authorize_credential">Authorize Credential</a></li>
            <li><a href="tech-alert-sms">Tech SMS Template</a></li>
            <li><a href="admin-manage-email-notification">Admin Email Notifications</a></li>
            <li><a href="manage-emails">Manage Emails</a></li>
            <li><a href="manage-versions">Manage Versions</a></li>
        </ul>
       </li>
    </ul>
</div>
<div id="dropnavbg"></div>
<div id="resnav">
  <div class="container">
   <div id="content">
    <div id="menu1" class="menu_container green_glass full_width">
    <div class="mobile_collapser">
    <label for="hidden_menu_collapser">
        <span class="mobile_menu_icon"></span>
    </label>
    </div>
    <input id="hidden_menu_collapser" type="checkbox">
        <ul>
            <li><a href="dashboard">Dashboard</a>
            	<div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="edit-profile">My Account</a></li>
                             <li><a href="change-password">Change Password</a></li>
                             <li><a href="admin-manage-email-notification">Admin Email Notifications</a></li>
                             <li><a href="logout.php">Logout</a></li>
                        </ul>
                	</div>
            	</div>
            </li>
            <li><a href="#">Clients / Customers</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-client">Manage Client</a></li>
                            <li><a href="add-client">Create Client</a></li>
                            <li><a href="manage-customer">Manage Customer</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <li><a href="#">Technicians</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                        	<li><a href="manage-technician-active">Manage Active Techs</a></li>
                            <li><a href="manage-technician">Manage Technicians</a></li>
             				<li><a href="add-technician">Create Technicians</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <li><a href="#">Users</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-user">Manage Users</a></li>
             				<li><a href="add-user">Create Users</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <li><a href="#">Email Templates</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                             <li><a href="email-template?id=1">New User Created</a></li>
                             <li><a href="email-template?id=2">Forgot Password</a></li>
                             <li><a href="email-template?id=3">Post Order Notification</a></li>
                             <li><a href="email-template?id=16">Approve Post Order</a></li>
                             <li><a href="email-template?id=4">New Technician Created</a></li>
                             <li><a href="email-template?id=5">Client Notification</a></li>
                             <li><a href="email-template?id=6">Assign Jobs Notification</a></li>
                             <li><a href="email-template?id=7">New Client Created</a></li>
                             <li><a href="email-template?id=8">Unassign Jobs Notification</a></li>
                             <li><a href="email-template?id=9">Assign Jobs NotStarted</a></li>
                             <li><a href="email-template?id=10">Technician Job NotStarted</a></li>
                             <li><a href="email-template?id=11">Admin Schld Notification</a></li>
                             <li><a href="email-template?id=12">Client Schld Notification </a></li>
                             <li><a href="email-template?id=13">Client Pmt Notification </a></li>
                             <li><a href="email-template?id=14">Admin Pmt Notification</a></li>
                             <li><a href="email-template?id=15">Client Billing Notification</a></li>
                             <li><a href="email-template?id=17">Mass Email Template</a></li>
                             <li><a href="email-template?id=18">Alert Assigned Tech </a></li>
                             <li><a href="email-template?id=19">Tech Contact Customer</a></li>
                             <li><a href="email-template?id=20">Waiting For Parts</a></li>
                             <li><a href="email-template?id=21">COD Scheduled User Notification</a></li>
                             <li><a href="email-template?id=22">Tech Email Notification</a></li>
                             <li><a href="email-template?id=23">Rescheduled Notification</a></li>
                             <li><a href="email-template?id=24">Cancelled Order</a></li>
                             <li><a href="email-template?id=25">Changed Assign Order</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <li><a href="#">Service Calls</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-job-board">Open Board</a></li>
                            <li><a href="manage-job-board-assigned">Assigned Board</a></li>
                            <li><a href="manage-job-board-wfp">Waiting For Parts Board</a></li>
                            <li><a href="manage-job-board-hold">On Hold Board</a></li>
                            <li><a href="manage-job-board-dispatch">Dispatch Board</a></li>
                            <li><a href="manage-job-board-completed">Completed Board</a></li>
                            <li><a href="create-job">Create Orders</a></li>
                            <li><a href="manage-service-price">Service Price</a></li>
                            <li><a href="assign_job_technician">Assign Orders</a></li>
                            <li><a href="unapprove-job">Unapproved Board</a></li>
                            <li><a href="send-mass-email">Send Mass Email</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <li><a href="#">Payments</a>
            <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-client-billings">Clients Weekly Billings</a></li>
                            <li><a href="manage-client-invoiced-billings">Clients Invoiced Billings</a></li>
                            <li><a href="manage-technician-payments">Tech Weekly Payments</a></li>
                            <li><a href="manage-technician-invoiced-payments">Tech Invoived Payments</a></li>
                            <li><a href="manage-cod-billings">COD Billings</a></li>
                            <li><a href="manage-client-payments-history">Clients Payments History</a></li>
                            <li><a href="manage-cod-payments-history">COD Payments History</a></li>
                            <li><a href="manage-technician-payments-history">Tech Payments History</a></li>
                            <li><a href="manage-receivable">Manage Receivables</a></li>
                    	</ul>
                	</div>
            	</div>
           </li>
           <li><a href="jobs_calendar">Work Calendar</a></li>
           <li><a href="#">Reports</a>
           		<div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="admin_client_report">Client Report</a></li>
                            <li><a href="admin_technician_report">Technician Report</a></li>
                            <li><a href="admin_technician_ws_report">Technician Work Status</a></li>
                            <li><a href="admin_open_job_report">Open Orders Report</a></li>
                            <li><a href="admin_schedule_job_report">Schedule Orders Report</a></li>
                            <li><a href="admin_client_payment_report">Client's Payment Report</a></li>
                            <li><a href="admin_cod_payment_report">COD's Payment Report</a></li>
                            <li><a href="admin_tech_payment_report">Tech Payments Report</a></li>
                            <li><a href="admin_job_payment_report">Total Order Payment Report</a></li>
                            <li><a href="admin_service_billing_report">Total Service Payment Report</a></li>
                            <li><a href="admin_client_billing_report">Client Billing Report</a></li>
                            <li><a href="admin_invoiced_billing_report">Invoiced Order Report</a></li>
                            <li><a href="admin_completed_job_report">Completed Orders Report(For reconcile Invoice#)</a></li>
                            <li><a href="admin_client_reconcile_report">Client Reconcile Report</a></li>
                            <li><a href="admin_total_job_report">Total Order Report</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <li><a href="#">Settings</a>
            <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-service">Service</a></li>
                            <li><a href="manage-worktype">Work Type</a></li>
                            <li><a href="manage-equipment">Equipment</a></li>
                            <li><a href="manage-jobtype">Status Type</a></li>
                            <li><a href="manage-module">Modules</a></li>
                            <li><a href="manage-state">States</a></li>
                            <li><a href="alert_assignment">Alert Days</a></li>
                            <li><a href="alert-email-manage">Alert Emails</a></li>
                            <li><a href="authorize_credential">Authorize Credential</a></li>
                            <li><a href="tech-alert-sms">Tech SMS Template</a></li>
                            <li><a href="admin-manage-email-notification">Admin Email Notifications</a></li>
                            <li><a href="manage-emails">Manage Emails</a></li>
                            <li><a href="manage-versions">Manage Versions</a></li>
                    	</ul>
                	</div>
            	</div>
           </li>
        </ul>
    </div> 
   </div>
  </div>
 </div>
<?php }elseif($_SESSION['usertype']=='user'){ 
		$resPermissions = $dbf->fetchSingle("user_permission","user_type='user' AND user_id='$_SESSION[userid]'");
		//get clients,techs and modules from the user permission table
		$implode_clients = ($resPermissions['clients']<>'')? $resPermissions['clients']:'';
		$implode_techs = ($resPermissions['techs']<>'')? $resPermissions['techs']:'';
		$implode_reports = ($resPermissions['reports']<>'')? $resPermissions['reports']:'';
		$implode_modules = ($resPermissions['modules']<>'')? $resPermissions['modules']:'';
		$resModules = $dbf->fetchOrder("module","FIND_IN_SET(id,'$implode_modules')","","module_name","");
		foreach($resModules as $val){
			array_push($arrModule,$val['module_name']);
		}
		
		$arraReports = $implode_reports ? explode(",",$implode_reports):array();
		//print_r($resPermissions);
		//print_r($arraReports);
?>
	<input type="hidden" id="implode_clients" value="<?php echo $implode_clients;?>"/>
    <input type="hidden" id="implode_techs" value="<?php echo $implode_techs;?>"/>
<div id="navmenu">
    <ul>
       <li class="has-sub"><a href="dashboard">Dashboard</a>
          <ul>
             <li><a href="edit-profile">My Account</a></li>
             <li><a href="change-password">Change Password</a></li>
             <li><a href="logout.php">Logout</a></li>
          </ul>
       </li>
       <?php if(in_array('Clients',$arrModule,true)){?>
       <li class="has-sub"><a href="#">Clients / Customers</a>
          <ul>
             <li><a href="manage-client">Manage Client</a></li>
             <?php if($resPermissions['clients']==''){?>
             <li><a href="add-client">Create Client</a></li>
             <li><a href="manage-customer">Manage Customer</a></li>
             <?php }?>
          </ul>
       </li>
       <?php } if(in_array('Technicians',$arrModule,true)){?>
       <li class="has-sub"><a href="#">Technicians</a>
          <ul>
          	 <li><a href="manage-technician-active">Manage Active Techs</a></li>
             <li><a href="manage-technician">Manage Technicians</a></li>
             <?php if($resPermissions['techs']==''){?>
             <li><a href="add-technician">Create Technicians</a></li>
             <?php }?>
          </ul>
       </li>
       <?php }if(in_array('Email Templates',$arrModule,true)){?>
       <li class="has-sub"><a href="#">Email Templates</a>
          <ul>
             <li><a href="email-template?id=1">New User Created</a></li>
             <li><a href="email-template?id=2">Forgot Password</a></li>
             <li><a href="email-template?id=3">Post Order Notification</a></li>
             <li><a href="email-template?id=16">Approve Post Order</a></li>
             <li><a href="email-template?id=4">New Technician Created</a></li>
             <li><a href="email-template?id=5">Client Notification</a></li>
             <li><a href="email-template?id=6">Assign Jobs Notification</a></li>
             <li><a href="email-template?id=7">New Client Created</a></li>
             <li><a href="email-template?id=8">Unassign Jobs Notification</a></li>
             <li><a href="email-template?id=9">Assign Jobs NotStarted</a></li>
             <li><a href="email-template?id=10">Technician Job NotStarted</a></li>
             <li><a href="email-template?id=11">Admin Schld Notification</a></li>
             <li><a href="email-template?id=12">Client Schld Notification </a></li>
             <li><a href="email-template?id=13">Client Pmt Notification </a></li>
             <li><a href="email-template?id=14">Admin Pmt Notification</a></li>
             <li><a href="email-template?id=15">Client Billing Notification</a></li>
             <li><a href="email-template?id=17">Mass Email Template</a></li>
             <li><a href="email-template?id=18">Alert Assigned Tech </a></li>
             <li><a href="email-template?id=19">Tech Contact Customer</a></li>
             <li><a href="email-template?id=20">Waiting For Parts</a></li>
             <li><a href="email-template?id=21">COD Scheduled User Notification</a></li>
             <li><a href="email-template?id=22">Tech Email Notification</a></li>
             <li><a href="email-template?id=23">Rescheduled Notification</a></li>
             <li><a href="email-template?id=24">Cancelled Order</a></li>
             <li><a href="email-template?id=25">Changed Assign Order</a></li>
          </ul>
       </li>
       <?php }if(in_array('Service Calls',$arrModule,true)){?>
       <li  class="has-sub"><a href="#">Service Calls</a>
         <ul>
         	<li><a href="manage-job-board">Open Board</a></li>
            <li><a href="manage-job-board-assigned">Assigned Board</a></li>
            <li><a href="manage-job-board-wfp">Waiting For Parts Board</a></li>
            <li><a href="manage-job-board-hold">On Hold Board</a></li>
            <li><a href="manage-job-board-dispatch">Dispatch Board</a></li>
            <li><a href="manage-job-board-completed">Completed Board</a></li>
            <li><a href="create-job">Create Orders</a></li>
            <li><a href="manage-service-price">Service Price</a></li>
            <li><a href="assign_job_technician">Assign Orders</a></li>
            <li><a href="unapprove-job">Unapproved Board</a></li>
            <li><a href="send-mass-email">Send Mass Email</a></li>
         </ul>
       </li>
       <?php }if(in_array('Payments',$arrModule,true)){?>
       <li  class="has-sub"><a href="#">Payments</a>
         <ul>
         	<li><a href="manage-client-billings">Clients Weekly Billings</a></li>
            <li><a href="manage-client-invoiced-billings">Clients Invoiced Billings</a></li>
            <li><a href="manage-technician-payments">Tech Weekly Payments</a></li>
            <li><a href="manage-technician-invoiced-payments">Tech Invoived Payments</a></li>
            <li><a href="manage-cod-billings">COD Billings</a></li>
            <li><a href="manage-client-payments-history">Clients Payments History</a></li>
            <li><a href="manage-cod-payments-history">COD Payments History</a></li>
            <li><a href="manage-technician-payments-history">Tech Payments History</a></li>
            <li><a href="manage-receivable">Manage Receivables</a></li>
         </ul>
       </li>
       <?php }if(in_array('Work Calender',$arrModule,true)){?>
       <li><a href="jobs_calendar">Work Calender</a></li>
       <?php }if(in_array('Reports',$arrModule,true)){?>
       <li class="has-sub"><a href="#">Reports</a>
       	<ul>
           	<?php if(in_array('1',$arraReports,true)){?><li><a href="admin_client_report">Client Report</a></li><?php }?>
            <?php if(in_array('2',$arraReports,true)){?><li><a href="admin_technician_report">Technician Report</a></li><?php }?>
            <?php if(in_array('3',$arraReports,true)){?><li><a href="admin_technician_ws_report">Technician Work Status</a></li><?php }?>
            <?php if(in_array('4',$arraReports,true)){?><li><a href="admin_open_job_report">Open Orders Report</a></li><?php }?>
            <?php if(in_array('5',$arraReports,true)){?><li><a href="admin_schedule_job_report">Schedule Orders Report</a></li><?php }?>
            <?php if(in_array('6',$arraReports,true)){?><li><a href="admin_client_payment_report">Client's Payment Report</a></li><?php }?>
            <?php if(in_array('7',$arraReports,true)){?><li><a href="admin_cod_payment_report">COD's Payment Report</a></li><?php }?>
            <?php if(in_array('8',$arraReports,true)){?><li><a href="admin_tech_payment_report">Tech Payments Report</a></li><?php }?>
            <?php if(in_array('9',$arraReports,true)){?><li><a href="admin_job_payment_report">Total Job Payment Report</a></li><?php }?>
            <?php if(in_array('10',$arraReports,true)){?><li><a href="admin_service_billing_report">Total Service Payment Report</a></li><?php }?>
            <?php if(in_array('11',$arraReports,true)){?><li><a href="admin_client_billing_report">Client Billing Report</a></li><?php }?>
            <?php if(in_array('12',$arraReports,true)){?><li><a href="admin_invoiced_billing_report">Invoiced Order Report</a></li><?php }?>
            <?php if(in_array('13',$arraReports,true)){?><li><a href="admin_completed_job_report">Completed Orders Report(For reconcile Invoice#)</a></li><?php }?>
            <?php if(in_array('14',$arraReports,true)){?><li><a href="admin_client_reconcile_report">Client Reconcile Report</a></li><?php }?>
            <?php if(in_array('15',$arraReports,true)){?><li><a href="admin_total_job_report">Total Order Report</a></li><?php }?>
         </ul>
       </li>
       <?php }if(in_array('Settings',$arrModule,true)){?>
       <li class="has-sub"><a href="#">Settings</a>
       	<ul>
        	<li><a href="manage-service">Service</a></li>
            <li><a href="manage-worktype">Work Type</a></li>
            <li><a href="manage-equipment">Equipment</a></li>
            <li><a href="manage-jobtype">Status Type</a></li>
            <li><a href="manage-module">Modules</a></li>
            <li><a href="manage-state">States</a></li>
            <li><a href="alert_assignment">Alert Days</a></li>
            <li><a href="alert-email-manage">Alert Emails</a></li>
            <li><a href="authorize_credential">Authorize Credential</a></li>
            <li><a href="tech-alert-sms">Tech SMS Template</a></li>
            <li><a href="admin-manage-email-notification">Admin Email Notifications</a></li>
            <li><a href="manage-emails">Manage Emails</a></li>
            <li><a href="manage-versions">Manage Versions</a></li>
        </ul>
       </li>
       <?php }?>
    </ul>
</div>
<div id="resnav">
  <div class="container">
   <div id="content">
    <div id="menu1" class="menu_container green_glass full_width">
    <div class="mobile_collapser">
    <label for="hidden_menu_collapser">
        <span class="mobile_menu_icon"></span>
    </label>
    </div>
    <input id="hidden_menu_collapser" type="checkbox">
        <ul>
            <li><a href="dashboard">Dashboard</a>
            	<div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="edit-profile">My Account</a></li>
                             <li><a href="change-password">Change Password</a></li>
                             <li><a href="logout.php">Logout</a></li>
                        </ul>
                	</div>
            	</div>
            </li>
            <?php if(in_array('Clients',$arrModule,true)){?>
            <li><a href="#">Clients / Customers</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-client">Manage Client</a></li>
                            <?php if($resPermissions['clients']==''){?>
                            <li><a href="add-client">Create Client</a></li>
                            <li><a href="manage-customer">Manage Customer</a></li>
                            <?php }?>
                        </ul>
                	</div>
            	</div>
           </li>
           <?php } if(in_array('Technicians',$arrModule,true)){?>
           <li><a href="#">Technicians</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                        	<li><a href="manage-technician-active">Manage Active Techs</a></li>
                            <li><a href="manage-technician">Manage Technicians</a></li>
                            <?php if($resPermissions['techs']==''){?>
             				<li><a href="add-technician">Create Technicians</a></li>
                            <?php }?>
                        </ul>
                	</div>
            	</div>
           </li>
           <?php }if(in_array('Email Templates',$arrModule,true)){?>
           <li><a href="#">Email Templates</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                             <li><a href="email-template?id=1">New User Created</a></li>
                             <li><a href="email-template?id=2">Forgot Password</a></li>
                             <li><a href="email-template?id=3">Post Order Notification</a></li>
                             <li><a href="email-template?id=16">Approve Post Order</a></li>
                             <li><a href="email-template?id=4">New Technician Created</a></li>
                             <li><a href="email-template?id=5">Client Notification</a></li>
                             <li><a href="email-template?id=6">Assign Jobs Notification</a></li>
                             <li><a href="email-template?id=7">New Client Created</a></li>
                             <li><a href="email-template?id=8">Unassign Jobs Notification</a></li>
                             <li><a href="email-template?id=9">Assign Jobs NotStarted</a></li>
                             <li><a href="email-template?id=10">Technician Job NotStarted</a></li>
                             <li><a href="email-template?id=11">Admin Schld Notification</a></li>
                             <li><a href="email-template?id=12">Client Schld Notification </a></li>
                             <li><a href="email-template?id=13">Client Pmt Notification </a></li>
                             <li><a href="email-template?id=14">Admin Pmt Notification</a></li>
                             <li><a href="email-template?id=15">Client Billing Notification</a></li>
                             <li><a href="email-template?id=17">Mass Email Template</a></li>
                             <li><a href="email-template?id=18">Alert Assigned Tech </a></li>
                             <li><a href="email-template?id=19">Tech Contact Customer</a></li>
                             <li><a href="email-template?id=20">Waiting For Parts</a></li>
                             <li><a href="email-template?id=21">COD Scheduled User Notification</a></li>
                             <li><a href="email-template?id=22">Tech Email Notification</a></li>
                             <li><a href="email-template?id=23">Rescheduled Notification</a></li>
                             <li><a href="email-template?id=24">Cancelled Order</a></li>
                             <li><a href="email-template?id=25">Changed Assign Order</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <?php }if(in_array('Service Calls',$arrModule,true)){?>
           <li><a href="#">Service Calls</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-job-board">Open Board</a></li>
                            <li><a href="manage-job-board-assigned">Assigned Board</a></li>
                            <li><a href="manage-job-board-wfp">Waiting For Parts Board</a></li>
                            <li><a href="manage-job-board-hold">On Hold Board</a></li>
                            <li><a href="manage-job-board-dispatch">Dispatch Board</a></li>
                            <li><a href="manage-job-board-completed">Completed Board</a></li>
                            <li><a href="create-job">Create Orders</a></li>
                            <li><a href="manage-service-price">Service Price</a></li>
                            <li><a href="assign_job_technician">Assign Orders</a></li>
                            <li><a href="unapprove-job">Unapproved Board</a></li>
                            <li><a href="send-mass-email">Send Mass Email</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
            <?php }if(in_array('Payments',$arrModule,true)){?>
           <li  class="has-sub"><a href="#">Payments</a>
             <ul>
                <li><a href="manage-client-billings">Clients Weekly Billings</a></li>
                <li><a href="manage-client-invoiced-billings">Clients Invoiced Billings</a></li>
                <li><a href="manage-technician-payments">Tech Weekly Payments</a></li>
                <li><a href="manage-technician-invoiced-payments">Tech Invoived Payments</a></li>
                <li><a href="manage-cod-billings">COD Billings</a></li>
                <li><a href="manage-client-payments-history">Clients Payments History</a></li>
                <li><a href="manage-cod-payments-history">COD Payments History</a></li>
                <li><a href="manage-technician-payments-history">Tech Payments History</a></li>
                <li><a href="manage-receivable">Manage Receivables</a></li>
             </ul>
           </li>
           <?php }if(in_array('Work Calender',$arrModule,true)){?>
           <li><a href="jobs_calendar">Work Calender</a></li>
           <?php }if(in_array('Reports',$arrModule,true)){?>
           <li><a href="#">Reports</a>
           		<div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <?php if(in_array('1',$arraReports,true)){?><li><a href="admin_client_report">Client Report</a></li><?php }?>
							<?php if(in_array('2',$arraReports,true)){?><li><a href="admin_technician_report">Technician Report</a></li><?php }?>
                            <?php if(in_array('3',$arraReports,true)){?><li><a href="admin_technician_ws_report">Technician Work Status</a></li><?php }?>
                            <?php if(in_array('4',$arraReports,true)){?><li><a href="admin_open_job_report">Open Orders Report</a></li><?php }?>
                            <?php if(in_array('5',$arraReports,true)){?><li><a href="admin_schedule_job_report">Schedule Orders Report</a></li><?php }?>
                            <?php if(in_array('6',$arraReports,true)){?><li><a href="admin_client_payment_report">Client's Payment Report</a></li><?php }?>
                            <?php if(in_array('7',$arraReports,true)){?><li><a href="admin_cod_payment_report">COD's Payment Report</a></li><?php }?>
                            <?php if(in_array('8',$arraReports,true)){?><li><a href="admin_tech_payment_report">Tech Payments Report</a></li><?php }?>
                            <?php if(in_array('9',$arraReports,true)){?><li><a href="admin_job_payment_report">Total Order Payment Report</a></li><?php }?>
                            <?php if(in_array('10',$arraReports,true)){?><li><a href="admin_service_billing_report">Total Service Payment Report</a></li><?php }?>
                            <?php if(in_array('11',$arraReports,true)){?><li><a href="admin_client_billing_report">Client Billing Report</a></li><?php }?>
                            <?php if(in_array('12',$arraReports,true)){?><li><a href="admin_invoiced_billing_report">Invoiced Order Report</a></li><?php }?>
                            <?php if(in_array('13',$arraReports,true)){?><li><a href="admin_completed_job_report">Completed Orders Report(For reconcile Invoice#)</a></li><?php }?>
                            <?php if(in_array('14',$arraReports,true)){?><li><a href="admin_client_reconcile_report">Client Reconcile Report</a></li><?php }?>
                             <?php if(in_array('15',$arraReports,true)){?><li><a href="admin_total_job_report">Total Order Report</a></li><?php }?>
                        </ul>
                	</div>
            	</div>
           </li>
           <?php }if(in_array('Settings',$arrModule,true)){?>
           <li><a href="#">Settings</a>
            <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="manage-service">Service</a></li>
                            <li><a href="manage-worktype">Work Type</a></li>
                            <li><a href="manage-equipment">Equipment</a></li>
                            <li><a href="manage-jobtype">Status Type</a></li>
                            <li><a href="manage-module">Modules</a></li>
                            <li><a href="manage-state">States</a></li>
                            <li><a href="alert_assignment">Alert Days</a></li>
                            <li><a href="alert-email-manage">Alert Emails</a></li>
                            <li><a href="authorize_credential">Authorize Credential</a></li>
                            <li><a href="tech-alert-sms">Tech SMS Template</a></li>
                            <li><a href="admin-manage-email-notification">Admin Email Notifications</a></li>
                            <li><a href="manage-emails">Manage Emails</a></li>
                            <li><a href="manage-versions">Manage Versions</a></li>
                    	</ul>
                	</div>
            	</div>
           </li>
           <?php }?>
        </ul>
    </div> 
   </div>
  </div>
 </div>
<?php }?>
</nav>