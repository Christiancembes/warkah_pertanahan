<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ArchivesIssues extends \App\Models\Model
{
	use SoftDeletes;
	use Uuids;

	protected $table   = 'archives_issues';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function employees()
	{
		return $this->belongsTo('App\Employees', 'employee_id', 'id');
	}

	public function archives()
	{
		return $this->belongsTo('App\Archives', 'archive_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

	public function archivesIssuesLogs()
	{
		return $this->hasMany('App\ArchivesIssuesLogs', 'archives_issue_id');
	}
}
