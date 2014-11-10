var IWIDTH=250  // Tip box width
var x,y,winW,winH  // Current help position and main window size
var idiv=null   // Pointer to infodiv container
var px="px"     // position suffix with "px" in some cases

function hascss(){ return gettip('infodiv')?true:false }

function infoinit(){
 x=0;y=0;winW=800;winH=600;
 idiv=null;
 document.onmousemove = mousemove;
}

function untip(){
 if(idiv)
	idiv.visibility="hidden";
 idiv=null;
}

function gettip(name){return document.getElementById(name).style;}

function inittips(){
 document.write('<DIV ID="infodiv" STYLE="position:absolute; '+
		'visibility:hidden; z-index:20; top:0px; left:0px;"></DIV>');
}

function maketip(name,title,text){
 if(hascss()) document.write('<div id="'+name+'" name="'+name+'" style="position:absolute; visibility:hidden; z-index:20; top:0'+px+'; left:0'+px+';"><table width='+IWIDTH+' border=0 cellpadding=2 cellspacing=0 bgcolor="#333399"><tr><td class="tiptd"><table width="100%" border=0 cellpadding=0 cellspacing=0><tr><th class="ptt"><span class="ptt"><b><font color="#FFFFFF">'+title+'</font></b></span></th></tr></table><table width="100%" border=0 cellpadding=2 cellspacing=0 bgcolor="#CCCCFF"><tr><td class="pst"><span class="pst"><font color="#000000">'+text+'</font></span></td></tr></table></td></tr></table></div>'+"\n");
}

function tip(name){
 if(hascss()){
  if(idiv) untip();
  idiv=gettip(name);
  if(idiv){
   winW=(window.innerWidth)? window.innerWidth+window.pageXOffset-16:document.body.offsetWidth-20;
   winH=(window.innerHeight)?window.innerHeight+window.pageYOffset  :document.body.offsetHeight;
   if(x<=0||y<=0){
    x=(winW-IWIDTH)/2+(window.pageXOffset?window.pageXOffset:0); y=(winH-50)/2+(window.pageYOffset?window.pageYOffset:0); // middle of window
   }
   showtip();
  }
 }
}

function findPosXY(obj)
{
	foundLeft = foundTop = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			foundLeft += obj.offsetLeft;
			foundTop  += obj.offsetTop;
			obj = obj.offsetParent;
		}
	}
	else {
		foundLeft = obj.x;
		foundTop = obj.y;
	}
}

function showtip(){
	tipanchor = document.getElementById('tip-here')
	if (tipanchor) {
		findPosXY(tipanchor)
		idiv.left = foundLeft;
		idiv.top = foundTop;
	}
	else {
		idiv.left = ((x < winW / 2) ? x + 12 : x - 300) + px;
		idiv.top = 20 + px;
	}
	idiv.visibility="visible";
}

function mousemove(e){
 if(e) {x=e.pageX?e.pageX:e.clientX?e.clientX:0; y=e.pageY?e.pageY:e.clientY?e.clientY:0;}
 else if(event) {x=event.clientX; y=event.clientY;}
 else {x=0; y=0;}
 if(idiv) showtip();
}

window.onload=infoinit;

// EOF infobox.js
