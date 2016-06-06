<?php
//include_once 'com/extractor/f.php';
//getFProductUrls(1);
error_reporting(1);
session_start();
include_once 'com/utility/RequestKeyGenerator.php';
$requestKeyObj = new RequestKeyGenerator();
$formKey=$requestKeyObj->getRequestKey();
?>
<html>
<head>
<link href="assests/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
<script src="assests/js/lib/jquery/jquery-1.11.2.min.js"></script>
<script src="assests/js/lib/bootstrap/bootstrap.js"></script>
</head>
<body>
<div class='container' style='width:50%'>
     <ul class="nav nav-tabs">
	    <li class="active"><a href="#product_insert">Product Insert</a></li>
	    <li><a href="#product_view">Product View</a></li>
	    <li><a href="#spec_management">Specification Management</a></li>
	    <li><a href="#menu3">Menu 3</a></li>
	  </ul>
	<div id="product_insert" class="tab-pane fade in active">
		<div class='row col-lg-10 col-md-10 col-sm-12'>
			<form role="form">
			  <div id='config'>
			  	<div class="form-group">
			  	  <label for="site">Affiliate Site Id:</label>
			  	  	<select class='siteId form-control'></select>
			  	</div>
			  </div>
			 </form>
			 <form action="controller/SaveProductDataController.php" id="saveProducts" name="saveProducts" method="POST">
			 	<input  type='hidden' id='baseUrl' name='baseUrl' />
			    <input  type='hidden' id='productBaseUrl' name='productBaseUrl'/>
			    <input  type='hidden' id='categoryId' name='categoryId' />
			    <input  type='hidden' id='siteId' name='siteId' />
			    <input  type='hidden' id='categoryKey' name='categoryKey' />
			    <input  type='hidden' id='urlPart' name='urlPart' />
			    <input  type='text' id='urlSecondPart' name='urlSecondPart' />
			    <input  type='hidden' id='requestKey' name='requestKey' value='<?php echo $formKey;?>' />
			    <div id='buttonsDiv' style='text-align: center'>
				    <input type='button' value='New' name='store_data' class="btn btn-default new store-data" /> 
				    <input type='button' value='Popoular' name='store_data' class="btn btn-default popular store-data" />
				    <input type='button' value='High' name='store_data' class="btn btn-default high store-data"/>
				    <input type='button' value='Low' name='store_data' class="btn btn-default low store-data"/>
				    <input type='button' value='Relevance' name='store_data' class="btn btn-default relevance store-data" />
				    <input type='button' value='Discount' name='store_data' class="btn btn-default discount store-data" />
			    </div>
			 </form>
		</div>
	</div>
	<div id="product_view" class="tab-pane fade">
	
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
    $('.nav-tabs a').on('shown.bs.tab', function(event){
        var x = $(event.target).text();         // active tab
        var y = $(event.relatedTarget).text();  // previous tab
        $(".act span").text(x);
        $(".prev span").text(y);
    });
});
 function getCategory(pid,siteId,obj){
	 $.ajax({
			url: "controller/CreateUIController.php",
			type: "POST",
			data:{type:"get_category",pId:pid,siteId:siteId},
			success: function(data) {
				data=JSON.parse(data);
				console.log(JSON.stringify(data));
				if(data.length!==0){
					var str="";
					if(typeof(obj)!="string" && obj.closest("div").next("div").length > 0){
						    str+="<label for=\"category\">Category:</label>";
                        	str+="<select class='category form-control'>";
    						for(var i=0;i<data.length;i++){
    							str+="<option value='"+data[i].categoryId+"' alt='"+data[i].affiliateCategoryKey+"' >"+data[i].affiliateCategoryName+"</option>";
    						}
    						str+="</select>";
    						obj.closest("div").next("div").html(str);
    						getCategory(data[0].categoryId,siteId,obj.closest("div").next("div").find("select"));
					}else{
						str+="<div class=\"form-group\"><label for=\"category\">Category:</label>";
						str+="<select class='category form-control'>";
						for(var i=0;i<data.length;i++){
							str+="<option value='"+data[i].categoryId+"' alt='"+data[i].affiliateCategoryKey+"' >"+data[i].affiliateCategoryName+"</option>";
						}
						str+="</select></div>";
						$("#config").append(str);
						getCategory(data[0].categoryId,siteId,"");
					}			
					$("#urlSecondPart").val(data[0].urlPart);	
				}else if(typeof(obj)!="string" && obj.closest("div").next("div").length > 0){
					obj.closest("div").next("div").remove();
				}
			},
			error:function(data,status,er) {
				
			}
			
		});
 }
 function getLinks(siteId){
	 $.ajax({
			url: "controller/CreateUIController.php",
			type: "POST",
			data:{type:"get_link",siteId:siteId},
			success: function(data) {
				data=JSON.parse(data);
				for(var i=0;i<data.length;i++){
					$(".new").attr("id",data[i].latest);
					$(".popular").attr("id",data[i].popular);
					$(".high").attr("id",data[i].high);
					$(".low").attr("id",data[i].low);
					$(".relevant").attr("id",data[i].relevant);
					$(".discount").attr("id",data[i].discount);
					$("#baseUrl").val(data[i].baseUrl);
					$("#productBaseUrl").val(data[i].productBaseUrl);
				}
				//console.log("gffggfg"+data);
			},
			error:function(data,status,er) {
			}
		});
 }
 function getSiteList(){
	 $.ajax({
			url: "controller/CreateUIController.php",
			type: "POST",
			data:{type:"get_site_list"},
			success: function(data) {
				data=JSON.parse(data);
				for(var i=0;i<data.length;i++){
					$(".siteId").append("<option value='"+data[i].id+"'>"+data[i].affiliateSiteName+"</option>");
				}
				getLinks(1);
				getCategory(0,1,"");
			},
			error:function(data,status,er) {
			}
		});
 }
 $(document).ready(function(){
	 getSiteList();
	 $(document.body).on("change",".category",function(){
		var pid=$(this).val();
		var siteId=$(".siteId").val();
		getCategory(pid,siteId,$(this));
	 });
	 $(document.body).on("change",".siteId",function(){
			var siteId=$(".siteId").val();
			getCategory(0,siteId,$(this));
			getLinks(siteId);
	 });
	 $(".store-data").click(function(){
		$("#siteId").val($(".siteId").val());
		$("#categoryId").val($(".category:last").val());
		$("#categoryKey").val($(".category:last").find("option:selected").attr("alt"));
		$("#urlPart").val("&"+$(this).attr("id"));
		$("#saveProducts").submit();
	 });
 });

</script>
</body>
</html>