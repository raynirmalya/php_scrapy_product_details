var jsonContentObj={};
$(function() {
    $('#side-menu').metisMenu();
    xEditor.init({min_height:"400px"});
});

$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
    function checkBlank(obj){
		if(obj.val()==""){
			obj.focus();
			obj.closest("div").next("div").addClass("error");
			obj.closest("div").next("div").html('<span class="alert-type">ERROR: </span><span class="alert-msg">'+obj.attr("placeholder")+' required.</span>');
			//obj.attr('title', obj.attr("placeholder")+" required.").tooltip('fixTitle').tooltip('show', {placement: 'right'});
			//$(".tooltip").css({"border":"none","background":"none"});
			return false;
		}else{
			$(".alert-box").removeClass("error");
			$(".alert-box").html("");
			return true;
		}
	}
    $("#add-tutorial-details").click(function(){
    	var obj={};
    	if(!checkBlank($("#tutorial-id"))){
    		return false;
    	}else if($("#tutorial-id").val()=="other" && !checkBlank($("#tutorial-new-name"))  ){
    		return false;
    	}else if(!checkBlank($("#chapter-name"))){
    		return false;
    	}else if(!checkBlank($("#page-link"))){
    		return false;
    	}else{
    		if($("#tutorial-id").val()=="other"){
    			obj.tutorialName=$("#tutorial-new-name").val();
    		}else{
    			obj.tutorialId=$("#tutorial-id").val();
    		}
        	obj.codingLanguage=$("#coding-lang").val();
        	obj.chapterName=$("#chapter-name").val();
        	obj.pageLink=$("#page-link").val();
        	jsonContentObj.tutorialDetails=obj;
        	$(".tutorial-details-tab").removeClass("active");
        	$(".content-tab").addClass("active");
        	$(".tutorial-details-container").addClass("hidden");
        	$(".content-container").removeClass("hidden");
    	}

    });
    $("#back-totravel-details").click(function(){
    	$(".tutorial-details-tab").addClass("active");
    	$(".content-tab").removeClass("active");
    	$(".tutorial-details-container").removeClass("hidden");
    	$(".content-container").addClass("hidden");
    });
    $("#add-content").click(function(){
    	var obj={};
    	var content="";
    	content=$(".xeditor").html();
    	obj.content=$(".xeditor").html();
    	jsonContentObj.contentDetails=obj;
    	$("#content-input").val(content);
    	$(".preview-tab").addClass("active");
    	$(".content-tab").removeClass("active");
    	$(".preview-container").removeClass("hidden");
    	$(".content-container").addClass("hidden");
		var reqKey=Math.random();
		document.getElementById("content-form").action="preview.php?x="+reqKey;
		document.getElementById("preview-iframe").contentWindow.name = "preview";
		document.getElementById("content-form").submit();
    });
    $("#back-tocontent").click(function(){
    	$(".preview-tab").removeClass("active");
    	$(".content-tab").addClass("active");
    	$(".preview-container").addClass("hidden");
    	$(".content-container").removeClass("hidden");
    });
    $("#next-tosave").click(function(){
    	$(".preview-tab").removeClass("active");
    	$(".submit-tab").addClass("active");
    	$(".submit-container").removeClass("hidden");
    	$(".preview-container").addClass("hidden");
    });
    $("#publish-content").click(function(){
    	var obj={};
    	obj=jsonContentObj.contentDetails;
    	obj.tags=$("#tags").val();
    	obj.status="1";
    	jsonContentObj.contentDetails=obj;
    	//console.log(JSON.stringify(jsonContentObj));
   	   $.ajax({
			url: "controller/tutorialsCRUDController.php",
			type: "POST",
			data:{saveTutorial:JSON.stringify(jsonContentObj)},
			success: function(data) {
				alert("Tutorial Published Successfully")
			},
			error:function(data,status,er) {
			}
		});
    });
    $("#save-content").click(function(){
    	var obj={};
    	obj=jsonContentObj.contentDetails;
    	obj.tags=$("#tags").val();
    	obj.status="0";
    	jsonContentObj.contentDetails=obj;
    	//console.log(JSON.stringify(jsonContentObj));
    	 $.ajax({
				url: "controller/tutorialsCRUDController.php",
				type: "POST",
				data:{saveTutorial:JSON.stringify(jsonContentObj)},
				success: function(data) {
					alert("Tutorial Saved Successfully")
				},
				error:function(data,status,er) {
				}
			});
    });
    $("#tutorial-id").change(function(){
    	if($(this).val()=="other"){
    		$("#tutorial-new-name").removeClass("hidden");
    	}else{
    		$("#tutorial-new-name").addClass("hidden");
    	}
    });
   function getTutorialsList(){
	   $.ajax({
			url: "controller/tutorialsCRUDController.php",
			type: "POST",
			data:{loadTutorialsList:""},
			success: function(data,status,er) {
				data=JSON.parse(data);
				//console.log(JSON.stringify(data))
				for(var i in data){
					$("#tutorial-id").append("<option value=''>Choose Tutorial</option>");
					$("#tutorial-id").append("<option value='"+data[i].tutorialId+"'>"+data[i].tutorialName+"</option>");
					$("#tutorial-id").append("<option value='other'>Other</option>");
				}
			},
			error:function(data,status,er) {
			}
		});
   }
   getTutorialsList();
});
