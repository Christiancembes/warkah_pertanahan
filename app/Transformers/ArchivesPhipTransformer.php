<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesPhipTransformer extends BaseTransformer
{
  protected $availableKeys = ['archivesPhipHgb', 'archivesPhipPemecahan', 'archivesPhipPendaftaran', 'archivesPhipSertifikatHilang', 'archivesPhipSertifikatRusak'];

  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'        => $data->id,
      'archiveId' => $data->archiveId,
      'createdAt' => (new Carbon($data->createdAt))->toW3CString(),
      'updatedAt' => (new Carbon($data->updatedAt))->toW3CString(),
    );

    return $out;
  }

  public function includeArchivesPhipHgb($data)
  {
    return $this->item($data['archivesPhipHgb'], new ArchivesPhipHgbTransformer);
  }

  public function includeArchivesPhipPemecahan($data)
  {
    return $this->item($data['archivesPhipPemecahan'], new ArchivesPhipPemecahanTransformer);
  }

  public function includeArchivesPhipPendaftaran($data)
  {
    return $this->item($data['archivesPhipPendaftaran'], new ArchivesPhipPendaftaranTransformer);
  }

  public function includeArchivesPhipSertifikatHilang($data)
  {
    return $this->item($data['archivesPhipSertifikatHilang'], new ArchivesPhipSertifikatHilangTransformer);
  }

  public function includeArchivesPhipSertifikatRusak($data)
  {
    return $this->item($data['archivesPhipSertifikatRusak'], new ArchivesPhipSertifikatRusakTransformer);
  }
}
