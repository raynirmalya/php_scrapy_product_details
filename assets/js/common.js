$(document).ready(function(){
	$(".headerMenu li").click(function(){
		$(".subMenuDiv").hide();
		$("div[class$='"+$(this).attr("class")+"']").show();
	})
});
