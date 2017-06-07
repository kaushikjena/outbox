// JavaScript Document
var rowCount=0;
function initalizeDragDrop(){
	$(".chatbox").unbind();
	$(".chatbox").on('dragenter', function (e)
	{
		e.stopPropagation();
		e.preventDefault();
		$(this).css('border', '2px solid #0B85A1');
	});
	
	$(".chatbox").on('dragover', function (e)
	{
		 e.stopPropagation();
		 e.preventDefault();
		$(this).css('border', '2px solid #0B85A1');
		 
	});
	
	$(".chatbox").on('drop', function (e)
	{
	 	var obj = $(".chatbox");
 		$(this).css('border', 'none');
		 e.preventDefault();
		 var files = e.originalEvent.dataTransfer.files;
		 //We need to send dropped files to Server
		 handleFileUpload(files,$(this).children(".chatboxbodyarea").children(".chatboxcontent"));
	});
	
	$(".chatbox").on('dragleave',function(e){		
		$(this).css('border', 'none');
	});
	
	$(document).on('dragenter', function (e)
	{
		e.stopPropagation();
		e.preventDefault();
	});
	$(document).on('dragover', function (e)
	{
	  e.stopPropagation();
	  e.preventDefault();
	});
	$(document).on('drop', function (e)
	{
		e.stopPropagation();
		e.preventDefault();
	});
}

function handleFileUpload(files,obj)
{
   for (var i = 0; i < files.length; i++)
   {
        var fd = new FormData();
        fd.append('file', files[i]);
 
        var status = new createStatusbar(obj); //Using this we can set progress.
        status.setFileNameSize(files[i].name,files[i].size);
        sendFileToServer(fd,status,obj,files[i].name);
 
   }
}

function sendFileToServer(formData,status,obj,fname)
{
    var uploadURL =ajaxpath+"ajax_chat/upload.php"; //Upload URL
    var extraData ={}; //Extra Data.
	formData.append("to_user",$(obj).parent().parent().attr("data-username"));
	//function postMessage(touser,message){

    var jqXHR=$.ajax({
            xhr: function() {
            var xhrobj = $.ajaxSettings.xhr();
            if (xhrobj.upload) {
                    xhrobj.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //Set progress
                        status.setProgress(percent);
                    }, false);
                }
            return xhrobj;
        },
		
        url: uploadURL,
        type: "POST",
        contentType:false,
        processData: false,
        cache: false,
        data: formData,
        success: function(data){
            status.setProgress(100);
		 	var fu_message='<a href="uploads/'+curuser+"_"+$(obj).parent().parent().attr("data-username")+"_"+fname+'" target="_new">New File: '+fname+'</a>';
			//alert($(obj).parent().parent().attr("data-username"));
		//	postMessage($(obj).parent().parent().attr("data-username"),fu_message);

            //$("#status1").append("File upload Done<br>");          
        }
    });
 
    status.setAbort(jqXHR);
}


function createStatusbar(obj)
{
     this.statusbar = $("<div class='statusbar'></div>");
     this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
     this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
     this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
     this.abort = $("<div class='abort'>X</div>").appendTo(this.statusbar);
     obj.append(this.statusbar);
 
    this.setFileNameSize = function(name,size)
    {
        var sizeStr="";
        var sizeKB = size/1024;
        if(parseInt(sizeKB) > 1024)
        {
            var sizeMB = sizeKB/1024;
            sizeStr = sizeMB.toFixed(2)+" MB";
        }
        else
        {
            sizeStr = sizeKB.toFixed(2)+" KB";
        }
 
        this.filename.html(name);
        this.size.html(sizeStr);
		
			$(obj).scrollTop($(obj)[0].scrollHeight);

    }
    this.setProgress = function(progress)
    {       
        var progressBarWidth =progress*this.progressBar.width()/ 100;  
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if(parseInt(progress) >= 100)
        {
            this.abort.hide();
		
        }
		
    }
    this.setAbort = function(jqxhr)
    {
        var sb = this.statusbar;
        this.abort.click(function()
        {
            jqxhr.abort();
            sb.hide();
        });
    }
	
}
$(document).ready(function(){
	initalizeDragDrop();	
});