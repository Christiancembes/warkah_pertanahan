<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ArchivesLocations extends \App\Models\Model
{
	use SoftDeletes;
	use Uuids;

	protected $table   = 'archives_locations';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function archives()
	{
		return $this->hasMany('App\ArchivesLocations');
	}
}
