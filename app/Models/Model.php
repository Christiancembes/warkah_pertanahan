<?php

namespace App\Models;

use Illuminate\Support\Str as Str;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
	public function newCollection(array $models = array())
  {
    return new Collection($models);
  }

	public function toArrayCamel()
  {
    $item = $this->toArray();
    $dates = $this->getDates();

    return $this->camelCaseAttributes($item, array(), $dates);
  }

  protected function camelCaseAttributes($array = array(), $arrayHolder = array(), $dates = null)
  {
    $camelArray = !empty($arrayHolder) ? $arrayHolder : array();
    $array = !empty($array) ? $array : array();
    foreach ($array as $key => $val) {
      $newKey = Str::camel($key);
      if (!is_array($val)) {
        $camelArray[$newKey] = $val;
      } else {
        $camelArray[$newKey] = array();
        $camelArray[$newKey] = $this->camelCaseAttributes($val, $camelArray[$newKey]);
      }
    }

    return $camelArray;
  }
}