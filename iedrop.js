startList = function() {
if (document.all&amp;&amp;document.getElementById) {
navRoot = document.getElementById(&quot;nav&quot;);
for (i=0; i&lt;navRoot.childNodes.length; i++) {
node = navRoot.childNodes[i];
if (node.nodeName==&quot;LI&quot;) {
node.onmouseover=function() {
this.className+=&quot; over&quot;;
  }
  node.onmouseout=function() {
  this.className=this.className.replace(&quot; over&quot;, &quot;&quot;);
   }
   }
  }
 }
}
window.onload=startList;
