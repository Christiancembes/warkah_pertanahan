<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesHibahTransformer extends BaseTransformer
{
  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'                             => $data->id,
      'aktaHibah'                      => $data->aktaHibah,
      'fcBeaPerolehanHakTanahBangunan' => $data->fcBeaPerolehanHakTanahBangunan,
      'fcIdentitasPemohon'             => $data->fcIdentitasPemohon,
      'sertifikatHakTanah'             => $data->sertifikatHakTanah,
      'fcIdentitasPemilikiHak'         => $data->fcIdentitasPemilikiHak,
      'suratKuasaPermohonan'           => $data->suratKuasaPermohonan,
      'suratPermohonan'                => $data->suratPermohonan,
      'fcPbb'                          => $data->fcPbb,
      'suratPengantarPpat'             => $data->suratPengantarPpat,
      'archivesPpatId'                 => $data->archivesPpatId,
      'createdAt'                      => (new Carbon($data->createdAt))->toW3CString(),
      'updatedAt'                      => (new Carbon($data->updatedAt))->toW3CString(),
    );

    return $out;
  }
}
