/**
 * 
 */

var xEditor={
	version:"v1.0",
	xEditorType:"standard-coding",
	xEditorClass:"xeditor",
	xEditorControllerClass:"xeditor-controllers-wrapper",
	xEditorControlGroupClass:"xeditor-control-group",
	xEditorheight:"200px",
	init:function(obj){
		var el=document.getElementsByClassName(this.xEditorClass);
		for(var i=0;i<el.length;i++){
			el[i].contentEditable=true;
			el[i].style.minHeight=this.xEditorheight;
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
		var el = _el("#editorBox");
		savedSel = saveSelection();
		_el("#table_modal").modal("show");
        break;
	case 'link':
		savedSel = saveSelection();
		_el("#link_modal").modal("show");
        break;
	case 'code':
		savedSel = saveSelection();
		_el("#code_modal").modal("show");
        break;
	case 'image':
		savedSel = saveSelection();
		_el("#img_modal").modal("show");
    default:
        document.execCommand($(this).data('role'), false, null);
        _el(this).toggleClass("button_on");
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
			controlButtons(ecgcEl[i],"bulletedlist");
			controlButtons(ecgcEl[i],"numberedlist");
			controlButtons(ecgcEl[i],"h1");
			controlButtons(ecgcEl[i],"h2");
			controlButtons(ecgcEl[i],"p");
			controlButtons(ecgcEl[i],"subscript");
			controlButtons(ecgcEl[i],"superscript");
			controlButtons(ecgcEl[i],"table");
			controlButtons(ecgcEl[i],"image");
			controlButtons(ecgcEl[i],"link");
			
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
xEditor.init();
