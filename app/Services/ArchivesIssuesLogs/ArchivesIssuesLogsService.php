<?php

namespace App\Services\ArchivesIssuesLogs;

use App\Services\BaseService;

class ArchivesIssuesLogsService extends BaseService implements ArchivesIssuesLogsServiceInterface
{
  public function __construct()
  {
    $this->archivesIssuesLogsQB = \App::make('\App\Models\QueryBuilder\ArchivesIssuesLogs\ArchivesIssuesLogsQueryBuilderInterface');
  }

  public function getAll($attributes)
  {
    $limit = isset($attributes['limit']) ? $attributes['limit'] : 10;
    $page = isset($attributes['page']) ? $attributes['page'] : 1;

    $qb = $this->archivesIssuesLogsQB;
    $total = $this->archivesIssuesLogsQB->count();
    $results = $qb->get($limit, $page);

    $totalPage = (int) ceil($total / $limit);

    return ['results' => $results, 'total' => $total, 'totalPage' => $totalPage, 'page' => $page];
  }

  public function getById($id)
  {
    return $this->archivesIssuesLogsQB->idEquals($id)->firstOrFail();
  }

  public function create($attributes)
  {
    return $this->atomic(function() use ($attributes) {
      return $this->archivesIssuesLogsQB->create($attributes)->toArrayCamel();
    });
  }

  public function update($id, $attributes)
  {
    return $this->atomic(function() use ($id, $attributes) {
      $this->archivesIssuesLogsQB->idEquals($id)->update($attributes);

      return $this->archivesIssuesLogsQB->idEquals($id)->firstOrFail();
    });
  }

  public function delete($id)
  {
    return $this->atomic(function() use ($id) {
      return $this->archivesIssuesLogsQB->idEquals($id)->delete($id);
    });
  }
}
