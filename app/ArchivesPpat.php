<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ArchivesPpat extends \App\Models\Model
{
	use SoftDeletes;

	protected $table   = 'archives_ppat';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function archives()
	{
		return $this->belongsTo('App\Archives');
	}

	public function archivesWarisan()
	{
		return $this->hasOne('App\ArchivesWarisan', 'archives_ppat_id');
	}

	public function archivesAktaJualBeli()
	{
		return $this->hasOne('App\ArchivesAktaJualBeli', 'archives_ppat_id');
	}

	public function archivesHibah()
	{
		return $this->hasOne('App\ArchivesHibah', 'archives_ppat_id');
	}
}
