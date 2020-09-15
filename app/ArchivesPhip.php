<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ArchivesPhip extends \App\Models\Model
{
	use SoftDeletes;

	protected $table   = 'archives_phip';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function archives()
	{
		return $this->belongsTo('App\Archives');
	}

	public function archivesPhipHgb()
	{
		return $this->hasOne('App\ArchivesPhipHgb', 'archives_phip_id');
	}

	public function archivesPhipPemecahan()
	{
		return $this->hasOne('App\ArchivesPhipPemecahan', 'archives_phip_id');
	}

	public function archivesPhipPendaftaran()
	{
		return $this->hasOne('App\ArchivesPhipPendaftaran', 'archives_phip_id');
	}

	public function archivesPhipSertifikatHilang()
	{
		return $this->hasOne('App\ArchivesPhipSertifikatHilang', 'archives_phip_id');
	}

	public function archivesPhipSertifikatRusak()
	{
		return $this->hasOne('App\ArchivesPhipSertifikatRusak', 'archives_phip_id');
	}
}
