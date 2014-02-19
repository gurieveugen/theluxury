var xmlhttp;

function checkStock(str,subfolder,basisPrice,attrNum)
{			
	var stock_ctrl = tracking;	
	
	if(stock_ctrl == "off"){
	
	
	}
	else{

		xmlhttp=GetXmlHttpObject();
		if (xmlhttp==null)
		{
		  alert ("Browser does not support HTTP Request");
		return;
		}
		  
		  
		if(subfolder != "/none/"){
			subfolder 		= String(subfolder); 
			var addition 	= "/" + subfolder.slice(1,-1);
		}else{
			var addition 	= "";
		}    
	}
	/////////////////

	var attr_option	= document.getElementById("attr_option").value;
		  

		switch(attr_option){

			case '1':
			
				var x 				= document.getElementById("the_product");
				var attributes 		= new Array();
				var attr_get_p 		= '';
				var attrData		= '#';
				var counter1		= 0;
				var counter2		= 0;
				
				for (var i=0;i<x.length;i++)
				{					
						if(typeof x[i].name.split("item_attr_")[1] != "undefined"){					  
							counter1++;
						}
				}
				
				for (var i=0;i<x.length;i++)
				{		
						if((typeof x[i].name.split("item_attr_")[1] != "undefined") && (x[i].value != 'pch')){					  
						  attributes[i] = x[i].name.split("item_attr_")[1] + "=" + x[i].value + "&";
						  attrData		= attrData + x[i].name.split("item_attr_")[1] + "=" + x[i].value + "#";
						}
				}
					
				
				for (var i=0;i<attributes.length;i++)
				{
					if(typeof(attributes[i]) != "undefined"){
						attr_get_p = attr_get_p + attributes[i];
						counter2++;
					}
				}
				
				
				//needed for post data 
				attrData = attrData.slice(0,-1);
				document.getElementById('attrData').setAttribute("value",attrData);		
				
				//alert(attr_get_p +"| Counter: " + counter2 + "| Required GET param: " + counter1);
			
			break;

			case '2':
				collectAmounts(basisPrice,attrNum);
				
				var x 				= document.getElementById("the_product");
				var attributes 		= new Array();
				var attr_get_p 		= '';
				var counter1		= 0;
				var counter2		= 0;
			
				
				for (var i=0;i<x.length;i++)
				{											
						if(typeof x[i].name.split("item_attr_")[1] != "undefined"){								
							counter1++;
						}
				}
				
				for (var i=0;i<x.length;i++)
				{		
						if((typeof x[i].name.split("item_attr_")[1] != "undefined") && (x[i].value != 'pch')){
						attributes[i] = x[i].name.split("item_attr_")[1] + "=" + x[i].value.split("=")[1] + "&"; 
						}
				}
				
				for (var i=0;i<attributes.length;i++)
				{	
					if(typeof(attributes[i]) != "undefined"){
						attr_get_p = attr_get_p + attributes[i];
						counter2++;
					}
				}			
				
				//alert(attr_get_p +"| Counter: " + counter2 + "| Required GET param: " + counter1);						
			break;

		}

	var child_th_url = document.getElementById('child_theme_url').innerHTML;	
	
	if(stock_ctrl == "off"){
	
		if(counter1 == counter2){
			document.getElementById('txtHint').innerHTML	= "<input type='image' id='addC' name='add' style='visibility:visible;' class='input_image' src='" + child_th_url + "/images/add_to_cart.png' />";
		}
		else {
			document.getElementById('txtHint').innerHTML				= "<img src='" + child_th_url + "/images/add_to_cart_grey.png' id='greyAdd' name='greyAdd' style='visibility:visible;' />";
		}
	
	}
	else{

		if(counter1 == counter2){

			var	equal	= 1;
			var url		= NWS_template_directory +"/ajax_check_stock.php";
			var item_id = document.getElementById("item_id").value;
			
			url			= url+"?"+attr_get_p+"option="+attr_option+"&id="+item_id;		
			url			= url+"&sid="+Math.random();
			url			= encodeURI(url);

			xmlhttp.onreadystatechange=stateChangedStock;
			xmlhttp.open("GET",url,true);
			xmlhttp.send(null);

		}
		else {
			//var child_th_url 											= document.getElementById('child_theme_url').innerHTML;
			document.getElementById('txtHint').innerHTML				= "<img src='" + child_th_url + "/images/add_to_cart_grey.png' id='greyAdd' name='greyAdd' style='visibility:visible;' />";
			document.getElementById('stock_amount').style.visibility 	= 'hidden';	
		}
	}
}



function stateChangedStock()
{
	if (xmlhttp.readyState==4)
	{
		var result = xmlhttp.responseText.split('%');
		document.getElementById("txtHint").innerHTML = result[0];
		var response 	= document.getElementById("txtHint").innerHTML;
		var pos_search	= response.search(/<input/i);
		
		document.getElementById('stock_amount').style.visibility 	= 'visible';	
		document.getElementById('stock_amount').innerHTML 			= result[1];
		
		if(pos_search == 0){
		//document.getElementById('greyAdd').style.visibility = 'hidden';
		}
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



function collectAmounts(basisPrice,attrNum){
 
			var numElements = window.document.forms['the_product'].length;		
			var bp 			= Number(basisPrice);
			var amount		= Number("0.00");
			var vfactor		= 0;
			var attrData	= "";
			
			for(i=0,a=1;i<attrNum;i++,a++){		

				var attr		= "attr_" + a;	
				var show_price 	= "attr_price_" + a;				
				var rawDazu		= document.forms['the_product'].elements[attr].options[document.forms['the_product'].elements[attr].options.selectedIndex].value;				

				if(rawDazu != "pch"){
					var parts 		= rawDazu.split("#");				
					var dazu		= Number(parts[0]);
					attrData		= attrData + "#" + parts[1];
					amount 			+=   dazu;
					vfactor++;
				}

			}
			
			amount 												= amount + bp;		
			document.getElementById('priceTotal').innerHTML 	= amount.toFixed(2);
			
			if(vfactor == attrNum){
				//document.getElementById('addC').style.visibility	= "visible";
				//document.getElementById('greyAdd').style.display	= "none";
			}
			else{
				//document.getElementById('addC').style.visibilty	= "hidden";
				//document.getElementById('greyAdd').style.display	= "block";	
			}

			document.getElementById('amount').setAttribute("value",amount);
			document.getElementById('attrData').setAttribute("value",attrData);		
 }
 


 
function var_dump(obj){
   if(typeof obj == "object") {
      return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
   } else {
      return "Type: "+typeof(obj)+"\nValue: "+obj;
   }
}
