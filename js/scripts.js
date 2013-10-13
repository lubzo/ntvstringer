
(function($){
    
    var fkey, topLoader, startstopTimer, startstopCurrent = 0;;
    var fkeys=[];
	
    // Padding function
    function pad(number, length) {
	    var str = '' + number;
	    while (str.length < length) {str = '0' + str;}
	    return str;
    }

    // Initialize the progress loader
    $(function(){
        topLoader = $("#progress").percentageLoader({
            width           : 150, 
            height          : 150,
            controllable    : false,
            value           : '00:00:00',
            progress        : 0, 
            onProgressUpdate : function(val) {
                topLoader.setValue(Math.round(val * 100.0));
            }
        });
    });
	
	 function pollStatus(fkey){
        var statusData;
        
        $.ajax(jsNS.post_url, {
            type    : 'POST',
            dataType : 'json',
            async   : false,
            data    : { 'fkey' : fkey, 'type' : 'status' },
            success : function(data){
                statusData = data;
            },
            error   : function(){
                alert('Polling failed!');
                statusData = false;
            }
        });
        return statusData;
    }
    
    function startPolling(data){
        var currentTime, totalTime, hrCurrentTime, hrTotalTime, statData, intPoll, timer, count;
        count = 0;

        currentTime = data.time_encoded;
        totalTime   = data.time_total;
        
        timer = $.timer(function() {
		    var min     = parseInt(startstopCurrent/6000);
		    var sec     = parseInt(startstopCurrent/100)-(min*60);
		    var micro   = pad(startstopCurrent-(sec*100)-(min*6000),2);
		    var output  = "00"; if(min > 0) {output = pad(min,2);}
		    topLoader.setValue(output+":"+pad(sec,2)+":"+micro);
		    startstopCurrent+=7;
	    }, 70, true);
        timer.play();
        intPoll = setInterval(function(){
            if( currentTime < totalTime ) {
                statData = pollStatus(fkey, currentTime);
                //console.log(statData);
                if( !statData ){
                    alert('Bad data!');
                    //console.log(statData);
                    clearInterval(intPoll);
                    return false;
                }
                currentTime = statData.time_encoded;
                totalTime   = statData.time_total;
                hrCurrentTime = statData.time_encoded_min;
                hrTotalTime   = statData.time_total_min;
                
                topLoader.setProgress(currentTime / totalTime);
            }
            else {
                timer.stop();
                clearInterval(intPoll);
				$("#videoname").val("");
                alert('Finished!');
            }
        },1000);   
    }
    
    function convertVideo(filename,key){
		alert("im in babeyy 1");
       var filename = el.data('filename');
            fkey     = el.data('fkey');
        var params   = $('#ffmpeg_params').val();
		var videoname = $('#videoname').val();
		
			//alert("filename is "+filename+"  the key is "+key);
		
        fkeys.push(fkey);
        if(videoname != ""){
			
        $.ajax(jsNS.post_url, {
            type    : 'POST',
            dataType : 'json',
            async   : false,
            data    : { 
                'filename'  : filename,
                'fkey'      : fkey,
                'type'      : 'convert',
				'videoname' : videoname,
				'params'    : params
            },
            success : function(data){
                startPolling(data);
            },
            error   : function(){
                alert('Request failed!');
            }
        });
		}
		else
		{
			alert('video file name is needed!');
		}
    }

   $.Lightbox = {
	   convertVideo : function(filename,key){
		   alert("im in babey");
		        var filename = el.data('filename');
            fkey     = el.data('fkey');
        var params   = $('#ffmpeg_params').val();
		var videoname = $('#videoname').val();
		
			//alert("filename is "+filename+"  the key is "+key);
		
        fkeys.push(fkey);
        if(videoname != ""){
			
        $.ajax(jsNS.post_url, {
            type    : 'POST',
            dataType : 'json',
            async   : false,
            data    : { 
                'filename'  : filename,
                'fkey'      : fkey,
                'type'      : 'convert',
				'videoname' : videoname,
				'params'    : params
            },
            success : function(data){
                startPolling(data);
            },
            error   : function(){
                alert('Request failed!');
            }
        });
		}
		else
		{
			alert('video file name is needed!');
		}
		   }
	   };
	

    $(document).ready(function(){
        $('#output').change()(function(){
            alert("changed");
        });
    });

})(jQuery);