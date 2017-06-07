<?php
$arrModule =array();
$resPermissions = $dbf->fetchOrder("client_permission u,client_module m","u.user_id='$_SESSION[userid]' AND u.module_id=m.id","","u.module_id,m.module_name","");
foreach($resPermissions as $val){
	array_push($arrModule,$val['module_name']);
}
?>
<nav>
<div id="navmenu">
    <ul>
       <li class="has-sub"><a href="client-dashboard">Dashboard</a>
          <ul>
             <li><a href="client-edit-profile">My Account</a></li>
             <li><a href="client-change-password">Change Password</a></li>
             <li><a href="../logout.php">Logout</a></li>
          </ul>
       </li>
       <?php if(in_array('Service Call',$arrModule,true)){?>
       <li  class="has-sub"><a href="#">Service Calls</a>
         <ul>
         	<li><a href="client-create-job">Create Order</a></li>
            <li><a href="client-manage-job-board">Open Board</a></li>
            <li><a href="client-manage-job-board-assign">Assign Board</a></li>
            <li><a href="client-manage-job-board-dispatch">Dispatch Board</a></li>
         </ul>
       </li>
       <?php }if(in_array('Payments',$arrModule,true)){?>
       <li class="has-sub"><a href="#">Payments</a>
       	<ul>
        	<li><a href="client-workorder-billings">Work Order Bills</a></li>
            <li><a href="client-payment-history">Payment History</a></li>
        </ul>
       </li>
       <?php }if(in_array('Reports',$arrModule,true)){?>
       <li  class="has-sub"><a href="#">Report</a>
         <ul>
         	<li><a href="client_report">Order Report</a></li>
            <li><a href="client_payment_report">Client Payment Report</a></li>
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
            <li><a href="client-dashboard">Dashboard</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="client-edit-profile">My Account</a></li>
                             <li><a href="client-change-password">Change Password</a></li>
                             <li><a href="../logout.php">Logout</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <?php if(in_array('Service Call',$arrModule,true)){?>
           <li><a href="#">Service Calls</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                           <li><a href="client-create-job">Create Order</a></li>
                            <li><a href="client-manage-job-board">Open Board</a></li>
                            <li><a href="client-manage-job-board-assign">Assign Board</a></li>
                            <li><a href="client-manage-job-board-dispatch">Dispatch Board</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <?php }if(in_array('Payments',$arrModule,true)){?>
           <li><a href="#">Payments</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="client-workorder-billings">Work Order Bills</a></li>
            				<li><a href="client-payment-history">Payment History</a></li>
                        </ul>
                	</div>
            	</div>
           </li>
           <?php }if(in_array('Reports',$arrModule,true)){?>
           <li><a href="#">Reports</a>	
                <div class="menu_dropdown_block">
                	<div class="container">
                        <ul class="menu_submenu">
                            <li><a href="client_report">Order Report</a></li>
            				<li><a href="client_payment_report">Client Payment Report</a></li>
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
  </nav>