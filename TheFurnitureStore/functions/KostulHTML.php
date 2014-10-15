<?php

class KostulHTML{
	//                                       __  _          
	//     ____  _________  ____  ___  _____/ /_(_)__  _____
	//    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
	//   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
	//  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
	// /_/              /_/                                 
	private $post;
	private $columns;
	private $option;
	private $permalink;
	private $title_attribute;
	public $spend_time;

	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct($post, $columns = 3, $option)
	{
		$this->post             = $post;
		$this->columns          = $columns;
		$this->option           = $option;
		$this->permalink        = get_permalink($this->post);
		$this->title_attribute  = esc_attr(strip_tags($this->post->post_title));
		$this->spen_time = '';
	}

	/**
	 * This post have hover effect ?
	 * @return boolean true if yes | false if not
	 */
	public function isHaveHover()
	{
		if($this->post->thumbnail != '' AND $this->post->thumbnail_hover != '') return true;
		return false;
	}

	/**
	 * Get product HTML
	 * @return string --- HTML code
	 */
	public function getHTML()
	{
		$start = microtime(true);
		$hover           = $this->isHaveHover() ? 'hover_link' : '';
		$thumbnail       = $this->post->thumbnail;
		$thumbnail_hover = $this->post->thumbnail_hover;
		
		ob_start();
		?>
		<div class="<?php echo $this->getCSS(); ?>">
			<div class="contentWrap">
				<div class="holder">
					<div class="images">
						<?php echo $this->getSoldOutHTML(); ?>
						<?php echo $this->wrapImage($thumbnail, $hover); ?>
						<?php echo $this->wrapImage($thumbnail_hover); ?>
					</div><!-- images end -->
					<?php $this->displayTeaser(); ?>
				</div><!-- holder end -->
				<?php $this->displayPrice(); ?>
				<?php $this->displayDaysAgo(); ?>
			</div><!-- contentWrap end -->
			<?php $this->displayPostSelection(); ?>
		</div><!-- c_box -->
		<?php		
		$this->spend_time = sprintf('%.4F', (microtime(true) - $start));
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}

	/**
	 * Get days ago block
	 */
	public function displayDaysAgo()
	{
		$days_ago = ceil((time() - $this->post->post_date) / 86400);
		if ($days_ago > 0 && $days_ago <= 30)
		{
			printf('<span class="date-info">added %s days ago</span>', $days_ago);
		}
	}

	/**
	 * Get price block
	 */
	public function displayPrice()
	{

		if ($this->post->new_price) 
		{ 
			?>
			<div class="price-box">
			<?php
				if ($this->post->price > $this->post->new_price) 
				{ 
					$perc = round(($this->post->price - $this->post->new_price) / ($this->post->price / 100));
					echo '<span class="discounts">'.$perc.'% off</span>';
				}
				?> 
				<h3>
					<strong>Now: <?php product_prices_list($this->post->new_price)?></strong>
				</h3>
			</div><!-- price-box end -->
		<?php
		}
		else
		{
		?>
			<div class="price-box">
				<h3>
					<strong><?php product_prices_list($this->post->price) ?></strong>
				</h3>
			</div><!-- price-box end -->
			<?php
		}			
	}

	/**
	 * Get teaser
	 */
	public function displayTeaser()
	{
		if($this->option['wps_teaser_enable_option']) 
		{
			$item_remarks = (string) get_post_meta($this->post->ID, 'item_remarks', true);
			$item_remarks = strlen($item_remarks) ? sprintf('<div class="item_description">%s</div><!-- item_description -->', $item_remarks) : '';
			?>
			<div class="teaser">
				<div class="prod-title-box">
					<h5 class="prod-title">
						<a href="<?php echo $this->permalink; ?>" title="<?php echo sprintf( __('Permalink to %s', 'wpShop'), $this->title_attribute ) ?>" rel="bookmark"><?php echo $this->post->post_title; ?></a>
					</h5>
				</div><!-- prod-title-box end -->
				<?php
				if ($this->option['wps_teaser2_enable_option']) 
				{ 
					echo $item_remarks;
				} 
				?>
				<p class="price_value">
				<?php
				if ($this->post->new_price && $this->post->price) 
				{ 
					?>
					<span class="was price">Was: <?php product_prices_list($this->post->price) ?></span>
					<?php
				}
				?>					
				</p><!-- price_value -->
			</div><!-- teaser end -->
			<?php
		}
	}

	public function displayPostSelection()
	{
		if($this->post->tax_selections <= 0) return '';
		$post_selection = get_term($this->post->tax_selections, 'selection');
		
		$item_selection = $post_selection->name; 
		$isarr   = explode(" ", strtolower($item_selection));
		$icon_lt = substr($isarr[0], 0, 1);
		if (count($isarr) > 1) 
		{
			$icon_lt .= substr($isarr[1], 0, 1);
		}
		?>
		<span class="ico-cond <?php echo $icon_lt ?>" title="<?php echo $item_selection ?>"></span>
		<?php
		
	}

	/**
	 * Wrap image url to image HTML tag
	 * @param  string $src --- image url
	 * @param  string $css --- css class
	 * @return string --- HTML code
	 */
	public function wrapImage($src, $css = '')
	{
		if(!strlen($src)) return '';
		ob_start();
		?>
			<a class="<?php echo $css; ?>" href="<?php echo $this->permalink; ?>" rel="bookmark" title="<?php echo sprintf( __('Permalink to %s', 'wpShop'), $this->title_attribute ) ?>">
				<img src="<?php bloginfo('template_url'); ?>/images/image-loader-224x224.gif" class="image-reload" data-original="<?php echo $src ?>" alt="<?php echo $this->title_attribute; ?>"/>
			</a>
		<?php
			
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}

	/**
	 * Get product CSS classes
	 * @return string --- CSS classes
	 */
	public function getCSS()
	{
		$classes = get_post_class(sprintf('c_box c_box%d', $this->columns), $this->post->ID);
		return join( ' ', $classes);
	}

	/**
	 * Get Sold Out HTML
	 * @return string --- HTML code
	 */
	public function getSoldOutHTML()
	{
		if($this->isSoldOut()) return '<span class="sold-out">Sold Out</span>';
		return '';
	}

	/**
	 * This product is Sold Out?
	 * @return boolean --- true if yes | false if not
	 */
	public function isSoldOut()
	{
		return !(int)$this->post->invsort;
	}
}