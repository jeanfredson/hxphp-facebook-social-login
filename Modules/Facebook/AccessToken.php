<?php

namespace HXPHP\System\Modules\Facebook;

class AccessToken
{
	private $helper = null;
	private $errors = null;

	public function __construct($helper, Errors $errors)
	{
		$this->helper = $helper;
		$this->errors = $errors;
	}

	public function get()
	{
		try {
			$accessToken = $this->helper->getAccessToken();
		} 
		catch(\Facebook\Exceptions\FacebookResponseException $e) {
			$this->errors->add('Erro na API: ' . $e->getMessage());
		} 
		catch(\Facebook\Exceptions\FacebookSDKException $e) {
			$this->errors->add('Erro SDK: ' . $e->getMessage());
		}

		if (! isset($accessToken)) {
			if ($this->helper->getError()) {
				$msg = "Erro: " . $helper->getError() . "\n";
				$msg.= "Cód.: " . $helper->getErrorCode() . "\n";
				$msg.= "Motivo: " . $helper->getErrorReason() . "\n";
				$msg.= "Detalhes: " . $helper->getErrorDescription() . "\n";

				$this->errors->add($msg);
			}
		}

		return $this->errors->hasError() === true ? null : $accessToken->getValue();
	}
}