<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ArchivesHibah extends \App\Models\Model
{
	use SoftDeletes;

	protected $table   = 'archives_hibah';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function archivesPpat()
	{
		return $this->belongsTo('App\ArchivesPpat');
	}
}
