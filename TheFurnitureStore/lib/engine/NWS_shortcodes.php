<?php
##################################################################################################################################
// 												VIDEO SHORTCODES
##################################################################################################################################


//Vimeo, YouTube, Google Video, Blip TV, Veoh, Viddler, Revver


# Vimeo eg http://vimeo.com/5363880 id="5363880"
function vimeo_code($atts,$content = null){

	extract(shortcode_atts(array(  
		"id" 		=> '',
		"width"		=> 480, 
		"height" 	=> 360
	), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://vimeo.com/moogaloop.swf?clip_id=$id&amp;server=vimeo.com'
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://vimeo.com/moogaloop.swf?clip_id=$id&amp;server=vimeo.com' />
		</object>";
	return $data;
} 
add_shortcode("vimeo", "vimeo_code"); 

#### YouTube eg http://www.youtube.com/v/MWYi4_COZMU&hl=en&fs=1& id="MWYi4_COZMU&hl=en&fs=1&"
function youTube_code($atts,$content = null){

	extract(shortcode_atts(array(  
			 "id" 		=> '',
			 "width"	=> 480, 
			 "height" 	=> 360
		 ), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://www.youtube.com/v/$id' 
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='FlashVars' value='playerMode=embedded' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://www.youtube.com/v/$id' />
		</object>";
	return $data;
} 
add_shortcode("youtube", "youTube_code");

#### Google Video eg http://video.google.com/googleplayer.swf?docid=7664206256212725581&hl=en&fs=true id="7664206256212725581&hl=en&fs=true"
function googleVideo_code($atts,$content = null){

	extract(shortcode_atts(array(  
			 "id" 		=> '',
			 "width"	=> 480, 
			 "height" 	=> 360
		 ), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://video.google.com/googleplayer.swf?docid=$id' 
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://video.google.com/googleplayer.swf?docid=$id' />
		</object>";
	return $data;
} 
add_shortcode("googlevideo", "googleVideo_code");

#### Meta Cafe eg http://www.metacafe.com/fplayer/3025424/blue_iceberg.swf id="3025424/blue_iceberg.swf"
function metaCafe_code($atts,$content = null){

	extract(shortcode_atts(array(  
			 "id" 		=> '',
			 "width"	=> 480, 
			 "height" 	=> 360
		 ), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://www.metacafe.com/fplayer/$id' 
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://www.metacafe.com/fplayer/$id' />
		</object>";
	return $data;
} 
add_shortcode("metacafe", "metaCafe_code");

#### Blip TV eg http://blip.tv/play/AYGPryCBum0 id="AYGPryCBum0"
function blipTv_code($atts,$content = null){

	extract(shortcode_atts(array(  
			 "id" 		=> '',
			 "width"	=> 480, 
			 "height" 	=> 360
		 ), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://blip.tv/play/$id' 
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://blip.tv/play/$id' />
		</object>";
	return $data;
} 
add_shortcode("bliptv", "blipTv_code");

#### veoh eg http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&permalinkId=v17847048KQG6QD2r&player=videodetailsembedded&videoAutoPlay=0&id=anonymous id="v17847048KQG6QD2r"
function veoh_code($atts,$content = null){

	extract(shortcode_atts(array(  
			 "id" 		=> '',
			 "width"	=> 480, 
			 "height" 	=> 360
		 ), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&permalinkId=$id&player=videodetailsembedded&videoAutoPlay=0&id=anonymous' 
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&permalinkId=$id&player=videodetailsembedded&videoAutoPlay=0&id=anonymous' />
		</object>";
	return $data;
} 
add_shortcode("veoh", "veoh_code");

#### Viddler eg http://www.viddler.com/player/90b36677/ id="90b36677"
function viddler_code($atts,$content = null){

	extract(shortcode_atts(array(  
			 "id" 		=> '',
			 "width"	=> 480, 
			 "height" 	=> 360
		 ), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://www.viddler.com/player/$id/' 
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://www.viddler.com/player/$id/' />
		</object>";
	return $data;
} 
add_shortcode("viddler", "viddler_code");

#### Revver eg http://flash.revver.com/player/1.0/player.js?mediaId:99898;width:480;height:392; id="99898"
function revver_code($atts,$content = null){

	extract(shortcode_atts(array(  
			 "id" 		=> '',
			 "width"	=> 480, 
			 "height" 	=> 360
		 ), $atts)); 
	 
	$data = "<object	
		width='$width'
		height='$height'
		data='http://flash.revver.com/player/1.0/player.swf?mediaId=$id' 
		type='application/x-shockwave-flash'>
			<param name='allowfullscreen' value='true' />
			<param name='allowscriptaccess' value='always' />
			<param name='wmode' value='opaque'>
			<param name='movie' value='http://flash.revver.com/player/1.0/player.swf?mediaId=$id' />
		</object>";
	return $data;
} 
add_shortcode("revver", "revver_code");
?>