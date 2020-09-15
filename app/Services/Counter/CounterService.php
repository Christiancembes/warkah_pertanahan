<?php

namespace App\Services\Counter;

use App\Services\BaseService;

class CounterService extends BaseService implements CounterServiceInterface
{
  public function __construct()
  {
    $this->archivesLocationsQB = \App::make('\App\Models\QueryBuilder\ArchivesLocations\ArchivesLocationsQueryBuilderInterface');
    $this->archivesQB          = \App::make('\App\Models\QueryBuilder\Archives\ArchivesQueryBuilderInterface');
    $this->archivesIssuesQB    = \App::make('\App\Models\QueryBuilder\ArchivesIssues\ArchivesIssuesQueryBuilderInterface');
    $this->employeesQB         = \App::make('\App\Models\QueryBuilder\Employees\EmployeesQueryBuilderInterface');
  }

  public function getAll()
  {
    $totalArchivesLocation = $this->archivesLocationsQB->count();
    $totalArchives = $this->archivesQB->count();
    $totalArchivesIssues = $this->archivesIssuesQB->count();
    $totalEmployees = $this->employeesQB->count();

    $results = compact('totalArchivesLocation', 'totalArchives', 'totalArchivesIssues', 'totalEmployees');

    return $results;
  }
}
