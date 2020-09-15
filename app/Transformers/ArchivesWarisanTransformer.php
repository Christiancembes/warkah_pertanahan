<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesWarisanTransformer extends BaseTransformer
{
  public function serialize($data) {
    $data = (object)$data;

    $out = array(
      'id'                             => $data->id,
      'aktaPembangunanHartaWarisan'    => $data->aktaPembangunanHartaWarisan,
      'fcBeaPerolehanHakTanahBangunan' => $data->fcBeaPerolehanHakTanahBangunan,
      'sertifikatHakTanah'             => $data->sertifikatHakTanah,
      'fcIdentitasPemohon'             => $data->fcIdentitasPemohon,
      'fcIdentitasPemilikiHak'         => $data->fcIdentitasPemilikiHak,
      'suratKeteranganKematian'        => $data->suratKeteranganKematian,
      'suratKeteranganWarisan'         => $data->suratKeteranganWarisan,
      'suratKuasaPermohonan'           => $data->suratKuasaPermohonan,
      'suratPermohonan'                => $data->suratPermohonan,
      'fcPajakBumi'                    => $data->fcPajakBumi,
      'archivesPpatId'                 => $data->archivesPpatId,
      'createdAt'                      => (new Carbon($data->createdAt))->toW3CString(),
      'updatedAt'                      => (new Carbon($data->updatedAt))->toW3CString(),
    );

    return $out;
  }
}
