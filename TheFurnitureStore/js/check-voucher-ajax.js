var xmlhttp;

function checkVoucher(subfolder,protocol,cust_id)
{
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	{
	  alert ("Browser does not support HTTP Request");
	  return;
	}
	  

	if(subfolder != "none"){
		subfolder 		= str_replace('##slash##', '/',subfolder);					//if slashes are left unmasked, this might cause 
																					// 'regular expression missing flag error'
		var addition 	= "/" + subfolder;
	}else{
		var addition = "";
	} 
	
	var url		= protocol + NWS_template_directory_alt + "/ajax_check_voucher.php";
	var vid 	= document.getElementById("vid").value;
							
	url	= url+"?vid="+vid+"&cid="+cust_id;	
	url	= url+"&sid="+Math.random();
	url	= encodeURI(url);
	xmlhttp.onreadystatechange=stateChangedVoucher;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}


function stateChangedVoucher()
{
	if (xmlhttp.readyState==4)
	{
	document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	}
}



function GetXmlHttpObject()
{
	if (window.XMLHttpRequest)
	  {
	  // code for IE7+, Firefox, Chrome, Opera, Safari
	  return new XMLHttpRequest();
	  }
	if (window.ActiveXObject)
	  {
	  // code for IE6, IE5
	  return new ActiveXObject("Microsoft.XMLHTTP");
	  }
return null;
}


function var_dump(obj) {
   if(typeof obj == "object") {
      return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
   } else {
      return "Type: "+typeof(obj)+"\nValue: "+obj;
   }
}

function str_replace(search, replace, subject, count) {

    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }

    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}