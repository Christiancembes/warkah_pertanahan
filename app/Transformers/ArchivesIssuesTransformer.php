<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesIssuesTransformer extends BaseTransformer
{
  protected $availableKeys = ['user', 'employees', 'archives', 'archivesIssuesLogs'];

  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'                   => $data->id,
      'uuid'                 => $data->uuid,
      'keperluan'            => $data->keperluan,
      'ppat'                 => $data->ppat,
      'ppatWarisan'          => $data->ppatWarisan,
      'ppatAkjb'             => $data->ppatAkjb,
      'ppatHibah'            => $data->ppatHibah,
      'phip'                 => $data->phip,
      'phipPendaftaran'      => $data->phipPendaftaran,
      'phipHgb'              => $data->phipHgb,
      'phipPemecahan'        => $data->phipPemecahan,
      'phipSertifikatHilang' => $data->phipSertifikatHilang,
      'phipSertifikatRusak'  => $data->phipSertifikatRusak,
      'returnAt'             => (isset($data->returnAt)) ? (new Carbon($data->returnAt))->toW3CString() : null,
      'createdAt'            => (isset($data->createdAt)) ? (new Carbon($data->createdAt))->toW3CString() : (new Carbon($data->created_at))->toW3CString(),
      'updatedAt'            => (isset($data->updatedAt)) ? (new Carbon($data->updatedAt))->toW3CString() : (new Carbon($data->updated_at))->toW3CString(),
    );

    return $out;
  }

  public function includeUser($data)
  {
    return $this->item($data['user'], new UserTransformer);
  }

  public function includeEmployees($data)
  {
    return $this->item($data['employees'], new EmployeesTransformer);
  }

  public function includeArchives($data)
  {
    return $this->item($data['archives'], new ArchivesTransformer);
  }

  public function includeArchivesIssuesLogs($data)
  {
    return $this->collection($data['archivesIssuesLogs'], new ArchivesIssuesLogsTransformer);
  }
}
