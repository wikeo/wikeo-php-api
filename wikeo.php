<?php
/**
 * PHP library for Wikeo's API
 * @author Kevin Rapaille <kevinrapaille@reakserv.net>
 */
class Wikeo
{
	/**
	 * @var string $domain_url Url of API's racine.
	 */
	private static $domain_url = 'http://api.monsiteweb.net/';
	/**
	 * @var string $token Session's token.
	 */
	private $token;
	
	// AUTH
	
	/**
	 * Constructor for authentification
	 * 
	 * @param string $email user's email.
	 * @param string $password user's password, not hashed.
	 * @return void
	 */
	public function Wikeo($email, $password)
    {
    	$ret = $this->get('/auth/?email='.$email.'&password='.md5($password));
    	
    	if(strlen($ret) == 32)
		{
			$this->token = $ret;
		}
		else
		{
			throw new Exception($ret);
		}
    }
    
    /**
     * Logout method. It will delete the token.
     * @return void
     */
	public function logout()
	{
		$this->delete('/auth/?token='.$this->token);
	}
	
	// ACCOUNT
	
	/**
	 * Create an new account
	 * 
	 * @param string $email
	 * @param string $password
	 * @param string $firstname
	 * @param string $lastname
	 * @param int $newsletter
	 * @return void
	 */
	public static function create_account($email, $password, $firstname, $lastname, $newsletter)
	{
		$email = rawurlencode($email);
		$password = rawurlencode($password);
		$firstname = rawurlencode($firstname);
		$lastname = rawurlencode($lastname);
		$newsletter = (int)$newsletter;
		
		self::create('/user/registration/?email='.$email.'&password='.$password.'&firstname='.$firstname.'&lastname='.$lastname.'&newsletter='.$newsletter);
	}
	
	/**
	 * Delete the account
	 * 
	 * @return void
	 */
	public function delete_account()
	{
		$this->delete('/user/profil/?token='.$this->token);
	}
	
	// PROFIL
	
	/**
	 * Gets the profil of the user (email, firstname, lastname, keos, newsletter and sites)
	 * 
	 * @return array
	 */
	public function get_profil()
	{
		return $this->get('/user/profil/?token='.$this->token);
	}
	
	/**
	 * Update the profil
	 * 
	 * Available parameters : email, firstname, lastname, newsletter (0 or 1)
	 * @param array $params
	 * @return void
	 */
	public function update_profil(array $params)
	{
		$this->update($params, '/user/profil/?token='.$this->token);
	}
	
	// SITE
	
	/**
	 * Gets site_id, title, domain, subdomain, category_id, offer and description of a site.
	 * 
	 * @param $site_id
	 * @return array
	 */
	public function get_site($site_id)
	{
		return $this->get('/user/site/'.$site_id.'?token='.$this->token);
	}
	
	/**
	 * Create a new website
	 * 
	 * @param string $subdomain
	 * @param string $domain
	 * @param string $title
	 * @param int $category
	 * @param string $description
	 * @return void
	 */
	public function create_site($subdomain, $domain, $title, $category, $description)
	{
		$subdomain = rawurlencode($subdomain);
		$domain = rawurlencode($domain);
		$title = rawurlencode($title);
		$category = (int)$category;
		$description = rawurlencode($description);
		
		self::create('/user/site/?token='.$this->token.'&subdomain='.$subdomain.'&domain='.$domain.'&title='.$title.'&category='.$category.'&description='.$description);
	}
	
	/**
	 * Update a website
	 * 
	 * Available parameters : domain, title, category (id), description
	 * @param int $site_id
	 * @param array $params
	 * @return void
	 */
	public function update_site($site_id, array $params)
	{
		$this->update($params, '/user/site/'.$site_id.'?token='.$this->token);
	}
	
	/**
	 * Delete a website
	 * 
	 * @param int $site_id
	 * @return void
	 */
	public function delete_site($site_id)
	{
		$this->delete('/user/site/'.$site_id.'?token='.$this->token);
	}
	
	// PAGE
	
	/**
	 * Gets pages of a website
	 * (id, site_id, visible, name, title, contents)
	 * 
	 * @param int $site_id
	 * @return array
	 */
	public function get_site_pages($site_id)
	{
		return $this->get('/site/pages/?token='.$this->token.'&site='.$site_id);
	}
	
	/**
	 * Gets information of a page
	 * (id, site_id, visible, name, title, contents)
	 * 
	 * @param int $page_id
	 * @return array
	 */
	public function get_page($page_id)
	{
		return $this->get('/site/pages/'.$page_id.'?token='.$this->token);
	}
	
	/**
	 * Create a new page
	 * 
	 * @param int $site_id
	 * @param string $title
	 * @param string $contents
	 * @return void
	 */
	public function create_page($site_id, $title, $contents = NULL)
	{
		$site_id = rawurlencode($site_id);
		$title = rawurlencode($title);
		
		$url = '/site/pages/?token='.$this->token.'&site='.$site_id.'&title='.$title;
		
		if(!is_null($contents))
		{
			$contents = rawurlencode($contents);
			$url = $url.'&contents='.$contents;
		}
		
		return self::create($url);
	}
	
	/**
	 * Update a page
	 * Available parameters : title, visible, contents
	 * 
	 * @param int $page_id
	 * @param array $params
	 * @return void
	 */
	public function update_page($page_id, array $params)
	{
		return $this->update($params, '/site/pages/'.$page_id.'?token='.$this->token);
	}
	
	/**
	 * Delete a page
	 * 
	 * @param int $page_id
	 * @return void
	 */
	public function delete_page($page_id)
	{
		return $this->delete('/site/pages/'.$page_id.'?token='.$this->token);
	}
	
	// BLOCKS
	
	/**
	 * Gets blocks of a website
	 * (id, site, visibility, title, h_pos, v_pos & type)
	 * 
	 * @param int $site_id
	 * @return array
	 */
	public function get_site_blocks( $site_id)
	{
		return $this->get('/site/block/?token='.$this->token.'&site='.$site_id);
	}
	
	/**
	 * Gets a block
	 * (id, site, visibility, title, h_pos, v_pos & type)
	 * + contents if CUSTOM block
	 * + module_tag & options if MODULE block
	 * 
	 * @param int $block_id
	 * @return array
	 */
	public function get_block($block_id)
	{
		return $this->get('/site/block/'.$block_id.'?token='.$this->token);
	}
	
	/**
	 * Create a new MENU block
	 * 
	 * @param int $site_id
	 * @param string $title
	 * @return void
	 */
	public function create_block_menu($site_id, $title)
	{
		$site_id = rawurlencode($site_id);
		$title = rawurlencode($title);
		
		self::create('/site/block/?token='.$this->token.'&site='.$site_id.'&title='.$title.'&type=MENU');
	}
	
	/**
	 * Create a new CONTENTS block
	 * 
	 * @param int $site_id
	 * @param string $title
	 * @param string $contents
	 * @return void
	 */
	public function create_block_contents($site_id, $title, $contents)
	{
		$site_id = rawurlencode($site_id);
		$title = rawurlencode($title);
		$contents = rawurlencode($contents);
		
		self::create('/site/block/?token='.$this->token.'&site='.$site_id.'&title='.$title.'&type=CUSTOM&contents='.$contents);
	}
	
	/**
	 * Create a new MODULE block
	 * 
	 * @param int $site_id
	 * @param string $title
	 * @param string $module_tag
	 * @return void
	 */
	public function create_block_module($site_id, $title, $module_tag)
	{
		$site_id = rawurlencode($site_id);
		$title = rawurlencode($title);
		$module_tag = rawurlencode($module_tag);
		
		self::create('/site/block/?token='.$this->token.'&site='.$site_id.'&title='.$title.'&type=MODULE&module_tag='.$module_tag);
	}
	
	/**
	 * Create a new blog MODULE block
	 * 
	 * @param int $site_id
	 * @param string $title
	 * @param string $posts_number
	 * @param string $posts_order
	 * @return void
	 */
	public function create_block_module_blog($site_id, $title, $posts_number, $posts_order)
	{
		$site_id = rawurlencode($site_id);
		$title = rawurlencode($title);
		$posts_number = rawurlencode($posts_number);
		$posts_order = rawurlencode($posts_order);
		
		self::create('/site/block/?token='.$this->token.'&site='.$site_id.'&title='.$title.'&type=MODULE&module_tag=blog&posts_number='.$posts_number.'&posts_order='.$posts_order);
	}
	
	/**
	 * Create a new topref MODULE block
	 * 
	 * @param int $site_id
	 * @param string $title
	 * @param string $links_number
	 * @return void
	 */
	public function create_block_module_topref($site_id, $title, $links_number)
	{
		$site_id = rawurlencode($site_id);
		$title = rawurlencode($title);
		$links_number = rawurlencode($links_number);
		
		self::create('/site/block/?token='.$this->token.'&site='.$site_id.'&title='.$title.'&type=MODULE&module_tag=topref&links_number='.$links_number);
	}
	
	/**
	 * Update a block
	 * Available parameters : title
	 * + contents if CUSTOM block
	 * + posts_number & posts_order if blog MODULE block
	 * + links_number if topref MODULE block
	 * 
	 * @param int $block_id
	 * @param array $params
	 * @return void
	 */
	public function update_block($block_id, array $params)
	{
		$this->update($params, '/site/block/'.$block_id.'?token='.$this->token);
	}
	
	/**
	 * Delete a block
	 * 
	 * @param int $block_id
	 * @return void
	 */
	public function delete_block($block_id)
	{
		$this->delete('/site/block/'.$block_id.'?token='.$this->token);
	}
	
	// LINKS
	
	/**
	 * Gets block's links
	 * (id, block, label, targer, type, order)
	 * 
	 * @param int $block_id
	 * @return array
	 */
	public function get_block_links($block_id)
	{
		return $this->get('/site/link/?token='.$this->token.'&block='.$block_id);
	}
	
	/**
	 * Gets a link
	 * (id, block, label, targer, type, order)
	 * 
	 * @param int $link_id
	 * @return array
	 */
	public function get_link($link_id)
	{
		return $this->get('/site/link/'.$link_id.'?token='.$this->token);
	}
	
	/**
	 * Create a link
	 * 
	 * @param int $block_id
	 * @param string $label
	 * @param string $target
	 * @param string $type
	 * @return void
	 */
	public function create_link($block_id, $label, $target, $type)
	{
		$block_id = rawurlencode($block_id);
		$label = rawurlencode($label);
		$target = rawurlencode($target);
		$type = rawurlencode($type);
		
		self::create('/site/link/?token='.$this->token.'&block='.$block_id.'&label='.$label.'&target='.$target.'&type='.$type);
	}
	
	/**
	 * Update a link
	 * Available parameters : label, target
	 * 
	 * @param int $link_id
	 * @param array $params
	 * @return void
	 */
	public function update_link($link_id, array $params)
	{
		$this->update($params, '/site/link/'.$link_id.'?token='.$this->token);
	}
	
	/**
	 * Delete a link
	 * 
	 * @param int $link_id
	 * @return void
	 */
	public function delete_link($link_id)
	{
		$this->delete('/site/link/'.$link_id.'?token='.$this->token);
	}
	
	// CONFIGURATION
	
	/**
	 * Gets available domains
	 * 
	 * @return array
	 */
	public function get_domains()
	{
		return $this->get('/config/domains/');
	}
	
	/**
	 * Gets available categories
	 * (id & name)
	 * 
	 * @return array
	 */
	public function get_categories()
	{
		return $this->get('/config/categories/');
	}
	
	// API
	
	/**
	 * Tools method to call the API with the GET method
	 * 
	 * @param string $url
	 */
	public function get($url)
	{
		$ch = curl_init(self::$domain_url.$url);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		
		$ret = curl_exec($ch);	
		curl_close($ch);
		
		$ret = json_decode($ret,true);
		
		if(is_array($ret) || !isset($this->token))
		{
			return $ret;
		}
		else
		{
			throw new Exception($ret);
		}
	}
	
	/**
	 * Tools method to call the API with the POST method
	 * 
	 * @param string $url
	 */
	public static function create($url)
	{
		$ch = curl_init(self::$domain_url.$url);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		
		$ret = curl_exec($ch);	
		curl_close($ch);
		
		if(!empty($ret))
		{
			throw new Exception($ret);
		}
	}
	
	/**
	 * Tools method to call the API with the PUT method
	 * 
	 * @param string $url
	 */
	public function update($params, $url)
	{	
		foreach ($params as $key => $value) {
	    	$url = $url.'&'.$key.'='.rawurlencode($value);
		}
		
		$ch = curl_init(self::$domain_url.$url);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_PUT, TRUE);
		
		$ret = curl_exec($ch);
		curl_close($ch);
		
		if(!empty($ret))
		{
			throw new Exception($ret);
		}
	}
	
	/**
	 * Tools method to call the API with the DELETE method
	 * 
	 * @param string $url
	 */
	public function delete($url)
	{
		$ch = curl_init(self::$domain_url.$url);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		
		$ret = curl_exec($ch);	
		curl_close($ch);
		
		if(!empty($ret))
		{
			throw new Exception($ret);
		}
	}
}