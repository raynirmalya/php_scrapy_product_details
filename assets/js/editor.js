/**
 * 
 */
var editorBoxCaretPos="";
var savedSel="";
var defaultImageWidth="513";
var defaultImageHeight="320";
var bannerWidth="100";
var bannerHeight="250";
var languageObj={"markup":"html","c":"c","csharp":"c#",
		"java":"java","aspnet":"asp.net",
		"javascript":"javascript","css":"css","php":"php",
		"python":"python","sql":"sql","ruby":"ruby",
		"perl":"perl","cpp":"c++"};

//coding-languages
function populateLanguagesInSelectBoxes(){
	var langArr= Object.keys(languageObj);
	for(i=0;i<langArr.length;i++){
		$("#add-code-language,#coding-languages,#coding-lang,#create-coding-lang").append("<option value='"+langArr[i]+"'>"+languageObj[langArr[i]]+"</option>");
	}
}
populateLanguagesInSelectBoxes();
var xEditor={
	version:"v1.0",
	xEditorType:"standard-coding",
	xEditorClass:"xeditor",
	xEditorControllerClass:"xeditor-controllers-wrapper",
	xEditorControlGroupClass:"xeditor-control-group",
	xEditorheight:"200px",
	init:function(obj){
		if(typeof(obj.min_height)!='undefined'){
			this.xEditorheight=obj.min_height;
		}
		var el=document.getElementsByClassName(this.xEditorClass);
		for(var i=0;i<el.length;i++){
			el[i].contentEditable=true;
			el[i].style.height=this.xEditorheight;
            divWrapper(el[i], this.xEditorControllerClass);
		}
		createControllerPanel(this.xEditorType);
	}
};
function toggleClass(element, className){
    if (!element || !className){
        return;
    }
    
    var classString = element.className, nameIndex = classString.indexOf(className);
    if (nameIndex == -1) {
        classString += ' ' + className;
    }
    else {
        classString = classString.substr(0, nameIndex) + classString.substr(nameIndex+className.length);
    }
    element.className = classString;
}
function _el(elem){
	elem=$.trim(elem);
	elemType=elem.substring(0,1);
	if(elemType=="#"){
		return document.getElementById(elem);
	}else{
		return document.getElementsByClassName(elem);
	}
	
}
function taskMaker(){
    switch (this.dataset.role) {
    case 'h1':
    case 'h2':
    case 'p':
        document.execCommand('formatBlock', false, this.dataset.role);
        toggleClass(this,"button_on");
        break;
    case 'table':
		savedSel = saveSelection();
		$("#table_modal").modal("show");
        break;
	case 'link':
		savedSel = saveSelection();
		$("#link_modal").modal("show");
        break;
	case 'multi_line_code':
		savedSel = saveSelection();
		$("#code_modal").modal("show");
        break;
	case 'single_line_code':
		savedSel = saveSelection();
		changeSingleLineCode();
        break;
	case 'image':
		savedSel = saveSelection();
		$("#img_modal").modal("show");
		break;
	case 'format_text':
		savedSel = saveSelection();
		$("#format_text_modal").modal('show'); 
		break;
    default:
        document.execCommand($(this).data('role'), false, null);
    	toggleClass(this,"button_on");
        break;
    }
   
}
function createHtmlElement(obj){
	var el=document.createElement(obj.tagName);
	el.id=obj.id;
	el.title=obj.title;
	el.className=obj.className;
	return el;
}
function createTableModal(){
	
}
function createControllerPanel(xEditorType){
	var eccEl,ecgcEl,i,editortTypeArr;
	editortTypeArr=xEditorType.split("-");
	if(editortTypeArr[0]=="standard"){
		eccEl=document.getElementsByClassName(xEditor.xEditorControllerClass);
		for(i=0;i<eccEl.length;i++){
			eccEl[i].insertBefore(createControlGroup(), eccEl[i].firstChild);
		}
		ecgcEl=document.getElementsByClassName(xEditor.xEditorControlGroupClass);
		for(i=0;i<ecgcEl.length;i++){
			controlButtons(ecgcEl[i],"undo");
			controlButtons(ecgcEl[i],"redo");
			controlButtons(ecgcEl[i],"bold");
			controlButtons(ecgcEl[i],"italic");
			controlButtons(ecgcEl[i],"underline");
			controlButtons(ecgcEl[i],"strike");
			controlButtons(ecgcEl[i],"justifyleft");
			controlButtons(ecgcEl[i],"justifycenter");
			controlButtons(ecgcEl[i],"justifyright");
			controlButtons(ecgcEl[i],"justifyblock");
			controlButtons(ecgcEl[i],"indent");
			controlButtons(ecgcEl[i],"outdent");
			controlButtons(ecgcEl[i],"insertUnorderedList");
			controlButtons(ecgcEl[i],"insertOrderedList");
			controlButtons(ecgcEl[i],"h1");
			controlButtons(ecgcEl[i],"h2");
			controlButtons(ecgcEl[i],"p");
			controlButtons(ecgcEl[i],"subscript");
			controlButtons(ecgcEl[i],"superscript");
			controlButtons(ecgcEl[i],"table");
			controlButtons(ecgcEl[i],"image");
			controlButtons(ecgcEl[i],"link");
			controlButtons(ecgcEl[i],"single_line_code");
			controlButtons(ecgcEl[i],"multi_line_code");
			controlButtons(ecgcEl[i],"format_text");
			
		}
	}
}
function addListener(element, eventName, handler) {
	  if (element.addEventListener) {
	    element.addEventListener(eventName, handler, false);
	  }
	  else if (element.attachEvent) {
	    element.attachEvent('on' + eventName, handler);
	  }
	  else {
	    element['on' + eventName] = handler;
	  }
}

function removeListener(element, eventName, handler) {
	  if (element.addEventListener) {
	    element.removeEventListener(eventName, handler, false);
	  }
	  else if (element.detachEvent) {
	    element.detachEvent('on' + eventName, handler);
	  }
	  else {
	    element['on' + eventName] = null;
	  }
}
function controlButtons(el,btnName){
	var anchor = document.createElement('a');
	anchor.dataset.role=btnName;
	anchor.href="javascript:void(0)";
	anchor.className="editor_button";
	var spn= document.createElement('span');
	spn.title=btnName;
	if(btnName!="h1" && btnName!="h2" && btnName!="p"){
		spn.className="editor_botton_icon editor_"+btnName+"_icon";
	}else{
		spn.className="editor_botton_icon html_tag_icon";
		spn.innerHTML=btnName;
	}
	el.appendChild(anchor);
	anchor.appendChild(spn);
	addListener(anchor,'click',taskMaker);
}
function createControlGroup(){
	var div = document.createElement('div');
	div.className=xEditor.xEditorControlGroupClass;
	return div;
}
 function divWrapper(el, className) {
 	var div = document.createElement('div');
    var oldDiv = div.cloneNode(false);
    oldDiv.className = className;
    el.parentNode.insertBefore(oldDiv, el);
    oldDiv.appendChild(el);
  }
 
 function saveSelection() {
	    if (window.getSelection) {
	        sel = window.getSelection();
	        if (sel.getRangeAt && sel.rangeCount) {
	            var ranges = [];
	            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
	                ranges.push(sel.getRangeAt(i));
	            }
	            return ranges;
	        }
	    } else if (document.selection && document.selection.createRange) {
	        return document.selection.createRange();
	    }
	    return null;
	}

	function restoreSelection(savedSel) {
	    if (savedSel) {
	        if (window.getSelection) {
	            sel = window.getSelection();
	            sel.removeAllRanges();
	            for (var i = 0, len = savedSel.length; i < len; ++i) {
	                sel.addRange(savedSel[i]);
	            }
	        } else if (document.selection && savedSel.select) {
	            savedSel.select();
	        }
	    }
	}
	function createTable(properties){
		var rows=properties.rows;
		var cols=properties.cols;
		var header=properties.header;
		var cellspacing=properties.cellspacing;
		var cellpadding=properties.cellpadding;
		var caption=properties.caption;
		var summary=properties.summary;
		var alignment=properties.alignment;
		var width=properties.width;
		var height=properties.height;
		var borderSize=properties.borderSize;
		var tableStr="";
		tableStr+="<table summary='"+summary+"' class='editorTbl table table-bordered table-hover table-condensed'" +
				" cellspacing='"+cellspacing+"' cellpadding='"+cellpadding+"' border='"+borderSize+"' " +
				" style='width:100%;' align='"+alignment+"' >";
		if($.trim(caption)!="")
			tableStr+="<caption>"+caption+"</caption>"
		for(rowCounter=0;rowCounter<rows;rowCounter++){
			if(rowCounter==0){
				tableStr+="<thead>";
			}else if(rowCounter==1){
				tableStr+="</thead><tbody>";
			}
			tableStr+="<tr>";
			for(colCounter=0;colCounter<cols;colCounter++){				
				if(header=='row' && rowCounter==0)
					tableStr+="<th colspan='1' id='col"+colCounter+"'></th>";
				else if(header=='col' && colCounter==0)
					tableStr+="<th colspan='1' id='col"+colCounter+"'></th>";
				else if(header=='both' && (colCounter==0 || rowCounter==0))
					tableStr+="<th colspan='1' rowspan='1' id='col"+colCounter+"'></th>";
				else
					tableStr+="<td colspan='1' rowspan='1' id='col"+colCounter+"'></td>";
			}
			tableStr+="</tr>";
		}
		tableStr+="</tbody></table>";
		var caretPos=editorBoxCaretPos+tableStr.length;
		restoreSelection(savedSel);
		var el = $("."+xEditor.xEditorClass);
	    pasteHtmlAtCaret(tableStr);
	}
	$(document).ready(function(){
		$("#image-tabs").tabs();
		$("#code-preview-tabs").tabs();
		$("."+xEditor.xEditorClass).focus();
		$(".putTableInEditBox").click(function(){
			var properties={};
			properties.rows=$(".editorTblRows").val();
			properties.cols=$(".editorTblCols").val();
			properties.header=$(".editorHeaders").val();
			properties.borderSize=$(".editorTblBSize").val();
			properties.alignment=$(".editorAlignment").val();
			properties.caption=$(".editorTblCptn").val();
			properties.summary=$(".editorTblSmry").val();
			properties.width=$(".editorTblWidth").val();
			properties.height=$(".editorTblHeight").val();
			properties.cellspacing=$(".editorTblCspce").val();
			properties.cellpadding=$(".editorTblCpad").val();
			$("#table_modal").modal("hide");
			$("."+xEditor.xEditorClass).focus();
			setCaretPosition($("."+xEditor.xEditorClass),editorBoxCaretPos);
		    createTable(properties);
			
		});
	});
	
	function setCaretPosition(ctrl, pos){
		if(ctrl.setSelectionRange)
		{
			ctrl.focus();
			ctrl.setSelectionRange(pos,pos);
		}
		else if (ctrl.createTextRange) {
			var range = ctrl.createTextRange();
			range.collapse(true);
			range.moveEnd('character', pos);
			range.moveStart('character', pos);
			range.select();
		}
	}

	function getCaretPosition(editableDiv) {
	  var caretPos = 0,
	    sel, range;
	  if (window.getSelection) {
	    sel = window.getSelection();
	    if (sel.rangeCount) {
	      range = sel.getRangeAt(0);
	      if (range.commonAncestorContainer.parentNode == editableDiv) {
	        caretPos = range.endOffset;
	      }
	    }
	  } else if (document.selection && document.selection.createRange) {
	    range = document.selection.createRange();
	    if (range.parentElement() == editableDiv) {
	      var tempEl = document.createElement("span");
	      editableDiv.insertBefore(tempEl, editableDiv.firstChild);
	      var tempRange = range.duplicate();
	      tempRange.moveToElementText(tempEl);
	      tempRange.setEndPoint("EndToEnd", range);
	      caretPos = tempRange.text.length;
	    }
	  }
	  return caretPos;
	}
	function pasteHtmlAtCaret(html) {
	    var sel, range;
	    if (window.getSelection) {
	        sel = window.getSelection();
	        if (sel.getRangeAt && sel.rangeCount) {
	            range = sel.getRangeAt(0);
	            range.deleteContents();
	            var el = document.createElement("div");
	            el.innerHTML = html;
	            var frag = document.createDocumentFragment(), node, lastNode;
	            while ( (node = el.firstChild) ) {
	                lastNode = frag.appendChild(node);
	            }
	            range.insertNode(frag);
	            if (lastNode) {
	                range = range.cloneRange();
	                range.setStartAfter(lastNode);
	                range.collapse(true);
	                sel.removeAllRanges();
	                sel.addRange(range);
	            }
	        }
	    } else if (document.selection && document.selection.type != "Control") {
	        document.selection.createRange().pasteHTML(html);
	    }
	}
	$(document).on('change', '.btn-file :file', function () {
	    var input = $(this), numFiles = input.get(0).files ? input.get(0).files.length : 1, label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
	    input.trigger('fileselect', [
	        numFiles,
	        label
	    ]);
	});
	$(document).ready(function () {
	    $('.btn-file :file').on('fileselect', function (event, numFiles, label) {
	        var input = $(this).parents('.input-group').find(':text'), log = numFiles > 1 ? numFiles + ' files selected' : label;
	        if (input.length) {
	            input.val(log);
	        } else {
	            if (log)
	                alert(log);
	        }
	    });
	});
	$(document).ready(function(){
		
		$(".search-code").click(function(){
		var code_title="",code_language="";
		code_title=$(".code-title").val();
		code_language=$("#coding-languages").val();
		 $.ajax({
				url: "controller/CodeExampleController.php",
				type: "POST",
				data:{type:"search_code_example","code_title":code_title,"code_language":code_language},
				success: function(data) {
					var json=JSON.parse(data);
					//console.log(JSON.parse(data));
					var str="";
					str+="<table class='table table-bordered table-hover table-condensed margin-top-sm'>";
					str+="<thead>";
					str+="<tr><td>Title</td><td>Language</td></tr>"
					str+="</thead>";
					str+="<tbody>";
					for(i=0;i<json.length;i++){
						str+="<tr class='show-codes pointer'>";
						str+="<td title='"+json[i].title+"' class='width-md'>"+json[i].title+"</td>";
						str+="<td>"+json[i].language+"</td>";
						str+="<td class='hidden code_link'>"+json[i].link+"</td>";
						str+="<td class='code_example_id hidden'>"+json[i].id+"</td>";
						str+="</tr>";
					}
					str+="<tbody>";
					str+="</table>";
					$(".code-example-result").html(str);
				},
				error:function(data,status,er) {
				}
			});
	});
		$(document.body).on('click', '.close-codebox' ,function(){	
			$(this).parent("div").remove();
		});
		$(document.body).on('click', '.show-codes' ,function(){	
			var code_id=$(this).find(".code_example_id").html();
			$("#code_preview").addClass("hidden");
			$.ajax({
				url: "controller/CodeExampleController.php",
				type: "POST",
				data:{type:"code_example_by_id","code_id":code_id},
				success: function(data) {
					var json=JSON.parse(data);
				//	console.log(JSON.stringify(json));
					$("#code-preview-tab").html(json[0].code);
					$("#code-output-tab").html(json[0].output);
					$("#put-code-output").val(json[0].output);
					$("#code-link").val(json[0].link);
					$("#code_preview").removeClass("hidden");
				},
				error:function(data,status,er) {
				}
			});
			
			//code=JSON.parse(code);
			
		});
		function getHTMLOfSelection () {
			  var range;
			  if (document.selection && document.selection.createRange) {
			    range = document.selection.createRange();
			    return range.htmlText;
			  }
			  else if (window.getSelection) {
			    var selection = window.getSelection();
			    if (selection.rangeCount > 0) {
			      range = selection.getRangeAt(0);
			      var clonedSelection = range.cloneContents();
			      var div = document.createElement('div');
			      div.appendChild(clonedSelection);
			      return div.innerHTML;
			    }
			    else {
			      return '';
			    }
			  }
			  else {
			    return '';
			  }
			}
		function surroundSelection(textBefore, textAfter) {
		    if (window.getSelection) {
		        var sel = window.getSelection();
		        if (sel.rangeCount > 0) {
		            var range = sel.getRangeAt(0);
		            var startNode = range.startContainer, startOffset = range.startOffset;
		            var boundaryRange = range.cloneRange();
		            var startTextNode = document.createTextNode(textBefore);
		            var endTextNode = document.createTextNode(textAfter);
		            boundaryRange.collapse(false);
		            boundaryRange.insertNode(endTextNode);
		            boundaryRange.setStart(startNode, startOffset);
		            boundaryRange.collapse(true);
		            boundaryRange.insertNode(startTextNode);
		            
		            // Reselect the original text
		            range.setStartAfter(startTextNode);
		            range.setEndBefore(endTextNode);
		            sel.removeAllRanges();
		            sel.addRange(range);
		        }
		    }
		}
		function CopyText(el){
		    var selectedText = "";
		    if(window.getSelection){
		        selectedText = window.getSelection();
		    }else if (document.getSelection){
		        selectedText = document.getSelection();
		    }else if (document.selection){
		        selectedText = document.selection.createRange().text;
		    } 
		    if(selectedText != ""){
		        selectedText = selectedText.toString();
		        el.focus();
		        el.value = selectedText;
		        }else {
		        alert("Select a text in the page and then press this button!");
		    }
		}
		function ClipBoard() 
		{
		holdtext.innerText = copytext.innerText;
		Copied = holdtext.createTextRange();
		Copied.execCommand("Copy");
		}

	  $("#add_code").click(function(){
		 surroundSelection("[pre class=\"line-numbers\"][code class=\"language-"+$(".code-selector").val()+"\" theme=\"coy\"]","[/code][/pre]") 
	  });
	  $(".format-text").click(function(){
		  $("#format_text_modal").modal('show'); 
	  });
	  $("#save-tutorial").click(function(){
			var code_title="",code_language="",code_content="",link="",tags="",status="",post_type="",jsonObj="";
			code_title=$("#post-title").val();
			code_language=$("#coding-lang").val();
			code_content=$("."+xEditor.xEditorClass).html();
			post_type=$("#post-type").val();
		    $.ajax({
				url: "controller/TutorialsController.php",
				type: "POST",
				data:{type:"save_tutorial","tutorial_title":code_title,"tutorial_language":code_language,"tutorial_post":code_content,"link":"",tags:"",status:"","post_type":post_type},
				success: function(data) {
					console.log(data);
				},
				error:function(data,status,er) {
				}
			});
		});
     $("#img_upload").click(function(){
    	 uploadFile();
     });
	  $(".put-codes").click(function(){
		  var html="",prehtml="",posthtml="",el,code_tryout="",code_link="",code_used="",final_html="";
		  html=getSelectionHtml();
		  code_used=$(".code-used").val();
		  code_link=$("#code-link").val();
		  code_tryout=$(".code-tryout").val();
		  console.log(code_used);
		  if(code_used=="code"){
			  if($.trim(html)==""){
				 html=$("#code-preview-tab").text(); 
			  }
			  var len=$(".code-container").length;
			  prehtml="<div class='code-container almost-full-width float-left padding-lg margin-left-sm' id='code-container"+(len+1)+"' contenteditable=\"false\">" +
			  		"<span class=\"close-codebox\"></span>" +
			  		"[pre class=\"line-numbers\"][code class=\"language-"+$("#add-code-language").val()+"\" theme=\"coy\"]" +
			  		"<br/>";
			  if(code_tryout=="yes"){
				  posthtml="[/code][/pre]" +
				  		"<div class=\"almost-full-width padding-lg\">" +
				  		"<div class=\"float-right\">" +
				  		"<a href=\""+code_link+"\" target=\"_blank\"class=\"btn btn-primary\">Try it</a>" +
				  		"</div>" +
				  		"</div>" +
				  		"</div>"; 
			  }else{
				  posthtml="[/code][/pre]</div>";
			  }
			  final_html=prehtml+html+posthtml;
		  }else if(code_used=="output"){
			  final_html="<div style='border:1px solid #f0f0f0;'><pre>"+$("#put-code-output").val()+"</pre></div>"; 
		  }
		  final_html+="<div style='float:left;width:100%;min-height:15px;'></div>";
		  $("#code_modal").modal("hide");
		  restoreSelection(savedSel);
		  $("."+xEditor.xEditorClass).focus();
		  el = $("."+xEditor.xEditorClass);
		  pasteHtmlAtCaret(final_html);
		  placeCaretAtEnd(el);
		  //$("#code-container"+(len+1)).parent().css({"float":"left","width":"100%"});
		  //placeCaretAfterNode(document.getElementById("code-container"+(len+1)));
		  
	  });
	  
	  function placeCaretAtEnd(el) {
		    el.focus();
		    if (typeof window.getSelection != "undefined"
		            && typeof document.createRange != "undefined") {
		        var range = document.createRange();
		        range.selectNodeContents(el);
		        range.collapse(false);
		        var sel = window.getSelection();
		        sel.removeAllRanges();
		        sel.addRange(range);
		    } else if (typeof document.body.createTextRange != "undefined") {
		        var textRange = document.body.createTextRange();
		        textRange.moveToElementText(el);
		        textRange.collapse(false);
		        textRange.select();
		    }
		}
	  function placeCaretAfterNode(node) {
		    if (typeof window.getSelection != "undefined") {
		        var range = document.createRange();
		        range.setStartAfter(node);
		        range.collapse(true);
		        var selection = window.getSelection();
		        selection.removeAllRanges();
		        selection.addRange(range);
		    }
		}

		function moveCaret() {
		    document.getElementById("editor").focus();
		    placeCaretAfterNode( document.getElementById("theSpan") );
		}
	  $(document.body).on('click', '.add-image' ,function(){	
		  var image="";
		  $("#img_modal").modal("hide");
		  image=$("#preview-add-image").html();
		  restoreSelection(savedSel);
		  el = $("."+xEditor.xEditorClass);
		  pasteHtmlAtCaret(image);
		  $("."+xEditor.xEditorClass).focus();
	  });
	  $(document.body).on('click', '.select-searched-image' ,function(){
		  	$(".select-searched-image").prop("checked",false);
	  		$(this).prop("checked",true);
	  });
	  $(document.body).on('click', '.add-search-image' ,function(){
		  	 var src="",image="",width="",height="",padding="",alignment="",left="",widthWithoutPx="";
		  	 width=$("#img-width").val();
		  	 height=$("#img-height").val();
		  	 padding=$("#img-padding").val();
		  	 alignment=$("#img-alignment").val();
			 if($.trim(width)==""){
			  	width=defaultImageWidth;
			 }
		  	 if($.trim(height)==""){
		  		height=defaultImageHeight;
		  	 }
		  	 
		  	 widthWithoutPx=parseInt(width,10);//.substring(0, (width.length)-2)
		  	 left=widthWithoutPx-20;
		  	 rightSideWidth=parseInt($("."+xEditor.xEditorClass).width(),10)-widthWithoutPx-(padding*2);
		  	 src=$(".select-searched-image:checked").closest("tr").find(".img_responsive").attr("src");
		  	 if(alignment=="left"){
			  	 image+="<div class='img-wrap'  style='padding:"+padding+"px'><!--<span contenteditable='false' class='close-image' style='left:"+left+"px;top:5px;'></span>-->";
			  	 image+="<img src='"+src+"' class='img-responsive' style='float:left;max-width:"+width+"px;max-height:"+height+"px' />";
			  	 image+="</div>";
		  	 }else if(alignment=="right"){
		  		 image+="<div class='img-wrap' style='padding:"+padding+"px;'><!--<span contenteditable='false' class='close-image' style='margin-left:"+rightSideWidth+"px;right:5px;top:5px;'></span>-->";
			  	 image+="<img src='"+src+"' class='img-responsive' style='float:left;margin-left:"+rightSideWidth+"px;max-width:"+width+"px;max-height:"+height+"px' />";
			  	 image+="</div>";
		  	 }else if(alignment=="banner"){
		  		 image+="<div class='img-wrap' style='padding:"+padding+"px;'><!--<span contenteditable='false' class='close-image' style='right:5px;top:5px;'></span>-->";
			  	 image+="<img src='"+src+"' class='img-responsive' style='float:left;max-width:"+bannerWidth+"%;max-height:"+bannerHeight+"px' />";
			  	 image+="</div>";
		  	 }else{
		  		 image+="<div class='img-wrap'  style='padding:"+padding+"px;text-align:center;'><!--<span contenteditable='false' class='close-image' style='vertical-align:middle;display:inline-block;top:5px;'></span>-->";
			  	 image+="<img src='"+src+"' class='img-responsive' style='vertical-align:middle;display:inline-block;max-width:"+width+"px;max-height:"+height+"px' />";
			  	 image+="</div>";
		  	 }
		  	 
		  	  
		  	  restoreSelection(savedSel);
			  el = $("."+xEditor.xEditorClass);
			  $("#img_modal").modal("hide");
			  pasteHtmlAtCaret(image);
			  $("."+xEditor.xEditorClass).focus();
	  });
	  $("#add_note").click(function(){
		  var html=getSelectionHtml();
		  savedSel = saveSelection();
		  pasteHtmlAtCaret("<div class='info-box' contenteditable=\"false\"><img src=\"images/info_blue.png\" width=\"20\"/><span contenteditable=\"false\">"+html+"</span></div>");
		  restoreSelection(savedSel);
		  
	  });
	  $("#add_line").click(function(){
		  var html=getSelectionHtml();
		  savedSel = saveSelection();
		  pasteHtmlAtCaret("<hr/>");
		  restoreSelection(savedSel);
		  
	  });
	  $(".search-image").click(function(){	
		  var search_term="",str="";
		  var search_term=$("#srch-term").val();
		  $.ajax({
				url: "controller/TutorialsController.php",
				type: "POST",
				data:{type:"search_image","searchTerm":search_term},
				success: function(jsonObj) {
					var data=JSON.parse(jsonObj);
					str+="<table class='table table-bordered table-hover table-condensed margin-top-lg'>";
					str+="<thead><tr>";
					str+="<th>#</th><th>Image title</th><th>Image</th>";
					str+="</tr></thead>";
					str+="<tbody>";
					for(var i in data){
						if(typeof(data[i].id)!='undefined'){
							str+="<tr>";
							str+="<td><input type='checkbox' class='select-searched-image' value='"+data[i].id+"' /></td>";
							str+="<td>"+data[i].image_title+"</td>";
							str+="<td><img src='"+data[i].image_src+"' class='img_responsive' width='20' /></td>";
							str+="</tr>";
						}
					}
					str+="</tbody>";
					str+="</table>";
					//console.log(str);
					$(".image-search-result").html(str);
				},
				error:function(data,status,er) {
				}
			});
	  });
		  $("a[href='#search-image-tab']").click(function(){
			  $(".add-image").addClass("add-search-image");
			  $(".add-image").removeClass("add-image");
		  });
		  $("a[href='#add-image-tab']").click(function(){
			  $(".add-search-image").addClass("add-image");
			  $(".add-search-image").removeClass("add-search-image");
		  });
		  $(".create-code").click(function(){
			  $("#create_code_modal").modal("show");
		  });
		  $("#add_note").click(function(){
			  var html=getSelectionHtml();
			  savedSel = saveSelection();
			  pasteHtmlAtCaret("<div class='info-box' contenteditable=\"false\"><img src=\"images/info_blue.png\" width=\"20\"/><span contenteditable=\"false\">"+html+"</span></div>");
			  restoreSelection(savedSel);
			  
		  });
		  $("#add_line").click(function(){
			  var html=getSelectionHtml();
			  savedSel = saveSelection();
			  pasteHtmlAtCaret("<hr/>");
			  restoreSelection(savedSel);
			  
		  });
	});
	function changeSingleLineCode(){
		  var html="",prehtml="",posthtml="",el,code_tryout="",code_link="";
		  html=getSelectionHtml();
		  code_link=$("#code-link").val();
		  code_tryout=$(".code-tryout").val();
		  prehtml="<span contenteditable='false' class='single_line_code'>";
		  posthtml="</span>";
		  restoreSelection(savedSel);
		  pasteHtmlAtCaret(prehtml+html+posthtml);
		  $("."+xEditor.xEditorClass).focus();
	} 
	function getSelectionHtml() {
	    var html = "";
	    if (typeof window.getSelection != "undefined") {
	        var sel = window.getSelection();
	        if (sel.rangeCount) {
	            var container = document.createElement("div");
	            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
	                container.appendChild(sel.getRangeAt(i).cloneContents());
	            }
	            html = container.innerHTML;
	        }
	    } else if (typeof document.selection != "undefined") {
	        if (document.selection.type == "Text") {
	            html = document.selection.createRange().htmlText;
	        }
	    }
	    return html;
	}
	  function _(el){
	 	 return document.getElementById(el);
		  } 
		  function ajaxFile(file,i){ 

			  //alert(file.name+" | "+file.size+" | "+file.type);
			 var formdata = new FormData();
			 formdata.append("image_file", file);
			 formdata.append("type", "image_upload");
			 formdata.append("image_title", $("#image_title").val());
			 var ajax = new XMLHttpRequest(); 
			 var preview=document.getElementById('preview-add-image')
			 var divs=document.createElement('div')
			 divs.setAttribute('class','page')
			 var progressbr=document.createElement('progress')
			 progressbr.setAttribute('id','progressBar'+i)
			 progressbr.setAttribute('value','0')
			 progressbr.setAttribute('max','100')
			 progressbr.style.width="220px"
			 var imgdv=document.createElement('div')
			 imgdv.setAttribute('id','imgdv'+i)
			 imgdv.style.width='180px';
			 imgdv.style.height='180px';
			 var spn=document.createElement('span')
			 spn.setAttribute('id','status'+i)
			 divs.appendChild(imgdv)
			 divs.appendChild(progressbr)
			 divs.appendChild(spn)
			 preview.appendChild(divs)
			 ajax.upload.addEventListener("progress", function(e) {progressHandler(e,i);}, false);
			 ajax.addEventListener("load", function(e) {completeHandler(e,i);}, false); 
			 ajax.addEventListener("error", function(e) {errorHandler(e,i);}, false); 
			 ajax.addEventListener("abort", function(e) {abortHandler(e,i);}, false); 
			 ajax.open("POST", "controller/TutorialsController.php"); 
			 ajax.send(formdata); 
		 } 
		 function uploadFile(){
		   for(var i=0;i<_("image_file").files.length;i++){
				  var file = _("image_file").files[i];
				  ajaxFile(file,i);
			  }
		 }
		 function progressHandler(event,i){
			 var percent = (event.loaded / event.total) * 100;
			 _("progressBar"+i).value = Math.round(percent); 
			 _("status"+i).innerHTML = Math.round(percent)+"% uploaded... please wait";
		 }
		 function completeHandler(event,i){
			 _("status"+i).style.display = 'none'; 
			 _("progressBar"+i).style.display = 'none'; 
			 var imgdv=document.getElementById('imgdv'+i)
			 var imgg=document.createElement('img')
			 var respnse=event.target.responseText
			 $("#preview-add-image").html(respnse);
		 }
		 function errorHandler(event,i){ 
			 _("status"+i).innerHTML = "Upload Failed";
		 } 
		 function abortHandler(event,i){
			 _("status"+i).innerHTML = "Upload Aborted";
		 }
		 function htmlEncoding(str){
			    var encodedStr = str.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
			       return '&#'+i.charCodeAt(0)+';';
			    });
			    return encodedStr.replace(/&/gim, '&amp;');
			}
		$(document).ready(function(){
			$("#coding-languages").change(function(){
				var language=$(this).val();
				var langArr=["javascript","css","markup"];
				if(jQuery.inArray(language,langArr)==-1){
					$("#see-result").removeClass("btn-primary");
					$("#see-result").addClass("btn-disabled");
				}else{
					$("#see-result").removeClass("btn-disabled");
					$("#see-result").addClass("btn-primary");
				}
			});
			$("#see-result").click(function(){
				if(!$(this).hasClass("btn-disabled")){
					var encodedHtml=$("#try-it-code-hidden").text();
					$("#code-content").val(encodedHtml);
					var reqKey=Math.random()
					document.getElementById("code-from").action="try_it_viewer.php?x="+reqKey;
					document.getElementById("output-iframe").contentWindow.name = "view";
					document.getElementById("code-from").submit();
				}
			});
			$("#save-code-example").click(function(){
				var code_title="",code_language="",output_editable="",code_content="",code_output="",jsonObj="";
				code_title=$("#code-title").val();
				code_language=$("#create-coding-lang").val();
				output_editable=$("input[name='output_editable']:checked").val();
				code_content=$("#try-it-code-hidden").html();
				code_output=$("#try-it-code-hidden").text();//$("#code-output").val();
			    $.ajax({
					url: "controller/CodeExampleController.php",
					type: "POST",
					data:{type:"save_code_example","code_title":code_title,"code_language":code_language,"output_editable":output_editable,"code_content":code_content,"code_output":code_output},
					success: function(data,status,er) {
						//console.log(data+"--"+status+"++"+er);
						alert("code saved sucessfully");
					},
					error:function(data,status,er) {
					}
				});
			});
			$(".cancel-modal").click(function(){
				$(this).closest(".modal").modal("hide");
			});
			
				function insertCellBefore(obj){
					if(obj.prop("tagName")=="TH"){
						obj.before("<th></th>");
					}else if(obj.prop("tagName")=="TD"){
						obj.before("<td></td>");
					}
					obj.focus();
				}
				function insertCellAfter(obj){
					if(obj.prop("tagName")=="TH"){
						obj.after("<th></th>");
					}else if(obj.prop("tagName")=="TD"){
						obj.after("<td></td>");
					}
					obj.focus();
				}
				function deleteCells(obj){
					obj.remove();
				}
				function mergeRight(obj){
				    obj.next().remove();
					var cspan=parseInt(obj.attr('colspan'),10);
					cspan++;
					obj.attr('colspan',cspan);
					obj.focus();
				}
				function mergeDown(obj){
				    var indx=obj.index();
					var rspan=parseInt(obj.attr('rowspan'),10);
					rspan++;
					obj.attr('rowspan',rspan);
				    obj.closest("tr").next("tr").find("td:eq("+indx+")").remove();
					obj.focus();
				}
				function columnCount(obj){
					var colCount = 0;
					obj.find('td').each(function () {
						if($(this).attr('colspan')) {
							colCount+=parseInt($(this).attr('colspan'),10);
						}else{
							colCount++;
						}
					});
					return colCount;
				}
				
				function rowCount(obj){
					var rowCount = 0;
					obj.find('td').each(function () {
						if($(this).attr('rowspan')) {
							rowCount+=parseInt($(this).attr('rowspan'),10);
						}else{
							rowCount++;
						}
					});
					return rowCount;
				}
				function maxRow(obj){
				    maxR=0
			        obj.find("tr").each(function(){
						if(rowCount($(this))>maxR){
							maxR=rowCount($(this));
						}
					});
					return maxR;
				}

				function maxColumn(obj){
				    maxCol=0
			        obj.find("tr").each(function(){
						if(columnCount($(this))>maxCol){
							maxCol=columnCount($(this));
						}
					});
					return maxCol;
				}
				function splitCellH(obj){
				    var indx=obj.index();
					var cspan="";
				    if(obj.prop("tagName")=="TD"){
						obj.before("<td colspan='1' rowspan='"+obj.attr("rowspan")+"' id='col"+(parseInt(indx,10)+1)+"'></td>");
					}else{
						obj.before("<th colspan='1' rowspan='"+obj.attr("rowspan")+"' id='col"+(parseInt(indx,10)+1)+"'></th>");
						/*if(parseInt(obj.attr("colspan"),10)>1){
						    obj.attr("colspan",parseInt(obj.attr("colspan"),10)-1)
						}*/
					}
					if(parseInt(obj.attr("colspan"),10)>1){
						    obj.attr("colspan",parseInt(obj.attr("colspan"),10)-1)
					}
					var maxCol=maxColumn(obj.closest("table"));
					obj.closest("table").find("tr").each(function(){
						if(columnCount($(this))<maxCol){
							if($(this).find("th").length>0){
								cspan=parseInt($(this).find("th:eq("+indx+")").attr("colspan"),10);
								cspan++
								$(this).find("th:eq("+indx+")").attr("colspan",cspan);
							}else{
								cspan=parseInt($(this).find("td:eq("+indx+")").attr("colspan"),10);
								cspan++;
								$(this).find("td:eq("+indx+")").attr("colspan",cspan)
							}
						}
					});
				}
				function splitCellV(obj){
					var indx=obj.index();
					var thisId=obj.attr("id");
					var rspan="";
					if(parseInt(obj.attr("rowspan"),10)>1){
						//obj.attr("rowspan",parseInt(obj.attr("rowspan"),10)-1);
						//alert("1");
						var currRowSpan=parseInt(obj.attr("rowspan"),10);
						var newRowSpan=Math.ceil(parseInt(obj.attr("rowspan"),10)/2);
						var restRowSpan=currRowSpan-newRowSpan;
						var cntr=1;
						obj.closest("tr").nextAll("tr").each(function(){
							if(cntr==newRowSpan){
							   var id=obj.attr("id");
							   id=id.replace("col","");
							   $(this).find("td").each(function(){
									 var thisCol=$(this).attr("id");
							         thisCol=thisCol.replace("col","");
							       //  alert((parseInt(id,10)+1)+"***********"+maxColumn(obj.closest("table")));
							         if((parseInt(id,10)+1)>=maxColumn(obj.closest("table"))){
							        //	 alert("1")
							        	 if(parseInt(thisCol)==(parseInt(id,10)-1)){
											// alert(parseInt(thisCol)+"--"+(parseInt(id,10)-1))
											$(this).after("<td colspan='1' id='col"+id+"' rowspan='"+restRowSpan+"'></td>");
										 }
							         }else{
										 if(parseInt(thisCol)==(parseInt(id,10)+1)){
											// alert(parseInt(thisCol)+"--"+(parseInt(id,10)+1))
											$(this).before("<td colspan='1' id='col"+id+"' rowspan='"+restRowSpan+"'></td>");
										 }
							         }
							   });
							}
							cntr++;
						});
						obj.attr("rowspan",newRowSpan);
					}else{
					    var col=columnCount(obj.closest("tr"));
						var  maxCol=maxColumn(obj.closest("table"));
						var orgRow;
						if(col<maxCol){
						      obj.closest("tr").prev("tr").each(function(){
							      if(columnCount($(this))==maxCol){
				                      orgRow=$(this);
								      return false;
								  }
							  });
							  orgRow.find("td").each(function(){
							        if($(this).attr("id")!=thisId)
										$(this).attr("rowspan",parseInt($(this).attr("rowspan"),10)+1)
							  });
							  if(obj.prop("tagName")=="TD"){
								obj.closest("tr").after("<tr><td colspan='1' rowspan='1' id='col"+indx+"'></td></tr>");
							  }else{
								obj.closest("tr").after("<tr><th colspan='1' rowspan='1' id='col"+indx+"'></th></tr>");
							  }
						}else{
							if(obj.prop("tagName")=="TD"){
								obj.closest("tr").after("<tr><td colspan='1' rowspan='1' id='col"+indx+"'></td></tr>");
							}else{
								obj.closest("tr").after("<tr><th colspan='1' rowspan='1' id='col"+indx+"'></th></tr>");
							}
						}
						obj.closest("tr").find("td").each(function(){
							if($(this).attr("id")!=thisId)
								$(this).attr("rowspan",parseInt($(this).attr("rowspan"),10)+1)
						});
					}
					//obj.index()
					//obj.
					
				    
				}
			$.contextMenu({
				selector: '.editorTbl td,.editorTbl th', 
				 build: function($trigger, e) {
		            return {
						callback: function(key, options) {
							var m = "clicked: " + key;
							switch(key){
								case "insert_cell_before":
									insertCellBefore($(this));
									break;
								case "insert_cell_after":
									insertCellAfter($(this));
									break;
								case "delete_cells":
									deleteCells($(this));
									break;
								case "merge_cells":
									mergeCells($(this));
									break;
								case "merge_right":
									mergeRight($(this));
									break;
								case "merge_down":
									mergeDown($(this));
									break;
								case "split_cell_horizontally":
									splitCellH($(this));
									break;
								case "split_cell_vertically":
									splitCellV($(this));
									break;
								case "cell_properties":
									break;
							}
							//window.console && console.log(m) || alert(m); 
						},
						items: {
							"paste": {name: "Paste", icon: "paste"},
							"sep1": "",
							"fold1": {
									name: "Cell", 
									callback: function() {
										alert("1");
										return false;
									},
									items: {
										"insert_cell_before": {"name": "Insert Cell Before"},
										"insert_cell_after": {"name": "Insert Cell After"},
										"delete_cells": {"name": "Delete Cells"},
										"merge_cells": {"name": "Merge Cells"},
										"merge_right": {"name": "Merge Right"},
										"merge_down": {"name": "Merge Down"},
										"split_cell_horizontally": {"name": "Split Cell Horizontally"},
										"split_cell_vertically": {"name": "Split Cell Vertically"},
										"cell_properties": {"name": "Cell properties"}
									}
										
								},
							"sep2": "",
							 "fold3": {
									"name": "Row", 
									"items": {
										"insert_row_before": {"name": "Insert Row Before"},
										"insert_row_after": {"name": "Insert Row After"},
										"delete_row": {"name": "Delete Row"}
									}
								},
							"sep3": "",
							 "fold5": {
									"name": "Column", 
									"items": {
										"insert_column_before": {"name": "Insert Column Before"},
										"insert_column_after": {"name": "Insert Column After"},
										"delete_column": {"name": "Delete Column"}
									}
								},
							"sep4": "",
							"delete_table": {name: "Delete Table", icon: ""},
							"sep5": "",
							"table_properties": {name: "Table Properties", icon: ""},
							
						}
					}
				}
			});
		});
		
$(document).ready(function(){
	 $("#try-it-code").on('paste', function(e){ 
		 setTimeout(function(){
			 $("#try-it-code").blur();
			 $("#try-it-code-hidden").focus();
			 $("#try-it-code-hidden").html("");
			 document.execCommand('inserttext', false,$("#try-it-code").val());
		 },0);
	 });
});