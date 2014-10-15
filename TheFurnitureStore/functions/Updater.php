<?php
require($_SERVER["DOCUMENT_ROOT"].'/wp-blog-header.php');
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
set_time_limit (6000);

class Updater{
	//                          __              __      
	//   _________  ____  _____/ /_____ _____  / /______
	//  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
	// / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
	// \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
	const FIELD_ID_ITEM   = 'ID_item';
	const FIELD_NEW_PRICE = 'new_price';
	const FIELD_PRICE     = 'price';

	//                                       __  _          
	//     ____  _________  ____  ___  _____/ /_(_)__  _____
	//    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
	//   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
	//  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
	// /_/              /_/                                 
	private $allowed_parent_ids;
	private $updated;
	private $thumbnail_size_w;
	private $thumbnail_size_h;
	private $option;

	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct($request)
	{
		global $OPTION, $wpdb;
		$this->option = $OPTION;
		$this->thumbnail_size_w = get_option('thumbnail_size_w');
		$this->thumbnail_size_h = get_option('thumbnail_size_h');
		$this->updated = array();
		$this->allowed_parent_ids = array(156, 418);
		if(isset($request['offset']) AND isset($request['posts_per_page']))
		{
			$query = sprintf(
				'SELECT * FROM %sposts WHERE `post_type` = "post" ORDER BY `post_date` DESC LIMIT %d,%d', 
				$wpdb->prefix, 
				$request['offset'], 
				$request['posts_per_page']
			);
			
			$posts   = $wpdb->get_results($query);
			foreach ($posts as $p) 
			{
				$this->savePost($p->ID);
			}
			$this->mailDebug(
				array(
					intval($request['offset']), 
					intval($request['posts_per_page']), 
					$this->updated,
					count($posts)
				)
			);
			echo '<pre>';
			var_dump($query, count($posts));
			echo '</pre>';
		}
		
	}
	
	public function savePost($post_id)
	{
		$row = $this->getRow($post_id);
		$this->updated[$post_id] = $this->updateRow($post_id, $row);
	}

	/**
	 * Get row values from post
	 * @param  integer $post_id --- post ID
	 * @return array --- row
	 */
	public function getRow($post_id)
	{
		$row = array(
			'tax_cat_1'         => 0,
			'tax_cat_2'         => 0,	
			'tax_cat_3'         => 0,	
			'tax_cat_4'         => 0,	
			'tax_cat_5'         => 0,
			'tax_sale'          => 0,
			'tax_colours'       => 0,
			'tax_sizes'         => 0,
			'tax_ring_sizes'    => 0,
			'tax_clothes_sizes' => 0,
			'tax_selections'    => 0,
			'tax_brands'        => 0,
			'tax_styles'        => 0,
			'tax_prices'        => 0,
			'price'             => 0,
			'new_price'         => 0,
			'thumbnail'         => '',
			'thumbnail_hover'   => '',
			'id_item'           => '',
		);

		// ==============================================================
		// Custom fields
		// ==============================================================
		$row['id_item']   = strval(get_post_meta($post_id, self::FIELD_ID_ITEM, true));
		$row['new_price'] = floatval(get_post_meta($post_id, self::FIELD_NEW_PRICE, true));
		$row['price']     = floatval(get_post_meta($post_id, self::FIELD_PRICE, true));
		// ==============================================================
		// Terms
		// ==============================================================
		$terms = $this->getTerms(
			$post_id,
			array( 
				'colour',
				'size',
				'ring-size',
				'clothes-size',
				'selection',
				'brand',
				'style',
				'price',
				'seller-category'
			)
		);
		$cats = $this->getCats($post_id);
		
		$row['tax_sale']            = $cats['sale'];
		$row['tax_cat_1']           = $cats[0];
		$row['tax_cat_2']           = $cats[1];
		$row['tax_cat_3']           = $cats[2];
		$row['tax_cat_4']           = $cats[3];
		$row['tax_cat_5']           = $cats[4];
		$row['tax_colours']         = $terms['colour'];
		$row['tax_sizes']           = $terms['size'];
		$row['tax_ring_sizes']      = $terms['ring-size'];
		$row['tax_clothes_sizes']   = $terms['clothes-size'];
		$row['tax_selections']      = $terms['selection'];
		$row['tax_brands']          = $terms['brand'];
		$row['tax_styles']          = $terms['style'];
		$row['tax_prices']          = $terms['price'];
		$row['tax_seller_category'] = $terms['seller-category'];

		// ==============================================================
		// Images
		// ==============================================================
		
		$images                 = $this->getPostImages($post_id, array('numberposts' => 2));
		$row['thumbnail']       = isset($images[0]) ? $this->getImage($images[0]) : '';
		$row['thumbnail_hover'] = isset($images[1]) ? $this->getImage($images[1]) : '';

		return $row;
	}

	/**
	 * Get image
	 * @param  integer $ID --- attachment id
	 * @return string --- image url
	 */
	public function getImage($ID)
	{
		if(!$ID) return '';
		$img_src = wp_get_attachment_image_src($ID, 'full');
		if(is_array($img_src)) $img_src = $img_src[0];

		if ($this->option['wps_wp_thumb']) // do we want the WordPress Generated thumbs?
		{
			$img_file_type = strrchr($img_src, '.'); //get the file type
			$parts         = explode($img_file_type, $img_src); //get the image name without the file type
			$width         = $this->thumbnail_size_w;
			$height        = $this->thumbnail_size_h;
			$img_url       = $parts[0].'-'.$width.'x'.$height.$img_file_type; //put everything together
			
		// no? then display the default proportionally resized thumbnails
		} 
		else 
		{
			$des_src  = $this->option['upload_path'].'/cache';	
			$img_file = mkthumb($img_src, $des_src, $this->getImageSize(), 'width');
			$img_url  = get_option('siteurl').'/'.$des_src.'/'.$img_file;	
		}
		return $img_url;
	}

	/**
	 * Get image size
	 * @return integer --- image size
	 */
	public function getImageSize()
	{
		return 232;
		$key = sprintf('wps_prodCol%d_img_size', 3);
		return $this->option[$key];
	}

	/**
	 * Update row
	 * @param  integer $post_id --- post ID
	 * @param  array $row --- row array
	 * @return mixed --- integer if succes | false if not
	 */
	public function updateRow($post_id, $row)
	{
		global $wpdb;

		return $wpdb->update( 
			sprintf('%sposts', $wpdb->prefix), 
			$row, 
			array('ID' => intval($post_id))
		);
	}

	/**
	 * Get all parents terms
	 * @param  object $term --- child term
	 * @param  array  $arr --- recursive paretns array
	 * @return array
	 */
	public function getParents($term, $arr = array())
	{
		if($term->parent != 0)
		{
			$parent = get_term_by('id', $term->parent, $term->taxonomy);
			$arr[] = $parent;
			return $this->getParents($parent, $arr);
		}
		return $arr;
	}
	/**
	 * Get categories 
	 * @param  integer $post_id --- post ID
	 * @return array --- categories
	 */
	public function getCats($post_id)
	{
		$result = array(
			'sale' => 0,
			0      => 0,
			1      => 0,
			2      => 0,
			3      => 0,
			4      => 0
		);

		$cats = wp_get_post_terms($post_id, 'category');
		if(is_array($cats) AND count($cats))
		{
			foreach ($cats as $cat) 
			{
				if($cat->slug == 'sale')
				{
					$result['sale'] = $cat->term_id;	
				} 
				else
				{
					$depth          = count($this->getParents($cat));
					$result[$depth] = $cat->term_id;
				}
			}
		}
		return $result;
	}

	/**
	 * This is Sale product? If yes return id 
	 * @param  array $cats --- categories
	 * @return integer --- id if success | 0 if not
	 */
	public function isSale($cats)
	{
		if(is_array($cats))
		{
			foreach ($cats as &$cat) 
			{
				if($cat->slug == 'sale')
				{
					return $cat->term_id;	
				} 
			}
		}
		return 0;
	}

	/**
	 * Get all needed terms from posts
	 * @param  integer $post_id --- post ID
	 * @param  array $taxonomies --- taxonomies
	 * @return mixed --- array if succes | false if not
	 */
	public function getTerms($post_id, $taxonomies)
	{
		$result = array();
		if(is_array($taxonomies))
		{
			foreach ($taxonomies as $tax) 
			{
				$result[$tax] = $this->getTermID($post_id, $tax);
			}
			return $result;
		}
		return false;
	}

	/**
	 * Get tax ID
	 * @param  integer $post_id --- post id
	 * @param  string $tax --- taxonomy
	 * @return integer --- ID if success | 0 if not
	 */
	public function getTermID($post_id, $tax)
	{
		$colours = wp_get_post_terms($post_id, $tax, array('fields' => 'ids'));
		if(is_array($colours) AND count($colours)) return intval($colours[0]);
		return 0;
	}

	/**
	 * Get attachment images form post
	 * @param  integer $post_id --- post id
	 * @param  array  $args --- query options
	 * @return mixed --- array if success | false if not
	 */
	public function getPostImages($post_id, $args = array())
	{
		$defaults = array(
			'post_parent'    => $post_id,
			'post_type'      => 'attachment',
			'order'          => 'ASC', 
			'orderby'        => 'menu_order ID',
			'numberposts'    => -1,
			'post_mime_type' => 'image',
			'fields'         => 'ids'
		);
		$images = get_posts(array_merge($defaults, $args));
		if(count($images)) return $images;
		return false;
	}

	/**
	 * Send debug information to email
	 * @param  mixed $args --- debug info
	 * @return boolean     --- return mail function result
	 */
	public function mailDebug($args)
	{
		return mail('tatarinfamily@gmail.com', 'UpdatePostData', print_r($args, true));
	}
}

// ==============================================================
// Launch
// ==============================================================
$update = new Updater($_GET);