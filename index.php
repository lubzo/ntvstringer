<?php
require 'config.php';
require 'functions.php';
?><!DOCTYPE html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>FFMPEG via PHP</title>
<meta name="description" content="" />
<meta name="author" content="" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="./js/jquery.percentageloader-0.1.js"></script>
<script src="./js/jquery.timer.js"></script>
<script>jsNS = {'base_url' : '<?php echo BASE_URL; ?>', 'post_url' : '<?php echo POST_URL; ?>'}</script>
<link rel="stylesheet" href="style.css" />
<script src="./js/scripts.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script language="javascript" type="text/javascript"> 

$(document).ready(function() { 
var progressbox 		= $('#progressbox'); //progress bar wrapper
var progressbar 		= $('#progressbar'); //progress bar element
var submitbutton 		= $("#SubmitButton"); //submit button
var statustxt 			= $('#statustxt'); //status text element
var myform 				= $("#UploadForm"); //upload form
var completed 			= '0%'; //initial progressbar value
var output 				= $("#output"); //ajax result output element
var theButton 				= $("#theButton"); //ajax result output element

var FileInputsHolder 	= $('#AddFileInputBox'); //Element where additional file inputs are appended


// adding and removing file input box
var i = $("#AddFileInputBox div").size() + 1;
$("#AddMoreFileBox").click(function () {
		event.returnValue = false;
		if(i < MaxFileInputs)
		{
			$('<span><input type="file" id="fileInputBox" size="20" name="file[]" class="addedInput" value=""/><a href="#" class="removeclass small2"><img src="images/close_icon.gif" border="0" /></a></span>').appendTo(FileInputsHolder);
			i++;
		}
		return false;
});

$("body").on("click",".removeclass", function(e){
		event.returnValue = false;
		if( i > 1 ) {
				$(this).parents('span').remove();i--;
		}
		
}); 

$(myform).ajaxForm({
	beforeSend: function() { //brfore sending form
	try{
		submitbutton.attr('disabled', ''); // disable upload button
		statustxt.empty();
		progressbox.show(); //show progressbar
		progressbar.width(completed); //initial value 0% of progressbar
		statustxt.html(completed); //set status text
		statustxt.css('color','#000'); //initial color of status text
	}
	catch(err)
	{
		alert("alert "+err.message);
	}
	
	},
	uploadProgress: function(event, position, total, percentComplete) { //on progress
		progressbar.width(percentComplete + '%') //update progressbar percent complete
		statustxt.html(percentComplete + '%'); //update status text
		if(percentComplete>50)
			{
				statustxt.css('color','#fff'); //change status text to white after 50%
			}else{
				statustxt.css('color','#000');
			}
			
		},
	complete: function(response) { // on complete
	    var resp = response.responseText;
		var resp1 = resp.split("^");
	    //theButton.html('<button data-filename="'+resp1[1]+'" data-fkey="'+resp1[0]+'">send It!</button> - '+resp1[0]+'<input type="text" id="videoname" name="videoname" placeholder="file name"/>');
		output.html(resp1[2]);//update element with received data
		myform.resetForm();  // reset form
		submitbutton.removeAttr('disabled'); //enable submit button
		//alert(""+resp1[2]);
		//$.Lightbox.convertVideo(resp1[0],resp1[1]);
		
		progressbox.hide(); // hide progressbar
		//$("#uploaderform").slideUp(); // hide form after upload
	}
});
});

</script> 
<style>
/* Progressbar */
#progressbox {
	border: 1px solid #0099CC;
	padding: 1px;
	position:relative;
	border-radius: 3px;
	margin: 10px;
	display:none;
	text-align:left;
}
#progressbar {
	height:20px;
	border-radius: 3px;
	background-color: #006699;
	width:1%;
}
#statustxt {
	top:3px;
	left:50%;
	position:absolute;
	display:inline-block;
	color: #000000;
	font-size: 12px;
	line-height: 15px;
}


</style>

</head>
<body>
    <div id="header-container">
        <header class="wrapper">
            <h1 id="title">NTV stringers</h1>
        </header>
    </div>
    <div id="main" class="wrapper">
        <!-- Progress Bar -->
        <div id="progress"></div>
        
        <form action="check.php" method="post" enctype="multipart/form-data" name="UploadForm" id="UploadForm"> 
       <table border="1"><tr><td> <span id="AddFileInputBox"><input id="fileInputBox" style="margin-bottom: 5px;" type="file"  name="file[]"/></span><button type="submit" class="pesa" id="SubmitButton"> upload</button>
</td></tr>
<tr><td>
<span id="output"></span>
</td></tr>
<tr><td>
<div id="progressbox"><div id="progressbar"></div ><div id="statustxt">0%</div >
</div>
</td></tr>
</table>
       </form>
       <ul id="source_videos">
        <li id="theButton">
        <button data-filename="tundacommedy.wmv" data-fkey="<?php echo hash('crc32', time() . "tundacommedy.wmv", false) ?>">send It!</button> - tundacommedy.wmv<input type="text" id="videoname" name="videoname" placeholder="file name"/></li>
        <?php// } ?>
        </ul>
        <p>
      <textarea hidden="hidden" id="ffmpeg_params" rows="3" cols="120">-acodec libvo_aacenc -ac 2 -ab 128 -ar 22050 -s 1024x768 -vcodec libx264 -fpre "<?php echo BASE_PATH; ?>ffmpeg\presets\libx264-ipod640.ffpreset" -b 1200k -f mp4 -threads 0</textarea>
        </p>
    </div>
</body>
</html><!--

sample commands ..

c:\ffmpeg\bin\ffmpeg.exe -i "C:\ffmpeg\FILE0055.MOV" -acodec libvo_aacenc -ac 2
-ab 128 -ar 22050 -s 1024x768 -vcodec libx264 -fpre "C:\ffmpeg\presets\libx264-i
pod640.ffpreset" -b 1200k -f mp4 -threads 0 FILE0055.mp4

c:\ffmpeg\bin\ffmpeg.exe -i "C:\ffmpeg\FILE0055.MOV" -fpre "C:\ffmpeg\presets\li
bx264-ipad.ffpreset" FILE0055.mp4

c:\ffmpeg\bin\ffmpeg.exe -i "C:\ffmpeg\GOD AT THE MOVIES - WEEK 1 - I LOST JESUS
.mp4" -vn -ar 44100 -ac 2 -f mp3 -ab 128000 "GOD AT THE MOVIES - WEEK 1 - I LOST
 JESUS.mp3" -->
