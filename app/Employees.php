<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Employees extends \App\Models\Model
{
	use SoftDeletes;
	use Uuids;

	protected $table   = 'employees';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function archivesIssues()
	{
		return $this->hasMany('App\ArchivesIssues');
	}
}
