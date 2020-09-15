<?php

namespace App\Models\QueryBuilder\Archives;

class ArchivesQueryBuilder extends \App\Models\QueryBuilder\BaseQueryBuilder implements ArchivesQueryBuilderInterface
{
  public function warkahIdEquals($id)
  {
    $this->queryBuilder = $this->queryBuilder->where('warkah_id', $id);

    return $this;
  }

  public function with($params)
  {
  	$this->queryBuilder = $this->queryBuilder->with($params);

  	return $this;
  }
}
