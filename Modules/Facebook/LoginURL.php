<?php

namespace HXPHP\System\Modules\Facebook;

class LoginURL
{
	private $helper = null;
	private $permissions = null;

	public function __construct($helper, $permissions)
	{
		$this->helper = $helper;
		$this->permissions = $permissions;
	}

	public function get($url)
	{
		return $this->helper->getLoginUrl($url, $this->permissions);
	}
}