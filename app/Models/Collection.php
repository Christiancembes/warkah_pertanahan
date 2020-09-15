<?php

namespace App\Models;

use Illuminate\Support\Str as Str;

class Collection extends \Illuminate\Database\Eloquent\Collection
{
  public function toArrayCamel()
  {
    $item = $this->toArray();

    return $this->camelCaseAttributes($item, array());
  }

  protected function camelCaseAttributes($array = array(), $arrayHolder = array())
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