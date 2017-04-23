<?php

namespace HXPHP\System\Modules\Facebook;

class Data
{
	private function objToArray($obj)
	{
	    if (!is_object($obj) && !is_array($obj))
	        return $obj;

	    foreach ($obj as $key => $value)
	        $arr[$key] = $this->objToArray($value);

	    return $arr;
	}

	public function get($userData): array
	{
		if (empty($userData))
			return false;

		$formattedUserData = [];

		foreach ($userData as $key => $value) {
			$value = $this->objToArray($value);

			$value = is_array($value) ? serialize($value) : $value;

			$value = ($value === true ? 'Sim' : $value);
			$value = ($value === false ? 'Não' : $value);

			$formattedUserData[$key] = $value;
		}

		return $formattedUserData;
	}
}