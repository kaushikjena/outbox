// JavaScript Document

var sfind = [
			':dance:','\\\o\\/','\\\:D\\/',':d\\/',
			':star:','\\(\\*\\)',
			':phone:',':mobile:',':mp:',':ph:',
			":finger:", ":bandit:", ":drunk:", 
			":smoking:", ":smoke:", ":ci:",
			":toivo:", ":rock:", ":headbang:", ":banghead:",
			":bug:",":fubar:",":poolparty:",
			":swear:",":tmi:",":heidy:",
			":mooning:",':\\)',':=\\)',
			':-\\)',':\\(',':=\\(',
			':-\\(',
			'8\\)','8=\\)','8-\\)','B\\)','B=\\)','B-\\)',':cool',
			';\\)',';-\\)',
			';\\(',':-\\(',';\\(',';-\\(',';=\\(',':=\\(',
			':sweat','\\(:\\|',
			':\\|',':=\\|',':-\\|',
			':\\*',':=\\*',':-\\*',
			':blush:',':\\$',':-\\$',':\\=\\=\\$', 
			':\\^\\)',
			'\\|-\\)','I-\\)','i\\=\\)',':snooze:',':sleepy:',
			'\\|\\(','\\|-\\(','\\|\\=\\(',
			':inlove:',
			'\\]:\\)','\\>:\\)',':grin:',
			':talk:',
			':yawn:','\\|-\\(\\)',
			':puke:',':\\&',':-\\&',':=\\&',
			':doh',
			':\\@',':\\-@',':\\=@','x\\(','x-\\(','x=\\(','X\\(','X-\\(','X-\\(',
			':wasntme:',
			':party:',
			':mm:',
			'8-I','8-\\|','8\\|','B\\|','8=\\|','B=\\|',':nerd:',   //image 40.gif
			':x',':-x',':X',':-X',':#',':-#',':=x',':=X',':=#',
			':hi:',
			':call:',
			':devil:',
			':angel:',
			':envy:',
			':wait:',
			':bear:',':hug:',
			':makeup:',':kate:',
			':giggle:',':chuckle:',
			':clap:',
			':think:',':\\?',':-\\?',':=\\?',
			':bow:',
			':rofl:',
			':whew:',
			':happy:',
			':smirk:',
			':nod:',
			':shaking:',
			':punch:',  //image 60
			':emo:',
			':ok:','\\(y\\)','\\(Y\\)',
			':no:','\\(n\\)','\\(N\\)',
			':handshake',
			'\\<3',
			'\\<\\/3',
			':mail:',
			':flower:',
			':rain:',
			':sun:',
			':time:',
			':music:',
			':film:',':movie:',
			':coffee:',
			':pizza:',
			':cash:',':\\$:',
			':muscle:',':flex:',
			':cake:','\\(\\^\\)',
			':beer:',
			':drink:','\\(d\\)','\\(D\\)',
			':ninja:',
			';p',':=P',':-P',':p',':=p',':-p',
			':S',':-S',':\\=S',':s',':-s',':=s',
			':D',':=D',':-D',':d',':=d',':-d',
		];

var sreplace = [
			"82","82","82","82",
			"84","84",
			"74","74","74","74",
			"1", "2", "3", 
			"4", "4", "4",
			"5", "6", "7", "7",
			"8","9","10",
			"11","12","13",
			"14","15","15",
			"15","16","16",
			"16",
			"18","18","18","18","18","18","18",
			"19","19",
			"20","20","20","20","20","20",
			"21","21",
			"22","22","22",
			"23","23","23",
			"25","25","25","25",
			"26",
			"27","27","27","27","27",
			"28","28","28",
			"29",
			"30","30","30",
			"31",
			"32","32",
			"33","33","33","33",
			"34",
			"35","35","35","35","35","35","35","35","35",
			"36",
			"37",
			"39",
			"40","40","40","40","40","40","40",
			"41","41","41","41","41","41","41","41","41",
			"42",
			"43",
			"44",
			"45",
			"46",
			"47",
			"48","48",
			"49","49",
			"50","50",
			"51",
			"52","52","52","52",
			"53",
			"54",
			"55",
			"56",
			"57",
			"58",
			"59",
			"60",
			"61",
			"62","62","62",
			"63","63","63",
			"64",
			"65",
			"66",
			"67",
			"68",
			"69",
			"70",
			"71",
			"72",
			"73","73",
			"75",
			"76",
			"77","77",
			"78","78",
			"79","79",
			"80",
			"81","81","81",
			"83",
			"24","24","24","24","24","24",		
			"38","38","38","38","38","38",
			"17","17","17","17","17","17",				
			];

String.prototype.addSmileys = function() {
  var replaceString = this;
  var regex; 
  for (var i = 0; i < sfind.length; i++) {
    regex = new RegExp(sfind[i], "g");
    replaceString = replaceString.replace(regex, '<img class="img_smileys" src="'+ajaxpath+'images/smiley/'+sreplace[i]+'.gif" height="30"  width="30" />');
  }
  return replaceString;
};


var rreplace = [
			":finger:", ":bandit:", ":drunk:", 
			":smoking:", ":smoke:", ":ci:",
			":toivo:", ":rock:", ":headbang:", ":banghead:",
			":bug:",":fubar:",":poolparty:",
			":swear:",":tmi:",":heidy:",
			":mooning:",':\\)',':=\\)',
			':-)',':\\(',':=\\(',
			':-(',
			':D',':=D',':-D',':d',':=d',':-d',
			'8)','8=\\)','8-\\)','B\\)','B=\\)','B-\\)',':cool',
			';)',';-\\)',
			';(',':-\\(',';\\(',';-\\(',';=\\(',':=\\(',
			':sweat','\\(:\\|',
			':|',':=\\|',':-\\|',
			':*',':=\\*',':-\\*',
			';p',':=P',':-P',':p',':=p',':-p',
			':blush:',':\\$',':-\\$',':\\=\\=\\$', 
			':^)',
			'|-)','I-\\)','i\\=\\)',':snooze:',':sleepy:',
			'|(','\\|-\\(','\\|\\=\\(',
			':inlove:',
			']:)','\\>:\\)',':grin:',
			':talk:',
			':yawn:','\\|-\\(\\)',
			':puke:',':\\&',':-\\&',':=\\&',
			':doh',
			':@',':\\-@',':\\=@','x\\(','x-\\(','x=\\(','X\\(','X-\\(','X-\\(',
			':wasntme:',
			':party:',
			':S',':-S',':\\=S',':s',':-s',':=s',
			':mm:',
			'8-I','8-\\|','8\\|','B\\|','8=\\|','B=\\|',':nerd:',   //image 40.gif
			':x',':-x',':X',':-X',':#',':-#',':=x',':=X',':=#',
			':hi:',
			':call:',
			':devil:',
			':angel:',
			':envy:',
			':wait:',
			':bear:',':hug:',
			':makeup:',':kate:',
			':giggle:',':chuckle:',
			':clap:',
			':think:',':\\?',':-\\?',':=\\?',
			':bow:',
			':rofl:',
			':whew:',
			':happy:',
			':smirk:',
			':nod:',
			':shaking:',
			':punch:',  //image 60
			':emo:',
			':ok:','\\(y\\)','\\(Y\\)',
			':no:','\\(n\\)','\\(N\\)',
			':handshake',
			'<3',
			'</3',
			':mail:',
			':flower:',
			':rain:',
			':sun:',
			':time:',
			':music:',
			':film:',':movie:',
			':phone:',':mobile:',':mp:',':ph:',
			':coffee:',
			':pizza:',
			':cash:',':\\$:',
			':muscle:',':flex:',
			':cake:','\\(\\^\\)',
			':beer:',
			':drink:','\\(d\\)','(D)',
			':dance:','\o/','\:D/',':d/',
			':ninja:',
			':star:','(*)'
			];

var rfind = [
			"1", "2", "3", 
			"4", "4", "4",
			"5", "6", "7", "7",
			"8","9","10",
			"11","12","13",
			"14","15","15",
			"15","16","16",
			"16",
			"17","17","17","17","17","17",
			"18","18","18","18","18","18","18",
			"19","19",
			"20","20","20","20","20","20",
			"21","21",
			"22","22","22",
			"23","23","23",
			"24","24","24","24","24","24",
			"25","25","25","25",
			"26",
			"27","27","27","27","27",
			"28","28","28",
			"29",
			"30","30","30",
			"31",
			"32","32",
			"33","33","33","33",
			"34",
			"35","35","35","35","35","35","35","35","35",
			"36",
			"37",
			"38","38","38","38","38","38",
			"39",
			"40","40","40","40","40","40","40",
			"41","41","41","41","41","41","41","41","41",
			"42",
			"43",
			"44",
			"45",
			"46",
			"47",
			"48","48",
			"49","49",
			"50","50",
			"51",
			"52","52","52","52",
			"53",
			"54",
			"55",
			"56",
			"57",
			"58",
			"59",
			"60",
			"61",
			"62","62","62",
			"63","63","63",
			"64",
			"65",
			"66",
			"67",
			"68",
			"69",
			"70",
			"71",
			"72",
			"73","73",
			"74","74","74","74",
			"75",
			"76",
			"77","77",
			"78","78",
			"79","79",
			"80",
			"81","81","81",
			"82","82","82","82",
			"83",
			"84","84"
			];

/*

var rreplace = [
			":finger:", ":bandit:", ":drunk:", 
			":smoking:", ":smoke:", ":ci:",
			":toivo:", ":rock:", ":headbang:", ":banghead:"
			];

var rfind = [
			"1", "2", "3", 
			"4", "4", "4",
			"5", "6", "7", "7"
			];
*/
String.prototype.addSmileysCode = function() {
  var replaceString = this;
  var regex; 
  for (var i = 0; i < rfind.length; i++) {
    regex = new RegExp(ajaxpath+'images/smiley/'+rfind[i]+'.gif', "g");
    replaceString = replaceString.replace(regex, rreplace[i]);
  }
  return replaceString;
};


Array.prototype.remove_element = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

var online_users=[];
online_users.push(curuser);
				
var openwindowlist=[];
var visiblewinlist=[];
var arrindex=0;
var userstatus=[];
var myavailability=0;
var myoldavailability=0;
var window_focus;
var lmid=-1;

	
var tmpusrarr = [];

$(window).focus(function() {
    window_focus = true;
})
.blur(function() {
	window_focus = false;
});
$(document).ready(function(){
	//refreshUserStatus();
	setTimeout(refreshUserStatus,2000);
	//refreshUserWritingStatus();
	setTimeout(refreshUserWritingStatus,1000);
	//refresh userlists
	//setTimeout(refreshUserLists,60000);
});
 //custom_avl_setting
 
function setavailability(){	
	if($("#cmb_availability").val()==0){
		myoldavailability=myavailability;
		myavailability=$("#cmb_availability").val();
	
		$( ".chatbox" ).each(function( index ) {
				var ucname=$(this).attr("data-username");
				if(ucname!="JSRPLABLUNAME")
					removewindow($(this).attr("data-username"));
					
		});
		$(".chatbox_friends").css("opacity","0.6");
		var mtop=$("#chatbox_userlist").css('margin-top');
			mtop=parseInt(mtop);
			if(mtop=='0'){
				$("#chatbox_userlist").css('margin-top','279px');
				$("#chatbox_userlist").children(".chatboxbodyarea").hide('slow');
			}
			else{
				$("#chatbox_userlist").css('margin-top','0px');
				$("#chatbox_userlist").children(".chatboxbodyarea").show('slow');
			}	
			
		$.ajax({
			type: 'GET',
			url: ajaxpath+'ajax_chat/setavailability.php',
			dataType: 'html',
			timeout: 3000,
			data:  {'status':$("#cmb_availability").val()},
			cache: false, 
			success:function(html){		
				if($("#cmb_availability").val()==1){
				//	location.reload();
				}
			},
			error:function(jqXHR, textStatus){
			}
		});		
		
		if($(".chatboxdropdown").css('display') == 'block')
		$(".chatboxdropdown").hide();

		$('#custom_avl_setting').hide();
		return true;
		//$('.choose_users').show();
	}
	/*if($("#cmb_availability").val()==3){
		$('.choose_users').show();	
		$(".chatboxdropdown").hide();
		return true;
	//	alert(1);
	}*/
	else{
		myoldavailability=myavailability;
		myavailability=$("#cmb_availability").val();
		$.ajax({
			type: 'GET',
			url: ajaxpath+'ajax_chat/setavailability.php',
			dataType: 'html',
			timeout: 3000,
			data:  {'status':$("#cmb_availability").val()},
			cache: false, 
			success:function(html){		
				if($("#cmb_availability").val()==1){
					
					$('#custom_avl_setting').hide();	
					$(".chatbox_friends").css("opacity","1");

				//	location.reload();
				}
			},
			error:function(jqXHR, textStatus){
			}
		});
		
		if($(".chatboxdropdown").css('display') == 'block')
		$(".chatboxdropdown").hide();
	}
}

function loadchatwindow(user,pfocus){
	//alert(user);
	//alert(pfocus);
	if(myavailability==0){
		return false;
	}
	if(tmpusrarr.indexOf(user.toLowerCase())<0){
		var data=$("#div_chatbox_samp").html();
		//JSRPLABLUNAME
		var ndata=data.replace(/JSRPLABLUNAME/g,user); 
		$('#chat_bar').prepend(ndata);
		
		tmpusrarr.push(user.toLowerCase());
		
		openwindowlist.push(user);
		initializeChat();//initalize chart

		gethistoricmessages(user,1,0);//calling gethistoricmessages

	    //gethistoricmessages(user,1,1000);

		var intevalId=setTimeout(function() { getmessagesbyUser(user);},5000);//calling getmessagesbyuser function
       //var intevalId=setInterval( function() { getmessagesbyUser(user);},2000);
		//data-int-id
		$("#chatbox_"+user).attr("data-int-id",intevalId);
		if(pfocus==1){
			$("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxinput").children(".chatboxtextarea").focus();
		}
	}
	else{
		$("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxinput").children(".chatboxtextarea").focus();
	}
	return true;			
}

function removewindow(user){
	var intervalId=$("#chatbox_"+user).attr("data-int-id");	
	$("#chatbox_"+user).remove();
	openwindowlist.remove_element(user.toLowerCase());
	openwindowlist.remove_element(user);
	
	clearInterval(intervalId);
	initializeChat();
	//removewindow
}
function postMessage(touser,message){
	if(trim(message)=="") return false;
	$.ajax({
		type: 'POST',
		url: ajaxpath+'ajax_chat/postmessages.php',
		dataType: 'html',
		data:  {'cuser':curuser,'msgto':touser,'message':message},
		cache: false, 
		timeout: 3000,
		success:function(html){
			//alert(html);
			if(parseInt(html)==1){
				var d=new Date();
				var nd=getTimenow();
			}else{
				alert(html);	
			}
		},
		error:function(jqXHR, textStatus){
		//alert(textStatus+": Please check your internet connection!");
		//$("#txt_"+touser+"_messages").html(message);
		}
	});
}

function postOwinlist(){
	$.ajax({
		type: 'POST',
		url: ajaxpath+'ajax_chat/openwinlist.php',
		dataType: 'html',
		timeout: 3000,
		data:  {'owinlist':JSON.stringify(openwindowlist)},
		cache: false, 
		success:function(html){
//			setTimeout(postOwinlist,2000);		
		},
		error:function(jqXHR, textStatus){
		}
	});
}

function ltmparr(){
	tmpusrarr = [];
	for (var i = 0; i < openwindowlist.length; i++) {
		tmpusrarr.push(openwindowlist[i].toLowerCase());
	}	
}
function getmessages(){
	if($("#cmb_availability").val()==0){
		return false;
	}
	var olmid=lmid;	
	$.ajax({
		type: 'POST',
		url: ajaxpath+'ajax_chat/getmessages.php',
		dataType: 'json',
		data:  {'cuser':curuser,'lmid':lmid},
		cache: false, 
		success:function(html){
			if(parseInt(html.count)>0){
				lmid=parseInt(html.lmid);
				for(i=0;i<html.count;i++){
					var msgFrom=html.messages[i]['from_user'];
					var msgTo=html.messages[i]['to_user'];
					if(curuser!=msgFrom){
						if(tmpusrarr.indexOf(msgFrom.toLowerCase())<0){
							if(olmid<lmid) //This condition added to avoid repetative auto apening window after closing
							{
								loadchatwindow(msgFrom,0);
							}
							$("#chatbox_"+msgFrom).children(".chatboxbodyarea").children(".histmessages").attr("data-fmsgid",html.messages[i]['id']); 				

							//play_sound();
						}
						else{
							if($("#chatbox_"+msgFrom).index()>3)
								movetofirst(msgFrom);
							//	play_sound();
						}
					}
				}
			}
			setTimeout(getmessages, 3000);
		},
		error:function(jqXHR, textStatus){
			setTimeout(getmessages, 3000);
		}
	});
}

function notification_on_title(message){
	if(window_focus==false){
		$('title').html(message);
	}
	else
		$('title').html(pageTitle);
}

function getmessagesbyUser(friendUsrName){	
	//alert(friendUsrName);
	if(tmpusrarr.indexOf(friendUsrName.toLowerCase())<0){
		return false;
	}
	if($("#cmb_availability").val()==0){
		var intevalId=setTimeout( function() { getmessagesbyUser(friendUsrName);},15000);
		return false;
	}
	var lastmsgid=$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").attr("data-lmsgid"); 
	$.ajax({
		type: 'GET',
		url: ajaxpath+'ajax_chat/getmessagesuserwise.php',
		dataType: 'json',
		data:  {'cuser':curuser,'friend':friendUsrName,'lmid':lastmsgid},
		cache: false,
		success:function(html){
		var nofmsg=parseInt(html.count);
			if(nofmsg>0){
				$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").attr("data-lmsgid",html.messages[nofmsg-1]['id']);
				var msgset="";
				for(i=0;i<nofmsg;i++){
					var msgFrom=html.messages[i]['from_user'];
					//alert(msgFrom);
					if(msgFrom==curuser)
						var msgSender="<a style='color:#003;' href='javascript:void(0);'>"+msgFrom+"</a>";
					else
						var msgSender="<a href='javascript:void(0);'>"+msgFrom+"</a>";
					
					var msg=html.messages[i]['messagebody']	
					msg= msg.replace(/[\r\n]/g, "<br />");
					msg=msg.addSmileys();
					var time=html.messages[i]['datetime'];
					msgset+="<div class='livechat_messages'><div class='timeofmessage'>"+time+"</div><div class='livechat_messages_body'><span class='span_msg_from'>"+msgSender+": </span>"+msg+"</div></div>";
					
					//play_sound();
					var user=friendUsrName;
					notification_on_title('New message from '+user);
				}

				$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".chatboxcontent").append(msgset);
				
				//scroll
				if($("#chatbox_"+user).attr("data-auto-scroll")==1){
					$("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxcontent").scrollTop($("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxcontent")[0].scrollHeight);
				}
				//scroll end
			}
			var intevalId=setTimeout( function() { getmessagesbyUser(friendUsrName);},2000);
		},
		error:function(jqXHR, textStatus){
			var intevalId=setTimeout( function() { getmessagesbyUser(friendUsrName);},2000);
		}
	});
	//getmessages.php	
}
function updatemsgbox(user,msg,title,time){
	msg=msg.addSmileys();
	
	//=addsmileys(msg);
	
	$("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxcontent").append("<div class='livechat_messages'><div class='timeofmessage'>"+time+"</div><div class='livechat_messages_body'><span class='span_msg_from'>"+title+": </span>"+msg+"</div></div>");	
//	$("#chatbox_"+user)
	
	if(!($("#chatbox_"+user).children(".div_min_chatbox").children(".chatboxhead").hasClass('chatboxactive'))){
		$("#chatbox_"+user).children(".div_min_chatbox").children(".chatboxhead").addClass('chatboxnewmsg');	
	}
	
	play_sound();
	
	if($("#chatbox_"+user).attr("data-auto-scroll")==1){
		$("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxcontent").scrollTop($("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxcontent")[0].scrollHeight);
	}
//	else
//		alert($("#chatbox_"+user).attr("data-auto-scroll"));
}
function movetofirst(user){
//	var user=$("#username").val();
	$("#chatbox_"+user).prependTo('.chat_bar');
}
//userstatus;
function refreshUserStatus(){
	ltmparr();
	if(myavailability==0){
		$(".chatbox_friends").css("opacity","0.6");
	}
	$.ajax({
		type: 'POST',
		url: ajaxpath+'ajax_chat/availability.php',
		dataType: 'json',
		data:  {},
		cache: false,
		timeout: 5000, 
		success:function(html){
				//alert(html);
				//$("body").append(html);
				var len=$('.statuslight').length;
				var count=html.count;
				var custcount=html.custcount;
				var custuserarr=html.customusrs;
				
				online_users=[];
				online_users.push(curuser);
					
				for(i=0;i<len;i++){
					var userarr=html.users;
					var tusername=getUnameFrm_ID($('.statuslight')[i].id);
					
					online_users.push(tusername);
					
					if(userarr.indexOf(tusername)<0){
						//offline
						$('#'+tusername+"_status_id").html("<div class=\"greyLight\"></div>");
					}else{
						//online
						$('#'+tusername+"_status_id").html("<div class=\"greenLight\"></div>");
						if($("#chatbox_friends_"+tusername).index()>=count)
							$("#chatbox_friends_"+tusername).prependTo('#favorites_list');
					}
					//custcount
					if(myavailability==3){
						if(custuserarr.indexOf(tusername)<0){
							//Fade
							$("#chatbox_friends_"+tusername).css("opacity","0.6");
						}else{
							//Normal	
							$("#chatbox_friends_"+tusername).css("opacity","1");
							if($("#chatbox_friends_"+tusername).index()>=custcount)
								$("#chatbox_friends_"+tusername).prependTo('#favorites_list');
						}
					}
				}
				setTimeout(refreshUserStatus,2000);
		},
		error:function(jqXHR, textStatus,error){
			setTimeout(refreshUserStatus,2000);
		}
	});
}
//userlists;
function refreshUserLists(){
	$.ajax({
		type: 'POST',
		url: ajaxpath+'ajax_chat/userlist.php',
		dataType: 'html',
		data:  {},
		cache: false,
		timeout: 5000, 
		success:function(data){
			//alert(data);
			$("#chatbox_userlist").children(".chatboxbodyarea").children(".chatboxcontent").html(data)
			initializeChat();//initalize chart
			setTimeout(refreshUserLists,60000);
		},
		error:function(jqXHR, textStatus,error){
			setTimeout(refreshUserLists,60000);
		}
	});
}
function refreshUserWritingStatus(){
	//return false;
	//chatbox_writing_stat_JSRPLABLUNAME
	$.ajax({
		type: 'POST',
		url: ajaxpath+'ajax_chat/getwritingstatus.php',
		dataType: 'json',
		cache: false, 
		timeout: 1000,
		success:function(data){
			//alert(data.count);
			var len=openwindowlist.length;
			if(parseInt(data.count)>0){
				var userarr=data.users;
				for(i=0;i<len;i++){
					var tusername=openwindowlist[i];
					if(userarr.indexOf(tusername)<0 && $('#chatbox_writing_stat_'+tusername).css('display')=='block'){
						$('#chatbox_writing_stat_'+tusername).hide();
					}else if($('#chatbox_writing_stat_'+tusername).css('display') == 'none'){
						$('#chatbox_writing_stat_'+tusername).show();
					}
				}
			}else{
				$('.writing_notification').hide();				
			}
			setTimeout(refreshUserWritingStatus,1000);
		},
		error:function(jqXHR, textStatus,error){
			setTimeout(refreshUserWritingStatus,1000);
		}
	});
}
function updateWritingStat(user){
	$.ajax({
		type: 'POST',
		url: ajaxpath+'ajax_chat/writingstat.php',
		dataType: 'json',
		data:  {'to_user':user},
		timeout: 3000,
		cache: false, 
		sync: true,
		success:function(html){
			//alert(html);
		},
		error:function(jqXHR, textStatus,error){
		//alert(user+":"+error);
		}
	});
}
function gethistoricmessages(friendUsrName,isPush,delay){
	if($("#cmb_availability").val()==0){
		return false;
	}
	if(delay==0){
		setTimeout(function(){callhistmsg(friendUsrName,isPush);},1500)
//		callhistmsg(friendUsrName,isPush);
		$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").addClass("histmsgscroolimg"); 
	}else{
		setTimeout(function(){callhistmsg(friendUsrName,isPush);},3000)
		//chatbox_JSRPLABLUNAME
		$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").addClass("histmsgscroolimg"); 
	}
}
function callhistmsg(friendUsrName,isPush){
	var firstmsgid=$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").attr("data-fmsgid");
	$.ajax({
		type: 'GET',
		url: ajaxpath+'ajax_chat/getmessages.php',
		dataType: 'json',
		data:  {'cuser':curuser,'friend':friendUsrName,'firstid':firstmsgid},
		cache: false,
		timeout: 7000, 
		success:function(html){
			if(parseInt(html.count)>0){
				//Set first message id
				$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").attr("data-fmsgid",html.messages[html.count-1]['id']); 				
				
				//Set last message id
				if($("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").attr("data-lmsgid")==-1){
					//alert(html.messages[0]['id']);
					$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").attr("data-lmsgid",html.messages[0]['id']); 	
				}
				
				for(i=0;i<html.count;i++){
					var msgFrom=html.messages[i]['from_user'];
					if(msgFrom==curuser)
						var msgSender="<a style='color:#003;' href='javascript:void(0);'>"+msgFrom+"</a>";
					else
						var msgSender="<a href='javascript:void(0);'>"+msgFrom+"</a>";
						
					var msg=html.messages[i]['messagebody'];												
					msg= msg.replace(/[\r\n]/g, "<br />");																				
					updatemsghistbox(friendUsrName,msg,msgSender,html.messages[i]['datetime']);					
				}
				if(isPush==1){
					$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".chatboxcontent").scrollTop($("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".chatboxcontent")[0].scrollHeight);
				}
			}
			$("#chatbox_"+friendUsrName).children(".chatboxbodyarea").children(".histmessages").removeClass("histmsgscroolimg"); 
			postOwinlist();
		},
		error:function(jqXHR, textStatus){
			//alert(textStatus);
		}
	});
	//getmessages.php	
}
function updatemsghistbox(user,msg,title,time){
	msg=msg.addSmileys();
	$("#chatbox_"+user).children(".chatboxbodyarea").children(".chatboxcontent").prepend("<div class='livechat_messages'><div class='timeofmessage'>"+time+"</div><div class='livechat_messages_body'><span class='span_msg_from'>"+title+": </span>"+msg+"</div></div>");
}
function getUnameFrm_ID(uname){
	uname=uname.replace("_status_id","");
	return uname;
}
function trim(str){
	return str.replace(/^\s+|\s+$/g, '');
}
function getTimenow(){
	var now=new Date();
	var hh=now.getHours();
	var mm=now.getMinutes();
	if(mm<10)
		mm='0'+mm;

	if(hh<10)
		hh='0'+hh;
	var ap='';
	if(hh<=11)
		ap=' am';
	else
		ap=' pm';
		
	if(hh>12){
		hh=hh-12;	
	}
			
	return hh+":"+mm+ap;
}
function play_sound(){
	if(window_focus==false){
		$('#notification_sound')[0].play();
	}
}
function setSelectionRange(input, selectionStart, selectionEnd) {
  if (input.setSelectionRange) {
    input.focus();
    input.setSelectionRange(selectionStart, selectionEnd);
  }
  else if (input.createTextRange) {
    var range = input.createTextRange();
    range.collapse(true);
    range.moveEnd('character', selectionEnd);
    range.moveStart('character', selectionStart);
    range.select();
  }
}
function setCaretToPos (input, pos) {
  setSelectionRange(input, pos, pos);
}
function initializeChat(){
	//alert("hello");
	$(".chatboxdropdown").unbind('mouseleave');
	$(".chatboxdropdown").mouseleave(function(){
		var obj=$(this);
		setTimeout(function(){$(obj).hide()},15000);	
	});	
	
	$("#chatbox_settings").unbind('click');
	$("#chatbox_settings").click(function(){
		$(this).siblings(".chatboxdropdown").toggle();	
	});	
	//	alert(1);
	//input_image
	$('.input_image').unbind('click');
	$('.input_image').click(function(){
		$(this).parent().parent().parent().siblings(".splash_drag_drop").fadeIn(1000);
		$(this).parent().parent().parent().siblings(".splash_drag_drop").fadeOut(2000);			
	});
	//Smiley toggle
	$('.input_smiley').unbind('click');
	$('.input_smiley').click(function(){
		//	alert($(this).parent().parent().parent().eq(3).attr("id"));
		
		$(this).parent().parent().siblings(".div_smiley").fadeToggle('fast');
	});
	//addSmileysCode
	//Image to smiley code
	$('.img_smileys').unbind('click');
	$('.img_smileys').click(function(){
		var smCode=$(this).attr('src');
		smCode=smCode.addSmileysCode(smCode);
		$(this).parent().fadeToggle('fast');
		var ptext=$(this).parent().parent().children(".chatboxinput").children(".chatboxtextarea").val();
		$(this).parent().parent().children(".chatboxinput").children(".chatboxtextarea").val(ptext+" "+smCode+" ");
		
		$(this).parent().parent().children(".chatboxinput").children(".chatboxtextarea").focus().val('').val(ptext+" "+smCode+" ");
	});
	//Smiley hide
	$('.chatboxtextarea').unbind('click');
	$('.chatboxtextarea').click(function(){
		//	alert($(this).parent().parent().parent().eq(3).attr("id"));
		$(this).attr("data-active",1);
		$(this).parent().siblings(".div_smiley").hide(500);
//		$(this).parent().parent().parent().children(".div_min_chatbox").children(".chatboxhead").addClass('chatboxactive');	
	});
	$('.chatboxtextarea').unbind('focus');
	$('.chatboxtextarea').focus(function(){
		$(this).parent().parent().parent().children(".div_min_chatbox").children(".chatboxhead").addClass('chatboxactive');	
		$(this).parent().parent().parent().children(".div_min_chatbox").children(".chatboxhead").removeClass('chatboxnewmsg');	
	});
	//Toggle on minimize event
	$('.div_min_chatbox').unbind('click');
	$(".div_min_chatbox").click(function(){
		toggleHeight(this);
	});
	//Close all event
	$('.chatbox_close_all').unbind('click');
	$(".chatbox_close_all").click(function(){
		$( ".chatbox" ).each(function( index ) {
			var ucname=$(this).attr("data-username");
			if(ucname!="JSRPLABLUNAME")
				removewindow($(this).attr("data-username"));
				postOwinlist();				
		});
		
		if($(".chatboxdropdown").css('display') == 'block')
			$(".chatboxdropdown").hide();
	});
	//Close event
	$('.chatbox_close').unbind('click');
	$(".chatbox_close").click(function(){
		var myid=$(this).parent().parent().parent().parent().attr('id');
		//alert(myid);
		//$("#"+myid).remove();
		removewindow($("#"+myid).attr("data-username"));
		postOwinlist();
	});
	$('.frnd_list_minimize').unbind('click');
	$(".frnd_list_minimize").click(function(){
		//alert(mtop);
		var mtop=$("#chatbox_userlist").css('margin-top');
		mtop=parseInt(mtop);
		if(mtop=='0'){
			$("#chatbox_userlist").css('margin-top','279px');
			$("#chatbox_userlist").children(".chatboxbodyarea").hide('slow');
			if($(".chatboxdropdown").css('display') == 'block')
				$(".chatboxdropdown").hide();
		}else{
			$("#chatbox_userlist").css('margin-top','0px');
			$("#chatbox_userlist").children(".chatboxbodyarea").show('slow');
			if($(".chatboxdropdown").css('display') == 'block')
				$(".chatboxdropdown").hide();
		}
	});
	//Populate Smiley
	//	$('.div_smiley').html($('.div_smiley_samp').html());
	$(".chatbox_friends").click(function(){ 
		var username=$(this).attr("data-username");
		loadchatwindow(username,1);
		if($(".chatboxdropdown").css('display') == 'block')
			$(".chatboxdropdown").hide();
	});
	$('.chatboxtextarea').unbind('keydown');
	$(".chatboxtextarea").keydown(function(event){
		var to_user=$(this).attr("data-to-user");
		if(event.keyCode == 13 && event.shiftKey == 0)  {
			event.preventDefault();
			var message=$(this).val();
			message = message.replace(/^\s+|\s+$/g,"");
			$(this).val('');
			$(this).focus();
			postMessage(to_user,message);//calling post message function
		}else{
			updateWritingStat(to_user);//calling update writing status function
		}			
	});
	$('.chatboxtextarea').unbind("blur");
	$('.chatboxtextarea').blur(function(){
		$(this).data("data-active",0);
		$(this).parent().parent().parent().children(".div_min_chatbox").children(".chatboxhead").removeClass('chatboxactive');	
	});
	//Historic messages
	$('.chatboxcontent').unbind("scroll");
	$('.chatboxcontent').scroll(function(e){
	//histmessages
		var touser="";
		if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight-50){
					//alert('End');
				//	$('#txt_auto_scroll_enable_'+user).val(1);
		}else{
			// Diable auto scroll
				//$('#txt_auto_scroll_enable_'+user).val(0);
		}
		
		if($(this).scrollTop() <= 0){
			//Prepend more data
			gethistoricmessages($(this).attr('data-username'),0,1);
			var noofsclr=$(this).attr('data-scrolled');
			$(this).attr('data-scrolled',noofsclr+1);
			if(noofsclr>4)
				$(this).parent().children(".div_full_hist").show();
		}else
			$(this).parent().children(".div_full_hist").hide();
	});
	//Historic messages
	//setTimeout(postOwinlist,1000);
	$('#chatbox_userlist_area').unbind("click");
	$('#chatbox_userlist_area').click(function(e){
			if($(".chatboxdropdown").css('display') == 'block')
			$(".chatboxdropdown").hide();
	});
	
	initalizeDragDrop();//calling drag and drop function here
}
function autoPopOpenWin(){
//		return false;
		$.ajax({
			type: 'GET',
			url: ajaxpath+'ajax_chat/getopenwinlist.php',
			dataType: 'json',
			cache: false,
			timeout: 10000, 
			success:function(html){
				if(parseInt(html.count)>0){
					for(i=0;i<html.count;i++){
						var user=html.list[i];
						if(tmpusrarr.indexOf(user.toLowerCase())<0){
							loadchatwindow(html.list[i],0);
						}
					}
					initializeChat();
						//loadchatwindow(user,pfocus)			
				}
				//initializeChat();
			}
		});
//		initializeChat();
	}
	
function toggleHeight(obj){
	var mtop=$(obj).css('margin-top');
	mtop=parseInt(mtop);
	if(mtop==0)
		$(obj).animate({'margin-top':'279px'},'slow');
	else
		$(obj).animate({'margin-top':'0px'},'slow');
}
//initializeChat();
//setInterval(getmessages, 1000);
setTimeout(getmessages, 2000);	