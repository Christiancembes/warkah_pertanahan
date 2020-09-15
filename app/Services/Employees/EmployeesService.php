<?php

namespace App\Services\Employees;

use App\Services\BaseService;

class EmployeesService extends BaseService implements EmployeesServiceInterface
{
  public function __construct()
  {
    $this->employeesQB = \App::make('\App\Models\QueryBuilder\Employees\EmployeesQueryBuilderInterface');
  }

  public function getAll($attributes)
  {
    $limit = isset($attributes['limit']) ? $attributes['limit'] : 10;
    $page = isset($attributes['page']) ? $attributes['page'] : 1;

    $qb = $this->employeesQB;
    $total = $this->employeesQB->count();
    $results = $qb->get($limit, $page);

    $totalPage = (int) ceil($total / $limit);

    return ['results' => $results, 'total' => $total, 'totalPage' => $totalPage, 'page' => $page];
  }

  public function getById($id)
  {
    return $this->employeesQB->idEquals($id)->firstOrFail();
  }

  public function create($attributes)
  {
    return $this->atomic(function() use ($attributes) {
      return $this->employeesQB->create($attributes)->toArrayCamel();
    });
  }

  public function update($id, $attributes)
  {
    return $this->atomic(function() use ($id, $attributes) {
      $this->employeesQB->idEquals($id)->update($attributes);

      return $this->employeesQB->idEquals($id)->firstOrFail();
    });
  }

  public function delete($id)
  {
    return $this->atomic(function() use ($id) {
      return $this->employeesQB->idEquals($id)->delete($id);
    });
  }
}
