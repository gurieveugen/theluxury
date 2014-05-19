jQuery(document).ready(function(){
	jQuery('.menu li.noclick').each(function(){
		jQuery(this).find('a').eq(0).click(function(){
			return false;
		});
	});
});
jQuery(function() {
	initNav({
		menuId: "nav",
		cleverMode: true,
		hoverClass: "hover",
		flexibility: true,
		sideClasses: true,
		menuPaddings: 20,
		minWidth: 100
	});
	jQuery('#nav .drop .image-right, #nav .drop .image-left').css({'width':'auto','clear':'none'});
	jQuery('#nav > li:has(.drop)').addClass('has-drop');
	jQuery("#nav .title a").click(function(event) {
		event.preventDefault();
	});

	jQuery( '#nav li:has(ul)' ).not('.noclick').doubleTapToGo();
});

function initNav(o)
{
	if (!o.menuId) o.menuId = "main-nav";
	if (!o.cleverMode) o.cleverMode = false;
	if (!o.flexibility) o.flexibility = false;
	if (!o.dropExistenceClass) o.dropExistenceClass = false;
	if (!o.hoverClass) o.hoverClass = "hover";
	if (!o.menuHardCodeClass) o.menuHardCodeClass = "menu-hard-code";
	if (!o.sideClasses) o.sideClasses = false;
	if (!o.center) o.center = false;
	if (!o.menuPaddings) o.menuPaddings = 0;
	if (!o.minWidth) o.minWidth = 0;
	if (!o.coeff) o.coeff = 1.7;
	var n = document.getElementById(o.menuId);
	if(n)
	{
		n.className = n.className.replace(o.menuHardCodeClass, "");
		var lfl = [];
		var li = n.getElementsByTagName("li");
		for (var i=0; i<li.length; i++)
		{
			li[i].className += (" " + o.hoverClass);
			var d = li[i].getElementsByTagName("div").item(0);
			if(d)
			{
				if(o.flexibility)
				{
					var a = d.getElementsByTagName("a");
					for (var j=0; j<a.length; j++)
					{
						var w = a[j].parentNode.parentNode.offsetWidth;
						if(w > 0)
						{
							if(typeof(o.minWidth) == "number" && w < o.minWidth)
								w = o.minWidth;
							else if(typeof(o.minWidth) == "string" && li[i].parentNode == n && w < li[i].offsetWidth)
								w = li[i].offsetWidth - 3;
							a[j].style.width = w - o.menuPaddings + "px";
						}
					}
					d.style.width = li[i].getElementsByTagName("div").item(1).clientWidth + 1 + "px";
				}
				var t = document.documentElement.clientWidth/o.coeff;
				if(li[i].parentNode != n && (!o.cleverMode || fPX(li[i]) < t))
				{
					d.style.right = "auto";
					d.style.left = li[i].parentNode.offsetWidth + "px";
					d.parentNode.className += " left-side";
				}	
				else if(li[i].parentNode != n && (o.cleverMode || fPX(li[i]) >= t))
				{
					d.style.left = "auto";
					d.style.right = li[i].parentNode.offsetWidth + "px";
					d.parentNode.className += " right-side";
				}
				else if(li[i].parentNode == n && o.cleverMode && fPX(li[i]) >= t)
				{
					li[i].className += " right-side";
				}
				if(li[i].parentNode == n && o.center)
					d.style.left = -li[i].getElementsByTagName("div").item(1).clientWidth/2 + li[i].clientWidth/2 + "px";
			}
			if(o.dropExistenceClass && li[i].getElementsByTagName("ul").length > 0)
			{
				li[i].className += (" " + o.dropExistenceClass);
				li[i].getElementsByTagName("a").item(0).className += (" " + o.dropExistenceClass + "-link");
				li[i].innerHTML += "<em class='pointer'></em>";
			}
			if(li[i].parentNode == n) lfl.push(li[i]);
		}
		if(o.sideClasses)
		{
			lfl[0].className += " first-child";
			lfl[0].getElementsByTagName("a").item(0).className += " first-child-link";
			lfl[lfl.length-1].className += " last-child";
			lfl[lfl.length-1].getElementsByTagName("a").item(0).className += " last-child-link";
		}
		for (var i=0; i<li.length; i++)
		{
			li[i].className = li[i].className.replace(o.hoverClass, "");
			li[i].onmouseover = function()
			{
				this.className += (" " + o.hoverClass);
			}
			li[i].onmouseout = function()
			{
				this.className = this.className.replace(o.hoverClass, "");
			}
		}
	}
	function fPX(a)
	{
		var b = 0;
		while (a.offsetParent) {b += a.offsetLeft; a = a.offsetParent;}
		return b;
	}
}