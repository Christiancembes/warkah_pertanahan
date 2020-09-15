<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class ArchivesIssuesLogs extends \App\Models\Model
{
	use SoftDeletes;

	protected $table   = 'archives_issues_logs';
	protected $guarded = array('id');
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

	public function archivesIssues()
	{
		return $this->belongsTo('App\ArchivesIssues', 'archives_issue_id', 'id');
	}
}
