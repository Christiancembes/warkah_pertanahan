<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesPhipSertifikatRusakTransformer extends BaseTransformer
{
  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'                     => $data->id,
      'sertifikatHakAtasTanah' => $data->sertifikatHakAtasTanah,
      'fcIdentitasPemohon'     => $data->fcIdentitasPemohon,
      'suratPermohonan'        => $data->suratPermohonan,
      'archivesPhipId'         => $data->archivesPhipId,
      'createdAt'              => (new Carbon($data->createdAt))->toW3CString(),
      'updatedAt'              => (new Carbon($data->updatedAt))->toW3CString(),
    );

    return $out;
  }
}
