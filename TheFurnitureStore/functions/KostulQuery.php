<?php

class KostulQuery{
	//                                       __  _          
	//     ____  _________  ____  ___  _____/ /_(_)__  _____
	//    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
	//   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
	//  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
	// /_/              /_/                                 
	private $args;
	private $last_query_args;
	private $last_quer;
	private $last_where;
	private $allow_order_by_col;
	private $allow_order_by_type;

	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct()
	{
		$this->last_query_args = null;
		$this->last_query      = null;
		$this->last_where      = null;
		$this->allow_order_by_col = array(
			'post_date',
			'post_modified',
			'sort_price'
		);
		$this->allow_order_by_type = array(
			'DESC',
			'ASC'
		);
	}

	/**
	 * Get default tax values
	 * @return array --- tax values
	 */
	private function getDefaultTaxValues()
	{
		return array(
			'tax_cat_1'           => '',
			'tax_cat_2'           => '',	
			'tax_cat_3'           => '',	
			'tax_cat_4'           => '',	
			'tax_cat_5'           => '',
			'tax_sale'            => '',
			'tax_colours'         => '',
			'tax_sizes'           => '',
			'tax_ring_sizes'      => '',
			'tax_clothes_sizes'   => '',
			'tax_selections'      => '',
			'tax_brands'          => '',
			'tax_styles'          => '',
			'tax_prices'          => '',
			'tax_seller_category' => '',
			'tag'                 => ''
		);
	}

	/**
	 * Get allowed taxonomiess
	 * @return array --- allowed taxonomies
	 */
	private function getAllowedTaxonomies()
	{
		return array(
			'tax_cat_1',          
			'tax_cat_2',          	
			'tax_cat_3',          	
			'tax_cat_4',          	
			'tax_cat_5',          
			'tax_sale',           
			'tax_colours',        
			'tax_sizes',          
			'tax_ring_sizes',     
			'tax_clothes_sizes',  
			'tax_selections',     
			'tax_brands',         
			'tax_styles',         
			'tax_prices',         
			'tax_seller_category'
		);
	}

	/**
	 * Get products array from args
	 * @param  array $args --- query arguments
	 * @return array --- result
	 */
	public function makeRequestFromArgs($args)
	{	
		$start    = microtime(true);
		$defaults = array(
			'cats'          => $this->getDefaultTaxValues(),
			'offset'        => 0,
			'count'         => 0,
			'order_by_col'  => '',
			'order_by_type' => ''
		);
		$args  = array_merge($defaults, $args);
		$posts = $this->getProducts($args);
		$count = $this->getCount();
		
		return array(
			'posts'         => $posts,
			'visible_terms' => $this->getVisibleTerms($args['cats']),
			'last_args'     => $this->getLastArgs(),
			'last_query'    => $this->getLastQuery(),
			'count'         => $count,
			'spend_time'    => sprintf('%.4F', microtime(true) - $start)
		);
	}

	/**
	 * Get products array
	 * @param  array $args --- query arguments
	 * @param  $offset --- products offset
	 * @param  $count --- count limit 
	 * @param  $order_by_col --- column order
	 * @param  $order_by_type --- type order
	 * @return array --- result
	 */
	public function getProducts($args, $unlimited = false)
	{
		global $wpdb;

		$offset = 0;
		$count = 21;
		$order_by_col = 'post_date';
		$order_by_type = 'DESC';

		if ($args['cats']['tax_sale']) {
			$order_by_col = 'post_modified';
		}

		if ($args['offset']) { $offset = $args['offset']; }
		if ($args['count']) { $count = $args['count']; }
		if ($args['order_by_col']) { $order_by_col = $args['order_by_col']; }
		if ($args['order_by_type']) { $order_by_type = $args['order_by_type']; }

		$order_by_col  = $this->allow($order_by_col, $this->allow_order_by_col);
		$order_by_type = $this->allow($order_by_type, $this->allow_order_by_type);

		$where_args = array_merge($this->getDefaultTaxValues(), (array) $args['cats']);
		$where_args['wnew'] = $args['wnew'];
		$where_args['s'] = $args['s'];

		$where  = $this->initWhere($where_args);
		$offset = intval($offset);
		$count  = intval($count);
		$count  = $this->limit($count);
		if(is_array($where) AND count($where))
		{
			$where = ' AND '.implode(' AND ', $where);
		}
		else
		{
			$where = '';
		}

		$where = 'WHERE `post_status` = "publish" AND `post_type` = "post"'.$where;
		$this->last_where = $where;

		if(!$unlimited)
		{
			$query = sprintf(
				'SELECT *, IF(inventory > 0, 1, 0) as invsort, IF(new_price > 0, new_price, price) as sort_price FROM %sposts %s ORDER BY invsort DESC, `%s` %s LIMIT %d,%d', 
				$wpdb->prefix, 
				$where, 
				$order_by_col,
				$order_by_type,
				$offset, 
				$count
			);	
		}
		else
		{
			$query = sprintf(
				'SELECT *, IF(inventory > 0, 1, 0) as invsort, IF(new_price > 0, new_price, price) as sort_price FROM %sposts %s ORDER BY invsort DESC, `%s` %s', 
				$wpdb->prefix, 
				$where, 
				$order_by_col,
				$order_by_type
			);	
		}
		

		$this->last_query_args = array(
			'cats'          => $args['cats'],
			'wnew'          => $args['wnew'],
			's'             => $args['s'],
			'offset'        => $offset,
			'count'         => $count,
			'order_by_col'  => $order_by_col,
			'order_by_type' => $order_by_type
		);
		$this->last_query = $query;
		return $wpdb->get_results($query);
	}

	/**
	 * Init where 
	 * @param  array $args --- query arguments
	 * @return array --- initialized where
	 */
	public function initWhere($args)
	{
		$where = array();
		if(isset($args['tag']))
		{
			$tag_where = $this->initTagWhere($args['tag']);
			if(strlen($tag_where))
			{
				$where[] = 	$tag_where;
			}
			unset($args['tag']);
		}
		if($args['wnew'] == 'true')
		{
			$where[] = "wp_posts.post_date >= '" . date('Y-m-d', strtotime('-7 days')) . "'";
			unset($args['wnew']);
		}
		if(strlen(trim($args['s'])))
		{
			$svals = explode(' ', trim($args['s']));
			foreach($svals as $sval) {
				$sval = str_replace("'", "\'", $sval);
				$where[] = sprintf("(wp_posts.post_title LIKE '%s' OR wp_posts.post_content LIKE '%s')", '%'.$sval.'%', '%'.$sval.'%');
			}
			unset($args['s']);
		}
		foreach ($args as $key => $values) 
		{
			if(!empty($values))
			{
				if(is_array($values))
				{
					$value = implode(',', $values);
				}
				else
				{
					$value = $values;
				}	
				$value   = esc_sql($value);
				if ($key == 'tax_cat_3' || $key == 'tax_cat_4' || $key == 'tax_cat_5') {
					$where[] = sprintf('(tax_cat_3 IN (%s) OR tax_cat_4 IN (%s) OR tax_cat_5 IN (%s))', $value, $value, $value);
				} else {
					$where[] = sprintf('`%s` IN (%s)', $key, $value);
				}
			}
		}
		return $where;
	}

	/**
	 * Init tag Where
	 * @param  array $value --- items
	 * @return string --- where tag
	 */
	private function initTagWhere($value)
	{
		$items = '';
		$tags  = explode(',', $value);

		foreach ($tags as $t) 
		{
			if($t != '')
			{
				$items[] = '`tag` LIKE \'%"'.$t.'"%\'';	
			}
		}
		$items_str = implode(' OR ', $items);
		if($items_str == '') return '';
		return sprintf('(%s)', $items_str);
	}

	/**
	 * Limit counter
	 * @param  integer $val --- value
	 * @param  integer $min --- minimum
	 * @param  integer $max --- maximum
	 * @return integer limited
	 */
	private function limit($val, $min = 21, $max = 120)
	{
		if($val < $min) return $min;
		if($val > $max) return $max;
		return $val;
	}

	/**
	 * Allow string
	 * @param  string $value --- pretendent to allow
	 * @param  array $haystack --- allowed array strings
	 * @return string --- if succes return $value if not first array item
	 */
	private function allow($value, $haystack)
	{
		if(in_array($value, $haystack)) return $value;
		return $haystack[0];
	}

	/**
	 * Get visible categories
	 * @param  array $args --- query arguments
	 * @return array --- visible categories
	 */
	// public function getVisibleTerms($posts)
	// {
	// 	$visible    = array();
	// 	$taxonomies = $this->getAllowedTaxonomies();

	// 	if(is_array($posts) AND count($posts))
	// 	{
	// 		foreach ($taxonomies as $tax) 
	// 		{
	// 			foreach ($posts as $p) 
	// 			{
	// 				if(!in_array($p->{$tax}, $visible))
	// 				{
	// 					array_push($visible, $p->{$tax});
	// 				}		
	// 			}
	// 		}	
	// 	}
	// 	return $visible;
	// }

	/**
	 * Get visible categories
	 * @param  array $args --- query arguments
	 * @return array --- visible categories
	 */
	public function getVisibleTerms($args)
	{
		$fields = $this->getAllowedTaxonomies();
		$visible = array();
		$tmp = array();
		
		foreach ($fields as $field) 
		{
			$item = $this->getVisibleTermsByBlock($args, $field);
			if(is_array($item) AND count($item))
			{
				foreach ($item as $el) 
				{
					if(!in_array($el->{$field}, $visible))
					{
						array_push($visible, $el->{$field});
					}	
				}
			}
		}
		return $visible;
	}

	/**
	 * Filter not needed terms.
	 * @param  array $args --- query arguments
	 * @param  string $exclude --- exclude tax
	 * @return array --- query result
	 */
	public function getVisibleTermsByBlock($args, $exclude)
	{
		global $wpdb;
		$args  = array_merge($this->getDefaultTaxValues(), $args);
		unset($args[$exclude]);
		$where = $this->initWhere($args);
		if(is_array($where) AND count($where))
		{
			$where = ' AND '.implode(' AND ', $where);
		}
		else
		{
			$where = '';
		}
		$query = sprintf(
			'SELECT DISTINCT %s FROM %sposts WHERE `post_status` = "publish" AND `post_type` = "post"%s', 
			$exclude,
			$wpdb->prefix, 
			$where
		);
		return $wpdb->get_results($query);
	}

	/**
	 * Get Last args
	 * @return mixed --- array | null
	 */
	public function getLastArgs()
	{
		return $this->last_query_args;
	}

	/**
	 * Get last used query
	 * @return string --- MySQL query
	 */
	public function getLastQuery()
	{
		return $this->last_query;
	}

	/**
	 * Get count rows from query
	 * @param  array $args --- query result
	 * @return integer --- count
	 */
	public function getCount($args)
	{
		if(!$this->last_where) return 0;
		global $wpdb;

		$query = sprintf(
			'SELECT COUNT(*) FROM %sposts %s', 
			$wpdb->prefix, 
			$this->last_where
		);
		return intval($wpdb->get_var($query));

	}

	/**
	 * Count how mutch parents have a term
	 * @param  object  $p --- term object
	 * @param  integer $result --- temporary variable
	 * @return integer --- count parents
	 */
	public static function countParents($t, $result = 0)
	{
		if($t->parent != 0) return self::countParents(get_term($t->parent, $t->taxonomy), $result+1);
		return $result;
	}
}