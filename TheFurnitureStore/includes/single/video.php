<?php 
if($OPTION['wps_imagesTab_enable']) { $the_object_class='theProdMedia';} else {$the_object_class='';}

switch($videoMatches[1][0]){
	
	case 'vimeo': ?>
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0]; ?>" height="<?php echo $videoMatches[4] [0];?>" data="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $videoMatches[2] [0]; ?>&amp;server=vimeo.com" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $videoMatches[2] [0]; ?>&amp;server=vimeo.com"/>
			</object>	
		</div>		
	<?php break;

	case 'youtube': ?> 
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0];?>" height="<?php echo $videoMatches[4] [0]; ?>" data="http://www.youtube.com/v/<?php echo $videoMatches[2] [0]; ?>" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://www.youtube.com/v/<?php echo $videoMatches[2] [0]; ?>"/>
			</object>
		</div>
	<?php break;

	case 'googlevideo': ?>
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0]; ?>" height="<?php echo $videoMatches[4] [0]; ?>" data="http://video.google.com/googleplayer.swf?docid=<?php echo $videoMatches[2] [0];?>" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://video.google.com/googleplayer.swf?docid=<?php echo $videoMatches[2] [0]; ?>"/>
			</object>
		</div>
	<?php break;

	case 'metacafe':?>
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0]; ?>" height="<?php echo $videoMatches[4] [0]; ?>" data="http://www.metacafe.com/fplayer/<?php echo $videoMatches[2] [0]; ?>" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://www.metacafe.com/fplayer/<?php echo $videoMatches[2] [0]; ?>"/>
			</object>
		</div>
	<?php break;

	case 'bliptv': ?>
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0]; ?>" height="<?php echo $videoMatches[4] [0]; ?>" data="http://blip.tv/play/<?php echo $videoMatches[2] [0]; ?>" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://blip.tv/play/<?php echo $videoMatches[2] [0]; ?>"/>
			</object>
		</div>
	<?php break;

	case 'veoh':?>
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0]; ?>" height="<?php echo $videoMatches[4] [0]; ?>" data="http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&permalinkId=<?php echo $videoMatches[2] [0]; ?>&player=videodetailsembedded&videoAutoPlay=0&id=anonymous" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&permalinkId=<?php echo $videoMatches[2] [0]; ?>&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"/>
			</object>
		</div>
	<?php break;

	case 'viddler': ?>
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0]; ?>" height="<?php echo $videoMatches[4] [0]; ?>" data="http://www.viddler.com/player/<?php echo $videoMatches[2] [0];?>" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://www.viddler.com/player/<?php echo $videoMatches[2] [0]; ?>"/>
			</object>
		</div>
	<?php break;

	case 'revver': ?>
		<div class="<?php echo $the_object_class; ?>">
			<object width="<?php echo $videoMatches[3] [0]; ?>" height="<?php echo $videoMatches[4] [0]; ?>" data="http://flash.revver.com/player/1.0/player.swf?mediaId=<?php echo $videoMatches[2] [0]; ?>" type="application/x-shockwave-flash">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="opaque" />
				<param name="movie" value="http://flash.revver.com/player/1.0/player.swf?mediaId=<?php echo $videoMatches[2] [0]; ?>"/>
			</object>
		</div>		
	<?php break;
	
	case 'flowplayer': ?>
		<div class="flowplayer_wrap <?php echo $the_object_class; ?>">
			<!-- setup player container --> 
			<a href="<?php echo $videoMatches[2] [0]; ?>" title="<?php _e('Click to play','wpShop'); ?>" style="background:url(<?php bloginfo('stylesheet_directory'); ?>/images/play_large.png) no-repeat center;display:block;width:<?php echo $videoMatches[3] [0]; ?>px;height:<?php echo $videoMatches[4] [0];?>px;margin:0 auto;" id="player">
				<!-- HTML based splash image --> 
				<!--<img src="<?php bloginfo('stylesheet_directory'); ?>/images/play_large.png" alt="" />-->
			</a> 
		
			<!-- this will install flowplayer inside previous A- tag. -->
			<script>
				$f("player", "<?php bloginfo('template_directory'); ?>/swf/flowplayer-3.1.5.swf", { 
					plugins: { 
						audio: { 
							url: '<?php bloginfo('template_directory'); ?>/swf/flowplayer.audio-3.1.2.swf' 
						} 
					} 
				});
			</script>
		</div><!-- player_wrap-->
		
	<?php break;
} ?>