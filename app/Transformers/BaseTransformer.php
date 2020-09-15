<?php

namespace App\Transformers;

use League\Fractal;

abstract class BaseTransformer extends Fractal\TransformerAbstract {

  protected $defaultIncludes = [];
  protected $availableKeys = [];

  protected function getExcerpt($str, $startPos=0, $maxLength=100) {
    $str = strip_tags($str);
    if(strlen($str) > $maxLength) {
      $excerpt   = substr($str, $startPos, $maxLength-3);
      $lastSpace = strrpos($excerpt, ' ');
      $excerpt   = substr($excerpt, 0, $lastSpace);
      $excerpt  .= '...';
    } else {
      $excerpt = $str;
    }

    return $excerpt;
  }

  protected function filterMissingKeys(array $data) {
    $availableKeys = [];
    foreach($this->availableKeys as $key => $value) {
      if (isset($data[$value])) $availableKeys[] = $this->availableKeys[$key];
    }
    return $availableKeys;
  }

  public function transform($data) {
    $data = (array) $data;
    $this->defaultIncludes = $this->filterMissingKeys($data);
    return $this->serialize($data);
  }

  abstract public function serialize($data);

}