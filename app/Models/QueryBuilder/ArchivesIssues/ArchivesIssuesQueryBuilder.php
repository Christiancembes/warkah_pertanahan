<?php

namespace App\Models\QueryBuilder\ArchivesIssues;

class ArchivesIssuesQueryBuilder extends \App\Models\QueryBuilder\BaseQueryBuilder implements ArchivesIssuesQueryBuilderInterface
{
	public function whereDateBetween($startDate, $endDate)
  	{
    	$this->queryBuilder = $this->queryBuilder->whereRaw("DATE(created_at) BETWEEN '{$startDate}' AND '{$endDate}'");

    	return $this;
  	}

    public function hasReturn()
    {
      $this->queryBuilder = $this->queryBuilder->whereNull('return_at');

      return $this;
    }

  	public function onlyReturn($startDate, $endDate)
  	{
  		$this->queryBuilder = $this->queryBuilder->whereNotNull('return_at')->whereRaw("DATE(return_at) BETWEEN '{$startDate}' AND '{$endDate}'");

  		return $this;
  	}

  	public function onlyBorrow()
  	{
  		$this->queryBuilder = $this->queryBuilder->whereNull('return_at');

  		return $this;
  	}

	public function warkahIdLike($keyword)
	{
		$this->queryBuilder = $this->queryBuilder->whereHas('archives', function($query) use ($keyword) {
      		$query->where('warkah_id', 'LIKE', '%' . $keyword . '%');
    	});

		return $this;
	}

	public function archiveIdEquals($id)
	{
		$this->queryBuilder = $this->queryBuilder->where('archive_id', $id);

		return $this;
	}

	public function checkWarkah($type)
	{
		$this->queryBuilder = $this->queryBuilder->where($type, 1);

		return $this;
	}

  	public function with($params)
  	{
  		$this->queryBuilder = $this->queryBuilder->with($params);

  		return $this;
  	}
}
