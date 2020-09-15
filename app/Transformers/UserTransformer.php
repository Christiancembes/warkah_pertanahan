<?php

namespace App\Transformers;

use Carbon\Carbon;

class UserTransformer extends BaseTransformer
{
  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'        => $data->id,
      'name'      => $data->name,
      'email'     => $data->email,
      'createdAt' => (isset($data->createdAt)) ? (new Carbon($data->createdAt))->toW3CString() : (new Carbon($data->created_at))->toW3CString(),
      'updatedAt' => (isset($data->updatedAt)) ? (new Carbon($data->updatedAt))->toW3CString() : (new Carbon($data->updated_at))->toW3CString(),
    );

    return $out;
  }
}
