<?php
namespace HXPHP\System\Configs\Modules;

class Facebook
{
	public $app_id = null;
	public $app_secret = null;
	public $permissions = [];
	public $fields = [];

	public function setConfigs(
		string $app_id,
		string $app_secret,
		array $permissions = [],
		array $fields = []
	): self
	{
		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
		$this->permissions = $permissions;
		$this->fields = $fields;

		return $this;
	}

	public function getFields(): string
	{
		return implode(',', $this->fields);
	}
}