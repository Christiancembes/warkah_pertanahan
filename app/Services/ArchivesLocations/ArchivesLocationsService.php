<?php

namespace App\Services\ArchivesLocations;

use App\Services\BaseService;

class ArchivesLocationsService extends BaseService implements ArchivesLocationsServiceInterface
{
  public function __construct()
  {
    $this->archivesLocationsQB = \App::make('\App\Models\QueryBuilder\ArchivesLocations\ArchivesLocationsQueryBuilderInterface');
  }

  public function getAll($attributes)
  {
    $limit = isset($attributes['limit']) ? $attributes['limit'] : 10;
    $page = isset($attributes['page']) ? $attributes['page'] : 1;

    $qb = $this->archivesLocationsQB;
    $total = $this->archivesLocationsQB->count();
    $results = $qb->get($limit, $page);

    $totalPage = (int) ceil($total / $limit);

    return ['results' => $results, 'total' => $total, 'totalPage' => $totalPage, 'page' => $page];
  }

  public function getById($id)
  {
    return $this->archivesLocationsQB->idEquals($id)->firstOrFail();
  }

  public function create($attributes)
  {
    return $this->atomic(function() use ($attributes) {
      return $this->archivesLocationsQB->create($attributes)->toArrayCamel();
    });
  }

  public function update($id, $attributes)
  {
    return $this->atomic(function() use ($id, $attributes) {
      $this->archivesLocationsQB->idEquals($id)->update($attributes);

      return $this->archivesLocationsQB->idEquals($id)->firstOrFail();
    });
  }

  public function delete($id)
  {
    return $this->atomic(function() use ($id) {
      return $this->archivesLocationsQB->idEquals($id)->delete($id);
    });
  }
}
