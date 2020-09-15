<?php

namespace App\Models\QueryBuilder\Employees;

class EmployeesQueryBuilder extends \App\Models\QueryBuilder\BaseQueryBuilder implements EmployeesQueryBuilderInterface
{
  public function nikEquals($id)
  {
    $this->queryBuilder = $this->queryBuilder->where('nik', $id);

    return $this;
  }
}
