<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesAktaJualBeliTransformer extends BaseTransformer
{
  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'                             => $data->id,
      'aktaJualBeli'                   => $data->aktaJualBeli,
      'fcBeaPerolehanHakTanahBangunan' => $data->fcBeaPerolehanHakTanahBangunan,
      'sertifikatHakTanah'             => $data->sertifikatHakTanah,
      'fcIdentitasPemohon'             => $data->fcIdentitasPemohon,
      'fcIdentitasPemilikiHak'         => $data->fcIdentitasPemilikiHak,
      'suratKuasaPermohonan'           => $data->suratKuasaPermohonan,
      'suratPermohonan'                => $data->suratPermohonan,
      'fcPajakBumiBangunan'            => $data->fcPajakBumiBangunan,
      'suratPengantarPpat'             => $data->suratPengantarPpat,
      'fcSetoranPajakPph'              => $data->fcSetoranPajakPph,
      'archivesPpatId'                 => $data->archivesPpatId,
      'createdAt'                      => (new Carbon($data->createdAt))->toW3CString(),
      'updatedAt'                      => (new Carbon($data->updatedAt))->toW3CString(),
    );

    return $out;
  }
}
