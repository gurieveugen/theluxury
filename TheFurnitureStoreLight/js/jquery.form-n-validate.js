/*!
 * jQuery Form Plugin
 * version: 2.52 (07-DEC-2010)
 * @requires jQuery v1.3.2 or later
 *
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function(b){b.fn.ajaxSubmit=function(t){if(!this.length){a("ajaxSubmit: skipping submit process - no element selected");return this}if(typeof t=="function"){t={success:t}}var h=this.attr("action");var d=(typeof h==="string")?b.trim(h):"";if(d){d=(d.match(/^([^#]+)/)||[])[1]}d=d||window.location.href||"";t=b.extend(true,{url:d,type:this.attr("method")||"GET",iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var u={};this.trigger("form-pre-serialize",[this,t,u]);if(u.veto){a("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(t.beforeSerialize&&t.beforeSerialize(this,t)===false){a("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var f,p,m=this.formToArray(t.semantic);if(t.data){t.extraData=t.data;for(f in t.data){if(t.data[f] instanceof Array){for(var i in t.data[f]){m.push({name:f,value:t.data[f][i]})}}else{p=t.data[f];p=b.isFunction(p)?p():p;m.push({name:f,value:p})}}}if(t.beforeSubmit&&t.beforeSubmit(m,this,t)===false){a("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[m,this,t,u]);if(u.veto){a("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var c=b.param(m);if(t.type.toUpperCase()=="GET"){t.url+=(t.url.indexOf("?")>=0?"&":"?")+c;t.data=null}else{t.data=c}var s=this,l=[];if(t.resetForm){l.push(function(){s.resetForm()})}if(t.clearForm){l.push(function(){s.clearForm()})}if(!t.dataType&&t.target){var r=t.success||function(){};l.push(function(n){var k=t.replaceTarget?"replaceWith":"html";b(t.target)[k](n).each(r,arguments)})}else{if(t.success){l.push(t.success)}}t.success=function(w,n,x){var v=t.context||t;for(var q=0,k=l.length;q<k;q++){l[q].apply(v,[w,n,x||s,s])}};var g=b("input:file",this).length>0;var e="multipart/form-data";var j=(s.attr("enctype")==e||s.attr("encoding")==e);if(t.iframe!==false&&(g||t.iframe||j)){if(t.closeKeepAlive){b.get(t.closeKeepAlive,o)}else{o()}}else{b.ajax(t)}this.trigger("form-submit-notify",[this,t]);return this;function o(){var k=s[0];if(b(":input[name=submit],:input[id=submit]",k).length){alert('Error: Form elements must not have name or id of "submit".');return}var z=b.extend(true,{},b.ajaxSettings,t);z.context=z.context||z;var C="jqFormIO"+(new Date().getTime()),x="_"+C;window[x]=function(){var n=q.data("form-plugin-onload");if(n){n();window[x]=undefined;try{delete window[x]}catch(L){}}};var q=b('<iframe id="'+C+'" name="'+C+'" src="'+z.iframeSrc+'" onload="window[\'_\'+this.id]()" />');var y=q[0];q.css({position:"absolute",top:"-1000px",left:"-1000px"});var v={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(){this.aborted=1;q.attr("src",z.iframeSrc)}};var H=z.global;if(H&&!b.active++){b.event.trigger("ajaxStart")}if(H){b.event.trigger("ajaxSend",[v,z])}if(z.beforeSend&&z.beforeSend.call(z.context,v,z)===false){if(z.global){b.active--}return}if(v.aborted){return}var D=false;var G=0;var w=k.clk;if(w){var E=w.name;if(E&&!w.disabled){z.extraData=z.extraData||{};z.extraData[E]=w.value;if(w.type=="image"){z.extraData[E+".x"]=k.clk_x;z.extraData[E+".y"]=k.clk_y}}}function F(){var N=s.attr("target"),L=s.attr("action");k.setAttribute("target",C);if(k.getAttribute("method")!="POST"){k.setAttribute("method","POST")}if(k.getAttribute("action")!=z.url){k.setAttribute("action",z.url)}if(!z.skipEncodingOverride){s.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(z.timeout){setTimeout(function(){G=true;B()},z.timeout)}var M=[];try{if(z.extraData){for(var O in z.extraData){M.push(b('<input type="hidden" name="'+O+'" value="'+z.extraData[O]+'" />').appendTo(k)[0])}}q.appendTo("body");q.data("form-plugin-onload",B);k.submit()}finally{k.setAttribute("action",L);if(N){k.setAttribute("target",N)}else{s.removeAttr("target")}b(M).remove()}}if(z.forceSync){F()}else{setTimeout(F,10)}var J,K,I=50;function B(){if(D){return}q.removeData("form-plugin-onload");var M=true;try{if(G){throw"timeout"}K=y.contentWindow?y.contentWindow.document:y.contentDocument?y.contentDocument:y.document;var Q=z.dataType=="xml"||K.XMLDocument||b.isXMLDoc(K);a("isXml="+Q);if(!Q&&window.opera&&(K.body==null||K.body.innerHTML=="")){if(--I){a("requeing onLoad callback, DOM not available");setTimeout(B,250);return}}D=true;v.responseText=K.documentElement?K.documentElement.innerHTML:null;v.responseXML=K.XMLDocument?K.XMLDocument:K;v.getResponseHeader=function(S){var R={"content-type":z.dataType};return R[S]};var P=/(json|script)/.test(z.dataType);if(P||z.textarea){var L=K.getElementsByTagName("textarea")[0];if(L){v.responseText=L.value}else{if(P){var O=K.getElementsByTagName("pre")[0];var n=K.getElementsByTagName("body")[0];if(O){v.responseText=O.textContent}else{if(n){v.responseText=n.innerHTML}}}}}else{if(z.dataType=="xml"&&!v.responseXML&&v.responseText!=null){v.responseXML=A(v.responseText)}}J=b.httpData(v,z.dataType)}catch(N){a("error caught:",N);M=false;v.error=N;b.handleError(z,v,"error",N)}if(v.aborted){a("upload aborted");M=false}if(M){z.success.call(z.context,J,"success",v);if(H){b.event.trigger("ajaxSuccess",[v,z])}}if(H){b.event.trigger("ajaxComplete",[v,z])}if(H&&!--b.active){b.event.trigger("ajaxStop")}if(z.complete){z.complete.call(z.context,v,M?"success":"error")}setTimeout(function(){q.removeData("form-plugin-onload");q.remove();v.responseXML=null},100)}function A(n,L){if(window.ActiveXObject){L=new ActiveXObject("Microsoft.XMLDOM");L.async="false";L.loadXML(n)}else{L=(new DOMParser()).parseFromString(n,"text/xml")}return(L&&L.documentElement&&L.documentElement.tagName!="parsererror")?L:null}}};b.fn.ajaxForm=function(c){if(this.length===0){var d={s:this.selector,c:this.context};if(!b.isReady&&d.s){a("DOM not ready, queuing ajaxForm");b(function(){b(d.s,d.c).ajaxForm(c)});return this}a("terminating; zero elements found by selector"+(b.isReady?"":" (DOM not ready)"));return this}return this.ajaxFormUnbind().bind("submit.form-plugin",function(f){if(!f.isDefaultPrevented()){f.preventDefault();b(this).ajaxSubmit(c)}}).bind("click.form-plugin",function(j){var i=j.target;var g=b(i);if(!(g.is(":submit,input:image"))){var f=g.closest(":submit");if(f.length==0){return}i=f[0]}var h=this;h.clk=i;if(i.type=="image"){if(j.offsetX!=undefined){h.clk_x=j.offsetX;h.clk_y=j.offsetY}else{if(typeof b.fn.offset=="function"){var k=g.offset();h.clk_x=j.pageX-k.left;h.clk_y=j.pageY-k.top}else{h.clk_x=j.pageX-i.offsetLeft;h.clk_y=j.pageY-i.offsetTop}}}setTimeout(function(){h.clk=h.clk_x=h.clk_y=null},100)})};b.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};b.fn.formToArray=function(q){var p=[];if(this.length===0){return p}var d=this[0];var g=q?d.getElementsByTagName("*"):d.elements;if(!g){return p}var k,h,f,r,e,m,c;for(k=0,m=g.length;k<m;k++){e=g[k];f=e.name;if(!f){continue}if(q&&d.clk&&e.type=="image"){if(!e.disabled&&d.clk==e){p.push({name:f,value:b(e).val()});p.push({name:f+".x",value:d.clk_x},{name:f+".y",value:d.clk_y})}continue}r=b.fieldValue(e,true);if(r&&r.constructor==Array){for(h=0,c=r.length;h<c;h++){p.push({name:f,value:r[h]})}}else{if(r!==null&&typeof r!="undefined"){p.push({name:f,value:r})}}}if(!q&&d.clk){var l=b(d.clk),o=l[0];f=o.name;if(f&&!o.disabled&&o.type=="image"){p.push({name:f,value:l.val()});p.push({name:f+".x",value:d.clk_x},{name:f+".y",value:d.clk_y})}}return p};b.fn.formSerialize=function(c){return b.param(this.formToArray(c))};b.fn.fieldSerialize=function(d){var c=[];this.each(function(){var h=this.name;if(!h){return}var f=b.fieldValue(this,d);if(f&&f.constructor==Array){for(var g=0,e=f.length;g<e;g++){c.push({name:h,value:f[g]})}}else{if(f!==null&&typeof f!="undefined"){c.push({name:this.name,value:f})}}});return b.param(c)};b.fn.fieldValue=function(h){for(var g=[],e=0,c=this.length;e<c;e++){var f=this[e];var d=b.fieldValue(f,h);if(d===null||typeof d=="undefined"||(d.constructor==Array&&!d.length)){continue}d.constructor==Array?b.merge(g,d):g.push(d)}return g};b.fieldValue=function(c,j){var e=c.name,p=c.type,q=c.tagName.toLowerCase();if(j===undefined){j=true}if(j&&(!e||c.disabled||p=="reset"||p=="button"||(p=="checkbox"||p=="radio")&&!c.checked||(p=="submit"||p=="image")&&c.form&&c.form.clk!=c||q=="select"&&c.selectedIndex==-1)){return null}if(q=="select"){var k=c.selectedIndex;if(k<0){return null}var m=[],d=c.options;var g=(p=="select-one");var l=(g?k+1:d.length);for(var f=(g?k:0);f<l;f++){var h=d[f];if(h.selected){var o=h.value;if(!o){o=(h.attributes&&h.attributes.value&&!(h.attributes.value.specified))?h.text:h.value}if(g){return o}m.push(o)}}return m}return b(c).val()};b.fn.clearForm=function(){return this.each(function(){b("input,select,textarea",this).clearFields()})};b.fn.clearFields=b.fn.clearInputs=function(){return this.each(function(){var d=this.type,c=this.tagName.toLowerCase();if(d=="text"||d=="password"||c=="textarea"){this.value=""}else{if(d=="checkbox"||d=="radio"){this.checked=false}else{if(c=="select"){this.selectedIndex=-1}}}})};b.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||(typeof this.reset=="object"&&!this.reset.nodeType)){this.reset()}})};b.fn.enable=function(c){if(c===undefined){c=true}return this.each(function(){this.disabled=!c})};b.fn.selected=function(c){if(c===undefined){c=true}return this.each(function(){var d=this.type;if(d=="checkbox"||d=="radio"){this.checked=c}else{if(this.tagName.toLowerCase()=="option"){var e=b(this).parent("select");if(c&&e[0]&&e[0].type=="select-one"){e.find("option").selected(false)}this.selected=c}}})};function a(){if(b.fn.ajaxSubmit.debug){var c="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(c)}else{if(window.opera&&window.opera.postError){window.opera.postError(c)}}}}})(jQuery);(function(a){a.extend(a.fn,{validate:function(b){if(!this.length){b&&b.debug&&window.console&&console.warn("nothing selected, can't validate, returning nothing");return}var c=a.data(this[0],"validator");if(c){return c}c=new a.validator(b,this[0]);a.data(this[0],"validator",c);if(c.settings.onsubmit){this.find("input, button").filter(".cancel").click(function(){c.cancelSubmit=true});if(c.settings.submitHandler){this.find("input, button").filter(":submit").click(function(){c.submitButton=this})}this.submit(function(d){if(c.settings.debug){d.preventDefault()}function e(){if(c.settings.submitHandler){if(c.submitButton){var f=a("<input type='hidden'/>").attr("name",c.submitButton.name).val(c.submitButton.value).appendTo(c.currentForm)}c.settings.submitHandler.call(c,c.currentForm);if(c.submitButton){f.remove()}return false}return true}if(c.cancelSubmit){c.cancelSubmit=false;return e()}if(c.form()){if(c.pendingRequest){c.formSubmitted=true;return false}return e()}else{c.focusInvalid();return false}})}return c},valid:function(){if(a(this[0]).is("form")){return this.validate().form()}else{var c=true;var b=a(this[0].form).validate();this.each(function(){c&=b.element(this)});return c}},removeAttrs:function(d){var b={},c=this;a.each(d.split(/\s/),function(e,f){b[f]=c.attr(f);c.removeAttr(f)});return b},rules:function(e,b){var g=this[0];if(e){var d=a.data(g.form,"validator").settings;var i=d.rules;var j=a.validator.staticRules(g);switch(e){case"add":a.extend(j,a.validator.normalizeRule(b));i[g.name]=j;if(b.messages){d.messages[g.name]=a.extend(d.messages[g.name],b.messages)}break;case"remove":if(!b){delete i[g.name];return j}var h={};a.each(b.split(/\s/),function(k,l){h[l]=j[l];delete j[l]});return h}}var f=a.validator.normalizeRules(a.extend({},a.validator.metadataRules(g),a.validator.classRules(g),a.validator.attributeRules(g),a.validator.staticRules(g)),g);if(f.required){var c=f.required;delete f.required;f=a.extend({required:c},f)}return f}});a.extend(a.expr[":"],{blank:function(b){return !a.trim(""+b.value)},filled:function(b){return !!a.trim(""+b.value)},unchecked:function(b){return !b.checked}});a.validator=function(b,c){this.settings=a.extend(true,{},a.validator.defaults,b);this.currentForm=c;this.init()};a.validator.format=function(b,c){if(arguments.length==1){return function(){var d=a.makeArray(arguments);d.unshift(b);return a.validator.format.apply(this,d)}}if(arguments.length>2&&c.constructor!=Array){c=a.makeArray(arguments).slice(1)}if(c.constructor!=Array){c=[c]}a.each(c,function(d,e){b=b.replace(new RegExp("\\{"+d+"\\}","g"),e)});return b};a.extend(a.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",validClass:"valid",errorElement:"label",focusInvalid:true,errorContainer:a([]),errorLabelContainer:a([]),onsubmit:true,ignore:[],ignoreTitle:false,onfocusin:function(b){this.lastActive=b;if(this.settings.focusCleanup&&!this.blockFocusCleanup){this.settings.unhighlight&&this.settings.unhighlight.call(this,b,this.settings.errorClass,this.settings.validClass);this.errorsFor(b).hide()}},onfocusout:function(b){if(!this.checkable(b)&&(b.name in this.submitted||!this.optional(b))){this.element(b)}},onkeyup:function(b){if(b.name in this.submitted||b==this.lastElement){this.element(b)}},onclick:function(b){if(b.name in this.submitted){this.element(b)}else{if(b.parentNode.name in this.submitted){this.element(b.parentNode)}}},highlight:function(d,b,c){a(d).addClass(b).removeClass(c)},unhighlight:function(d,b,c){a(d).removeClass(b).addClass(c)}},setDefaults:function(b){a.extend(a.validator.defaults,b)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",accept:"Please enter a value with a valid extension.",maxlength:a.validator.format("Please enter no more than {0} characters."),minlength:a.validator.format("Please enter at least {0} characters."),rangelength:a.validator.format("Please enter a value between {0} and {1} characters long."),range:a.validator.format("Please enter a value between {0} and {1}."),max:a.validator.format("Please enter a value less than or equal to {0}."),min:a.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:false,prototype:{init:function(){this.labelContainer=a(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&this.labelContainer||a(this.currentForm);this.containers=a(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={};this.valueCache={};this.pendingRequest=0;this.pending={};this.invalid={};this.reset();var b=(this.groups={});a.each(this.settings.groups,function(e,f){a.each(f.split(/\s/),function(h,g){b[g]=e})});var d=this.settings.rules;a.each(d,function(e,f){d[e]=a.validator.normalizeRule(f)});function c(g){var f=a.data(this[0].form,"validator"),e="on"+g.type.replace(/^validate/,"");f.settings[e]&&f.settings[e].call(f,this[0])}a(this.currentForm).validateDelegate(":text, :password, :file, select, textarea","focusin focusout keyup",c).validateDelegate(":radio, :checkbox, select, option","click",c);if(this.settings.invalidHandler){a(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler)}},form:function(){this.checkForm();a.extend(this.submitted,this.errorMap);this.invalid=a.extend({},this.errorMap);if(!this.valid()){a(this.currentForm).triggerHandler("invalid-form",[this])}this.showErrors();return this.valid()},checkForm:function(){this.prepareForm();for(var b=0,c=(this.currentElements=this.elements());c[b];b++){this.check(c[b])}return this.valid()},element:function(c){c=this.clean(c);this.lastElement=c;this.prepareElement(c);this.currentElements=a(c);var b=this.check(c);if(b){delete this.invalid[c.name]}else{this.invalid[c.name]=true}if(!this.numberOfInvalids()){this.toHide=this.toHide.add(this.containers)}this.showErrors();return b},showErrors:function(c){if(c){a.extend(this.errorMap,c);this.errorList=[];for(var b in c){this.errorList.push({message:c[b],element:this.findByName(b)[0]})}this.successList=a.grep(this.successList,function(d){return !(d.name in c)})}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){if(a.fn.resetForm){a(this.currentForm).resetForm()}this.submitted={};this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass)},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(d){var c=0;for(var b in d){c++}return c},hideErrors:function(){this.addWrapper(this.toHide).hide()},valid:function(){return this.size()==0},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid){try{a(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(b){}}},findLastActive:function(){var b=this.lastActive;return b&&a.grep(this.errorList,function(c){return c.element.name==b.name}).length==1&&b},elements:function(){var c=this,b={};return a([]).add(this.currentForm.elements).filter(":input").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){!this.name&&c.settings.debug&&window.console&&console.error("%o has no name assigned",this);if(this.name in b||!c.objectLength(a(this).rules())){return false}b[this.name]=true;return true})},clean:function(b){return a(b)[0]},errors:function(){return a(this.settings.errorElement+"."+this.settings.errorClass,this.errorContext)},reset:function(){this.successList=[];this.errorList=[];this.errorMap={};this.toShow=a([]);this.toHide=a([]);this.currentElements=a([])},prepareForm:function(){this.reset();this.toHide=this.errors().add(this.containers)},prepareElement:function(b){this.reset();this.toHide=this.errorsFor(b)},check:function(c){c=this.clean(c);if(this.checkable(c)){c=this.findByName(c.name)[0]}var h=a(c).rules();var d=false;for(method in h){var g={method:method,parameters:h[method]};try{var b=a.validator.methods[method].call(this,c.value.replace(/\r/g,""),c,g.parameters);if(b=="dependency-mismatch"){d=true;continue}d=false;if(b=="pending"){this.toHide=this.toHide.not(this.errorsFor(c));return}if(!b){this.formatAndAdd(c,g);return false}}catch(f){this.settings.debug&&window.console&&console.log("exception occured when checking element "+c.id+", check the '"+g.method+"' method",f);throw f}}if(d){return}if(this.objectLength(h)){this.successList.push(c)}return true},customMetaMessage:function(b,d){if(!a.metadata){return}var c=this.settings.meta?a(b).metadata()[this.settings.meta]:a(b).metadata();return c&&c.messages&&c.messages[d]},customMessage:function(c,d){var b=this.settings.messages[c];return b&&(b.constructor==String?b:b[d])},findDefined:function(){for(var b=0;b<arguments.length;b++){if(arguments[b]!==undefined){return arguments[b]}}return undefined},defaultMessage:function(b,c){return this.findDefined(this.customMessage(b.name,c),this.customMetaMessage(b,c),!this.settings.ignoreTitle&&b.title||undefined,a.validator.messages[c],"<strong>Warning: No message defined for "+b.name+"</strong>")},formatAndAdd:function(c,e){var d=this.defaultMessage(c,e.method),b=/\$?\{(\d+)\}/g;if(typeof d=="function"){d=d.call(this,e.parameters,c)}else{if(b.test(d)){d=jQuery.format(d.replace(b,"{$1}"),e.parameters)}}this.errorList.push({message:d,element:c});this.errorMap[c.name]=d;this.submitted[c.name]=d},addWrapper:function(b){if(this.settings.wrapper){b=b.add(b.parent(this.settings.wrapper))}return b},defaultShowErrors:function(){for(var c=0;this.errorList[c];c++){var b=this.errorList[c];this.settings.highlight&&this.settings.highlight.call(this,b.element,this.settings.errorClass,this.settings.validClass);this.showLabel(b.element,b.message)}if(this.errorList.length){this.toShow=this.toShow.add(this.containers)}if(this.settings.success){for(var c=0;this.successList[c];c++){this.showLabel(this.successList[c])}}if(this.settings.unhighlight){for(var c=0,d=this.validElements();d[c];c++){this.settings.unhighlight.call(this,d[c],this.settings.errorClass,this.settings.validClass)}}this.toHide=this.toHide.not(this.toShow);this.hideErrors();this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return a(this.errorList).map(function(){return this.element})},showLabel:function(c,d){var b=this.errorsFor(c);if(b.length){b.removeClass().addClass(this.settings.errorClass);b.attr("generated")&&b.html(d)}else{b=a("<"+this.settings.errorElement+"/>").attr({"for":this.idOrName(c),generated:true}).addClass(this.settings.errorClass).html(d||"");if(this.settings.wrapper){b=b.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()}if(!this.labelContainer.append(b).length){this.settings.errorPlacement?this.settings.errorPlacement(b,a(c)):b.insertAfter(c)}}if(!d&&this.settings.success){b.text("");typeof this.settings.success=="string"?b.addClass(this.settings.success):this.settings.success(b)}this.toShow=this.toShow.add(b)},errorsFor:function(c){var b=this.idOrName(c);return this.errors().filter(function(){return a(this).attr("for")==b})},idOrName:function(b){return this.groups[b.name]||(this.checkable(b)?b.name:b.id||b.name)},checkable:function(b){return/radio|checkbox/i.test(b.type)},findByName:function(b){var c=this.currentForm;return a(document.getElementsByName(b)).map(function(d,e){return e.form==c&&e.name==b&&e||null})},getLength:function(c,b){switch(b.nodeName.toLowerCase()){case"select":return a("option:selected",b).length;case"input":if(this.checkable(b)){return this.findByName(b.name).filter(":checked").length}}return c.length},depend:function(c,b){return this.dependTypes[typeof c]?this.dependTypes[typeof c](c,b):true},dependTypes:{"boolean":function(c,b){return c},string:function(c,b){return !!a(c,b.form).length},"function":function(c,b){return c(b)}},optional:function(b){return !a.validator.methods.required.call(this,a.trim(b.value),b)&&"dependency-mismatch"},startRequest:function(b){if(!this.pending[b.name]){this.pendingRequest++;this.pending[b.name]=true}},stopRequest:function(b,c){this.pendingRequest--;if(this.pendingRequest<0){this.pendingRequest=0}delete this.pending[b.name];if(c&&this.pendingRequest==0&&this.formSubmitted&&this.form()){a(this.currentForm).submit();this.formSubmitted=false}else{if(!c&&this.pendingRequest==0&&this.formSubmitted){a(this.currentForm).triggerHandler("invalid-form",[this]);this.formSubmitted=false}}},previousValue:function(b){return a.data(b,"previousValue")||a.data(b,"previousValue",{old:null,valid:true,message:this.defaultMessage(b,"remote")})}},classRuleSettings:{required:{required:true},email:{email:true},url:{url:true},date:{date:true},dateISO:{dateISO:true},dateDE:{dateDE:true},number:{number:true},numberDE:{numberDE:true},digits:{digits:true},creditcard:{creditcard:true}},addClassRules:function(b,c){b.constructor==String?this.classRuleSettings[b]=c:a.extend(this.classRuleSettings,b)},classRules:function(c){var d={};var b=a(c).attr("class");b&&a.each(b.split(" "),function(){if(this in a.validator.classRuleSettings){a.extend(d,a.validator.classRuleSettings[this])}});return d},attributeRules:function(c){var e={};var b=a(c);for(method in a.validator.methods){var d=b.attr(method);if(d){e[method]=d}}if(e.maxlength&&/-1|2147483647|524288/.test(e.maxlength)){delete e.maxlength}return e},metadataRules:function(b){if(!a.metadata){return{}}var c=a.data(b.form,"validator").settings.meta;return c?a(b).metadata()[c]:a(b).metadata()},staticRules:function(c){var d={};var b=a.data(c.form,"validator");if(b.settings.rules){d=a.validator.normalizeRule(b.settings.rules[c.name])||{}}return d},normalizeRules:function(c,b){a.each(c,function(f,e){if(e===false){delete c[f];return}if(e.param||e.depends){var d=true;switch(typeof e.depends){case"string":d=!!a(e.depends,b.form).length;break;case"function":d=e.depends.call(b,b);break}if(d){c[f]=e.param!==undefined?e.param:true}else{delete c[f]}}});a.each(c,function(d,e){c[d]=a.isFunction(e)?e(b):e});a.each(["minlength","maxlength","min","max"],function(){if(c[this]){c[this]=Number(c[this])}});a.each(["rangelength","range"],function(){if(c[this]){c[this]=[Number(c[this][0]),Number(c[this][1])]}});if(a.validator.autoCreateRanges){if(c.min&&c.max){c.range=[c.min,c.max];delete c.min;delete c.max}if(c.minlength&&c.maxlength){c.rangelength=[c.minlength,c.maxlength];delete c.minlength;delete c.maxlength}}if(c.messages){delete c.messages}return c},normalizeRule:function(c){if(typeof c=="string"){var b={};a.each(c.split(/\s/),function(){b[this]=true});c=b}return c},addMethod:function(b,d,c){a.validator.methods[b]=d;a.validator.messages[b]=c!=undefined?c:a.validator.messages[b];if(d.length<3){a.validator.addClassRules(b,a.validator.normalizeRule(b))}},methods:{required:function(c,b,e){if(!this.depend(e,b)){return"dependency-mismatch"}switch(b.nodeName.toLowerCase()){case"select":var d=a(b).val();return d&&d.length>0;case"input":if(this.checkable(b)){return this.getLength(c,b)>0}default:return a.trim(c).length>0}},remote:function(f,c,g){if(this.optional(c)){return"dependency-mismatch"}var d=this.previousValue(c);if(!this.settings.messages[c.name]){this.settings.messages[c.name]={}}d.originalMessage=this.settings.messages[c.name].remote;this.settings.messages[c.name].remote=d.message;g=typeof g=="string"&&{url:g}||g;if(d.old!==f){d.old=f;var b=this;this.startRequest(c);var e={};e[c.name]=f;a.ajax(a.extend(true,{url:g,mode:"abort",port:"validate"+c.name,dataType:"json",data:e,success:function(i){b.settings.messages[c.name].remote=d.originalMessage;var k=i===true;if(k){var h=b.formSubmitted;b.prepareElement(c);b.formSubmitted=h;b.successList.push(c);b.showErrors()}else{var l={};var j=(d.message=i||b.defaultMessage(c,"remote"));l[c.name]=a.isFunction(j)?j(f):j;b.showErrors(l)}d.valid=k;b.stopRequest(c,k)}},g));return"pending"}else{if(this.pending[c.name]){return"pending"}}return d.valid},minlength:function(c,b,d){return this.optional(b)||this.getLength(a.trim(c),b)>=d},maxlength:function(c,b,d){return this.optional(b)||this.getLength(a.trim(c),b)<=d},rangelength:function(d,b,e){var c=this.getLength(a.trim(d),b);return this.optional(b)||(c>=e[0]&&c<=e[1])},min:function(c,b,d){return this.optional(b)||c>=d},max:function(c,b,d){return this.optional(b)||c<=d},range:function(c,b,d){return this.optional(b)||(c>=d[0]&&c<=d[1])},email:function(c,b){return this.optional(b)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(c)},url:function(c,b){return this.optional(b)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(c)},date:function(c,b){return this.optional(b)||!/Invalid|NaN/.test(new Date(c))},dateISO:function(c,b){return this.optional(b)||/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(c)},number:function(c,b){return this.optional(b)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(c)},digits:function(c,b){return this.optional(b)||/^\d+$/.test(c)},creditcard:function(f,c){if(this.optional(c)){return"dependency-mismatch"}if(/[^0-9-]+/.test(f)){return false}var g=0,e=0,b=false;f=f.replace(/\D/g,"");for(var h=f.length-1;h>=0;h--){var d=f.charAt(h);var e=parseInt(d,10);if(b){if((e*=2)>9){e-=9}}g+=e;b=!b}return(g%10)==0},accept:function(c,b,d){d=typeof d=="string"?d.replace(/,/g,"|"):"png|jpe?g|gif";return this.optional(b)||c.match(new RegExp(".("+d+")$","i"))},equalTo:function(c,b,e){var d=a(e).unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){a(b).valid()});return c==d.val()}}});a.format=a.validator.format})(jQuery);(function(c){var b=c.ajax;var a={};c.ajax=function(e){e=c.extend(e,c.extend({},c.ajaxSettings,e));var d=e.port;if(e.mode=="abort"){if(a[d]){a[d].abort()}return(a[d]=b.apply(this,arguments))}return b.apply(this,arguments)}})(jQuery);(function(a){if(!jQuery.event.special.focusin&&!jQuery.event.special.focusout&&document.addEventListener){a.each({focus:"focusin",blur:"focusout"},function(c,b){a.event.special[b]={setup:function(){this.addEventListener(c,d,true)},teardown:function(){this.removeEventListener(c,d,true)},handler:function(f){arguments[0]=a.event.fix(f);arguments[0].type=b;return a.event.handle.apply(this,arguments)}};function d(f){f=a.event.fix(f);f.type=b;return a.event.handle.call(this,f)}})}a.extend(a.fn,{validateDelegate:function(d,c,b){return this.bind(c,function(e){var f=a(e.target);if(f.is(d)){return b.apply(f,arguments)}})}})})(jQuery);