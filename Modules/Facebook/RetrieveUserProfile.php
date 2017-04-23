<?php
namespace HXPHP\System\Modules\Facebook;

class RetrieveUserProfile
{
	private $fb = null;
	private $errors = null;

	public function __construct($fb, Errors $errors)
	{
		$this->fb = $fb;
		$this->errors = $errors;
	}

	public function get(string $fields, string $accessToken)
	{
		try {
			$response = $this->fb->get('/me?fields=' . $fields, $accessToken);
		}
		catch(\Facebook\Exceptions\FacebookResponseException $e) {
			$this->errors->add('Erro na API: ' . $e->getMessage());
		} 
		catch(\Facebook\Exceptions\FacebookSDKException $e) {
			$this->errors->add('Erro SDK: ' . $e->getMessage());
		}

		return $this->errors->hasError() === true ? null : $response->getGraphUser();
	}
}