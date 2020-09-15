<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Archives extends \App\Models\Model
{
	use SoftDeletes;
	use Uuids;

	protected $table   = 'archives';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function archivesIssues()
	{
		return $this->hasMany('App\ArchivesIssues');
	}

	public function archivesPpat()
	{
		return $this->hasOne('App\ArchivesPpat', 'archive_id');
	}

	public function archivesPhip()
	{
		return $this->hasOne('App\ArchivesPhip', 'archive_id');
	}

	public function archivesLocations()
	{
		return $this->belongsTo('App\ArchivesLocations', 'archives_location_id', 'id');
	}
}
