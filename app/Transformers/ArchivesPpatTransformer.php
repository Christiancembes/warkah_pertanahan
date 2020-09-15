<?php

namespace App\Transformers;

use Carbon\Carbon;

class ArchivesPpatTransformer extends BaseTransformer
{
  protected $availableKeys = ['archivesWarisan', 'archivesAktaJualBeli', 'archivesHibah'];

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

  public function includeArchivesWarisan($data)
  {
    return $this->item($data['archivesWarisan'], new ArchivesWarisanTransformer);
  }

  public function includeArchivesAktaJualBeli($data)
  {
    return $this->item($data['archivesAktaJualBeli'], new ArchivesAktaJualBeliTransformer);
  }

  public function includeArchivesHibah($data)
  {
    return $this->item($data['archivesHibah'], new ArchivesHibahTransformer);
  }
}
