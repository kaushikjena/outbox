<!DOCTYPE html >
<html>
<head runat="server">
<title><?php echo $pageTitle;?></title>
<link rel="shortcut icon" href="../images/favicon.ico"> 
<!-- to make jquery.calendario.js IE8 compatible -->
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<!-- end of making jquery.calendario.js IE8 compatible -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale=1">
   <script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
   <!--ACCORDION MENU-->
        <link href="../accordion/accordion_style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../accordion/ddaccordion.js"></script>
        <script type="text/javascript">
        ddaccordion2.init({
            headerclass: "submenuheader2", //Shared CSS class name of headers group
            contentclass: "submenu2", //Shared CSS class name of contents group
            revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
            mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
            collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
            defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
            onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
            animatedefault: false, //Should contents open by default be animated into view?
            persiststate: true, //persist state of opened contents within browser session?
            toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
            togglehtml: ["suffix", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
            animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
            oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
                //do nothing
            },
            onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
                //do nothing
            }
        })
	</script>
    <!--ACCORDION MENU ENDS HERE-->
    <!--wysiwyg editor-->
    <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../ckeditor/sample.js" ></script>
    <link rel="stylesheet" type="text/css" href="../ckeditor/sample.css" />
    <!--wysiwyg editor-->
    <!--UI JQUERY DATE TIME PICKER-->
    <link rel="stylesheet" href="../datepicker/jquery.ui.all.css">
    <script src="../datepicker/jquery.ui.core.js"></script>
    <script src="../datepicker/jquery.ui.datepicker.js"></script>
    <script src="../datepicker/jquery.ui.widget.js"></script>
    <script src="../datepicker/jquery-ui-1.8.13.custom.min.js"></script>
    <script src="../datepicker/jquery-ui-timepicker-addon.js"></script>
    <link rel="stylesheet" href="../datepicker/jquery-ui-timepicker-addon.css">
    <script type="text/javascript">
    $(function() {
        $( ".datepick").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            yearRange: '-80:+20'
        });
    });
	$(function() {
		$( ".datetime").timepicker({
			ampm:true
		});
	});
    <!--END UI JQUERY DATE TIME PICKER-->
    </script>
    <!--Fancybox-->
	<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.2.js" ></script>
    <link rel="stylesheet" href="../fancybox/jquery.fancybox-1.3.2.css" type="text/css" />
    <!--Fancybox-->
    <script type="text/javascript" src="../js/outbox.js"></script>
    <!--Autocomplte--> 
	<script type="text/javascript" src="../jquery/jquery.autocomplete.js"></script>
    <link rel="stylesheet" type="text/css" href="../jquery/jquery.autocomplete.css" />
	<!--Autocomplte--> 
    <?php 
	if($_SESSION['userid']!='' && $_SESSION['usertype']!=''){
		include ("../mychat.php");//include chart here
	}?>  
</head>