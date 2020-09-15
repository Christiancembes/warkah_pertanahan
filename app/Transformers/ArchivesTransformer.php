<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesTransformer extends BaseTransformer
{
  protected $availableKeys = ['archivesLocations', 'archivesPpat', 'archivesPhip'];

  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'        => $data->id,
      'uuid'      => $data->uuid,
      'warkahId'  => $data->warkahId,
      'nama'      => $data->nama,
      'provinsi'  => $data->provinsi,
      'kabupaten' => $data->kabupaten,
      'kota'      => $data->kota,
      'kecamatan' => $data->kecamatan,
      'kelurahan' => $data->kelurahan,
      'alamat'    => $data->alamat,
      'createdAt' => (new Carbon($data->createdAt))->toW3CString(),
      'updatedAt' => (new Carbon($data->updatedAt))->toW3CString(),
    );

    return $out;
  }

  public function includeArchivesLocations($data)
  {
    return $this->item($data['archivesLocations'], new ArchivesLocationsTransformer);
  }

  public function includeArchivesPpat($data)
  {
    return $this->item($data['archivesPpat'], new ArchivesPpatTransformer);
  }

  public function includeArchivesPhip($data)
  {
    return $this->item($data['archivesPhip'], new ArchivesPhipTransformer);
  }
}
