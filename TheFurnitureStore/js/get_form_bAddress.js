var xmlhttp;

function getBaddressForm(subfolder,protocol)
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
				
	var url		= protocol + NWS_template_directory_alt + "/ajax_form_bAddress.php";		
	var country = document.getElementById("billingCountry").options[document.getElementById("billingCountry").selectedIndex].value;
	option 		= document.getElementById("editOption").value;	

	url	= url+"?country="+country;	
	url	= url+"&option="+option;	
	url	= url+"&sid="+Math.random();
	url	= encodeURI(url);
	
	xmlhttp.onreadystatechange=stateBaddressChanged;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);			
}



function stateBaddressChanged()
{		
	if (xmlhttp.readyState==4)
	{	
		document.getElementById("billingAddressCheck").innerHTML 	= "";
		if(option == "billingAddressFE"){	
			document.getElementById("savedAddress").innerHTML 		= "";	
		}
		document.getElementById("billingAddress").innerHTML 		= xmlhttp.responseText;			
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


function copy_addr_form() {
	if(document.getElementById('display_switch').checked == true){ 
		document.getElementById('dfirstname').value = document.getElementById('firstname').value;
		document.getElementById('dlastname').value = document.getElementById('lastname').value;
		document.getElementById('dstreet_hsno').value = document.getElementById('street_hsno').value;
		
		if (document.getElementById('dhsno') != null){
			document.getElementById('dhsno').value = document.getElementById('hsno').value;
		}
		if (document.getElementById('dstrnam') != null){
			document.getElementById('dstrnam').value = document.getElementById('strnam').value;
		}
		if (document.getElementById('dstrno') != null){
			document.getElementById('dstrno').value = document.getElementById('strno').value;
		}
		if (document.getElementById('dpo') != null){
			document.getElementById('dpo').value = document.getElementById('po').value;
		}
		if (document.getElementById('dpb') != null){
			document.getElementById('dpb').value = document.getElementById('pb').value;
		}
		if (document.getElementById('dzone') != null){
			document.getElementById('dpzone').value = document.getElementById('pzone').value;
		}
		if (document.getElementById('dcrossstr') != null){
			document.getElementById('dcrossstr').value = document.getElementById('crossstr').value;
		}
		if (document.getElementById('dcolonyn') != null){
			document.getElementById('dcolonyn').value = document.getElementById('colonyn').value;
		}
		if (document.getElementById('ddistrict') != null){
			document.getElementById('ddistrict').value = document.getElementById('district').value;
		}
		if (document.getElementById('dregion') != null){
			document.getElementById('dregion').value = document.getElementById('region').value;
		}
		if (document.getElementById('dstate') != null){
			document.getElementById('dstate').value = document.getElementById('state').value;
		}
		if (document.getElementById('dzip') != null){
			document.getElementById('dzip').value = document.getElementById('zip').value;
		}
		if (document.getElementById('dtown') != null){
			document.getElementById('dtown').value = document.getElementById('town').value;
		}
		
	}
	else {
		document.getElementById('dfirstname').value = '';
		document.getElementById('dlastname').value = '';
		document.getElementById('dstreet_hsno').value = '';
		document.getElementById('dzip').value = '';
		document.getElementById('dtown').value = '';
	}
}	

		
		
function display_delivery_address(){

	if(document.getElementById('delivery_address').style.visibility == 'hidden'){
		document.getElementById('delivery_address').style.visibility = 'visible';
		document.getElementById('delivery_address_yes').style.visibility = 'visible';				
	}
	else {
		document.getElementById('delivery_address').style.visibility = 'hidden';
		document.getElementById('delivery_address_yes').style.visibility = 'hidden';
	}			

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