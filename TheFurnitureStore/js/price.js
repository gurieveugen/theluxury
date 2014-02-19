///////////////////////////////////////////////////////////////////////  attribute calculation directly with JS //////////////////////////////

function collectAmounts(basisPrice,attrNum){
 
			//var numElements = window.document.forms['the_product'].length;
			var bp 			= Number(basisPrice);
			var amount		= Number("0.00");
			var vfactor		= 0;
			var attrData	= "";
			//alert(amount);
			
			
			for(i=0,a=1;i<attrNum;i++,a++){		

				var attr		= "attr_" + a;	
				var show_price 	= "attr_price_" + a;				

				var rawDazu		= document.forms['the_product'].elements[attr].options[document.forms['the_product'].elements[attr].options.selectedIndex].value;				
				
				
				//alert(rawDazu);
				if(rawDazu != "pch"){
					var parts 		= rawDazu.split("#");				
					var dazu		= Number(parts[0]);
					attrData		= attrData + "#" + parts[1] ;
				//alert(dazu);
				//alert(attrData);
				
				//document.getElementById(show_price).innerHTML 	= dazu.toFixed(2);
				amount 		+=   dazu;
				vfactor++;
				}

			}
			
			//alert(amount);
			//document.getElementById('basisPrice').innerHTML 	= bp.toFixed(2);		

			
			//alert(amount.toFixed(2));
			amount 												= amount + bp;		

			//alert(amount);
			document.getElementById('priceTotal').innerHTML 	= amount.toFixed(2);
			
			if(vfactor == attrNum){
				document.getElementById('addC').style.visibility	= "visible";
				document.getElementById('greyAdd').style.display	= "none";
			}
			else{
				document.getElementById('addC').style.visibility	= "hidden";
				document.getElementById('greyAdd').style.display	= "block";	
			}
			
			//alert(vfactor);
			
			document.getElementById('amount').setAttribute("value",amount);
			
			//alert(attrData);
			document.getElementById('attrData').setAttribute("value",attrData);
 }
 
 
 
function cartButtonVisbility(){

	//document.getElementById('addC').style.visibility	= "hidden";

}