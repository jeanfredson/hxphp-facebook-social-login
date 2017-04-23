<?php

namespace HXPHP\System\Modules\Facebook;

class Facebook
{
	/**
	 * Dependências
	 * @var object
	 */
	public $fb = null;
	private $configs = null;
	private $helper = null;
	public $errors = null;
	public $loginUrl = null;

	/**
	 * Dados do usuário
	 * @var mixed (null/array)
	 */
	private $userData = null;

	public function __construct(\HXPHP\System\Configs\Modules\Facebook $configs)
	{
		$this->fb = new \Facebook\Facebook([
			'app_id' => $configs->app_id,
			'app_secret' => $configs->app_secret,
			'default_graph_version' => 'v2.8',
		]);

		$this->configs = $configs;
		$this->helper = $this->fb->getRedirectLoginHelper();

		$this->errors = new Errors('facebook_social_login');

		$this->loginUrl = new LoginURL($this->helper, $configs->permissions);
	}

	public function getUserData($extended = false)
	{
		$fields = $extended === false ? 'id,email' : $this->configs->getFields();

		$accessToken = new AccessToken($this->helper, $this->errors);
		$retrieveUserProfile = new RetrieveUserProfile($this->fb, $this->errors);

		$accessToken = $accessToken->get();

		if (is_null($accessToken))
			return null;

		$userData = $retrieveUserProfile->get(
			$fields,
			$accessToken
		);

		if (is_null($userData))
			return null;

		$data = new Data;
		
		return $data->get($userData);
	}
}