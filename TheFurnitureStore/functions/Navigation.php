<?php

class Pagination{
	//                          __              __      
	//   _________  ____  _____/ /_____ _____  / /______
	//  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
	// / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
	// \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
	const NUMBERS_SMALL = 5;
	const NUMBERS_BIG   = 3;                                                 
	//                                       __  _          
	//     ____  _________  ____  ___  _____/ /_(_)__  _____
	//    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
	//   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
	//  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
	// /_/              /_/                                 
	private $count;
	private $climit;
	private $offset;

	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct($count, $limit, $offset)
	{
		$this->count  = intval($count);
		$this->limit  = intval($limit);
		$this->offset = intval($offset);
	}

	/**
	 * Get pagination HTML code
	 * @return string --- HTML code
	 */
	public function getHTML()
	{
		$pages   = '';
		$numbers = array_merge($this->getNumbersSmall(), $this->NumbersBig());
		foreach ($numbers as $page) 
		{
			$pages.= $this->wrapPageNUmber($page);
		}
		return $this->wrapPageNumbers($pages);
	}

	/**
	 * Get big numbers
	 * @return array --- big numbers
	 */
	private function getNumbersBig()
	{
		$current = ceil($this->getCurrent()/10)*10;
		$max     = $this->getPages();

		$result = array();
		for ($i=0; $i < self::NUMBERS_BIG ; $i++) 
		{ 
			$test = $current + ($i*10);
			if($test <= $max) array_push($result, $test);
		}
		return $result;
	}

	/**
	 * Get small numbers
	 * @return array --- small numbers
	 */
	private function getNumbersSmall()
	{
		$current = $this->getCurrent();
		$max     = $this->getPages();

		$result = array();
		for ($i=0; $i < self::NUMBERS_SMALL ; $i++) 
		{ 
			$test = $i + $current;
			if($test <= $max) array_push($result, $test);
		}
		return $result;
	}

	/**
	 * Wrap page number to HTML code
	 * @param  integer $i --- page number
	 * @return string --- HTML code
	 */
	private function wrapPageNumber($i)
	{
		if($i != $this->getCurrent())
		{	
			return sprintf('<a href="#%1$d" onclick="filter.getPage(event)" class="page" title="%1$s">%1$s</a>', $i);
		}
		return sprintf('<span class="current">%d</span>', $i);
	}

	/**
	 * Wrap page numbers to HTML code
	 * @param  string $numbers --- page numbers
	 * @return strin --- pagination HTML code
	 */
	private function wrapPageNumbers($numbers)
	{
		ob_start();
		?>
		<div class="clear"></div>
		<div class="nav-bottom-area">
			<div class="wp-pagenavi">
				<div class="holder">
					<?php echo $numbers; ?>
				</div>
				<span class="pages"><?php printf(__('Page %d of %d','wpShop'), $this->getCurrent(), $this->getPages()); ?> </span>
			</div>
		</div>
		<?php
		
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}

	/**
	 * Get count pages
	 * @return integer --- count all pages
	 */
	public function getPages()
	{
		return ceil($this->count/$this->limit);
	}

	/**
	 * Get current page number
	 * @return integer --- page number
	 */
	public function getCurrent()
	{
		return 1+ceil($this->offset/$this->limit);
	}

	/**
	 * Get items count
	 * @return integer --- all items count
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * Get limit count per page
	 * @return integer --- limit items per page
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * Get offset number
	 * @return integer --- offset number
	 */
	public function getOffset()
	{
		return $this->offset;
	}
}