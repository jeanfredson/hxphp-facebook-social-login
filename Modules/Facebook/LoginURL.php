<?php

namespace HXPHP\System\Modules\Facebook;

class LoginURL
{
	private $helper = null;
	private $permissions = null;

	public function __construct($helper, array $permissions)
	{
		$this->helper = $helper;
		$this->permissions = $permissions;
	}

	public function get(string $url)
	{
		return $this->helper->getLoginUrl($url, $this->permissions);
	}
}