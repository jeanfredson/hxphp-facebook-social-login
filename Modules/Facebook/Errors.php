<?php

namespace HXPHP\System\Modules\Facebook;
use HXPHP\System\Storage\Session\Session as Session;

class Errors
{
	/**
	 * Storage
	 * @var object
	 */
	private $session;

	/**
	 * Referência para sessão
	 * @var string
	 */
	private $session_name;

	public function __construct(string $session_name)
	{
		$this->session = new Session;
		$this->session_name = $session_name;

		$this->session->clear($session_name);
	}

	public function add(string $msg)
	{
		$errors = [$msg];

		if ($this->session->exists($this->session_name)) {
			$errors = (array) $this->session->get($this->session_name);
			$errors[] = $msg;
		}

		return $this->session->set($this->session_name, $errors);
	}

	public function hasError(): bool
	{
		if (!$this->session->exists($this->session_name))
			return false;

		$errors = $this->session->get($this->session_name);

		if (is_array($errors) && !empty($errors))
			return true;

		return false;
	}

	public function getErrors()
	{
		if (!$this->hasError())
			return false;

		return [
			'danger',
			'Oops! Não foi possível concluir a requisição. Por favor, verifique os erros abaixo:',
			$this->session->get($this->session_name)
		];
	}
}