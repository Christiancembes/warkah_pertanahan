<?php

namespace App\Services\ArchivesIssues;

use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ArchivesIssuesService extends BaseService implements ArchivesIssuesServiceInterface
{
  public function __construct()
  {
    $this->archivesQB = \App::make('\App\Models\QueryBuilder\Archives\ArchivesQueryBuilderInterface');
    $this->archivesIssuesQB = \App::make('\App\Models\QueryBuilder\ArchivesIssues\ArchivesIssuesQueryBuilderInterface');
    $this->archivesIssuesLogsQB = \App::make('\App\Models\QueryBuilder\ArchivesIssuesLogs\ArchivesIssuesLogsQueryBuilderInterface');
    $this->employeesQB = \App::make('\App\Models\QueryBuilder\Employees\EmployeesQueryBuilderInterface');
  }

  public function getAll($attributes)
  {
    $limit = isset($attributes['limit']) ? $attributes['limit'] : 10;
    $page = isset($attributes['page']) ? $attributes['page'] : 1;
    $latest = isset($attributes['latest']) ? $attributes['latest'] : false;

    $qb = $this->archivesIssuesQB;
    $total = $this->archivesIssuesQB->count();
    $qb = $qb->with(['employees', 'archives', 'user']);
    
    if ($latest)
      $qb = $qb->latest();
    
    $results = $qb->get($limit, $page);

    $totalPage = (int) ceil($total / $limit);

    return ['results' => $results, 'total' => $total, 'totalPage' => $totalPage, 'page' => $page];
  }

  public function getById($id)
  {
    return $this->archivesIssuesQB->idEquals($id)->with(['employees', 'archives', 'user'])->firstOrFail();
  }

  public function searchByWarkahId($keyword, $attributes)
  {
    $limit = isset($attributes['limit']) ? $attributes['limit'] : 100;
    $page = isset($attributes['page']) ? $attributes['page'] : 1;
    $latest = isset($attributes['latest']) ? $attributes['latest'] : false;

    $qb = $this->archivesIssuesQB;

    $qb = $qb->warkahIdLike($keyword)->with(['employees', 'archives', 'user']);
    
    if ($latest)
      $qb = $qb->latest();
    
    $total = $qb->count();

    $results = $qb->get($limit, $page);

    $totalPage = (int) ceil($total / $limit);

    return ['results' => $results, 'total' => $total, 'totalPage' => $totalPage, 'page' => $page];
  }

  public function create($attributes)
  {
    if (!$this->archivesQB->warkahIdEquals($attributes['warkahId'])->exists())
      abort(403, 'Warkah Tidak Ditemukan');
    else {
      $attributes['archiveId'] = $this->archivesQB->warkahIdEquals($attributes['warkahId'])->firstOrFail()['id'];
      unset($attributes['warkahId']);
    }
    if (!$this->employeesQB->nikEquals($attributes['nik'])->exists())
      abort(403, 'Pegawai Tidak Ditemukan');
    else {
      $attributes['employeeId'] = $this->employeesQB->nikEquals($attributes['nik'])->firstOrFail()['id'];
      unset($attributes['nik']);
    }

    if (isset($attributes['ppatAkjb']) && $attributes['ppatAkjb'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('ppatAkjb'))->hasReturn()->exists())
        abort(403, 'Warkah PPAT Akta Jual Beli Sedang Dipinjam');
    if (isset($attributes['ppatWarisan']) && $attributes['ppatWarisan'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('ppatWarisan'))->hasReturn()->exists())
        abort(403, 'Warkah PPAT Warisan Sedang Dipinjam');
    if (isset($attributes['ppatHibah']) && $attributes['ppatHibah'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('ppatHibah'))->hasReturn()->exists())
        abort(403, 'Warkah PPAT Hibah Sedang Dipinjam');

    if (isset($attributes['phipPendaftaran']) && $attributes['phipPendaftaran'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('phipPendaftaran'))->hasReturn()->exists())
        abort(403, 'Warkah PHIP Pendaftaran Sedang Dipinjam');
    if (isset($attributes['phipHgb']) && $attributes['phipHgb'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('phipHgb'))->hasReturn()->exists())
        abort(403, 'Warkah PHIP Pendaftaran Perubahan HGB Menjadi HAK Sedang Dipinjam');
    if (isset($attributes['phipPemecahan']) && $attributes['phipPemecahan'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('phipPemecahan'))->hasReturn()->exists())
        abort(403, 'Warkah PHIP Pemecahan Sedang Dipinjam');
    if (isset($attributes['phipSertifikatHilang']) && $attributes['phipSertifikatHilang'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('phipSertifikatHilang'))->hasReturn()->exists())
        abort(403, 'Warkah PHIP Sertifikat Hilang Sedang Dipinjam');
    if (isset($attributes['phipSertifikatRusak']) && $attributes['phipSertifikatRusak'] == 1)
      if ($this->archivesIssuesQB->archiveIdEquals($attributes['archiveId'])->checkWarkah(snake_case('phipSertifikatRusak'))->hasReturn()->exists())
        abort(403, 'Warkah PHIP Sertifikat Rusak Sedang Dipinjam');

    $attributes['userId'] = Auth::user()['id'];

    return $this->atomic(function() use ($attributes) {
      $archivesIssues = $this->archivesIssuesQB->create($attributes)->toArrayCamel();

      return $this->archivesIssuesQB->idEquals($archivesIssues['id'])->with(['employees', 'archives', 'user', 'archivesIssuesLogs'])->firstOrFail();
    });
  }

  public function update($id, $attributes)
  {
    $archivesIssuesAttrs = [];

    if (isset($attributes['return']) && $attributes['return'])
    {
      $archivesIssuesAttrs = ['returnAt' => date('Y-m-d')];
    }

    return $this->atomic(function() use ($id, $attributes, $archivesIssuesAttrs) {
      $this->archivesIssuesQB->idEquals($id)->update($archivesIssuesAttrs);

      return $this->archivesIssuesQB->idEquals($id)->with(['employees', 'archives', 'user'])->firstOrFail();
    });
  }

  public function delete($id)
  {
    return $this->atomic(function() use ($id) {
      return $this->archivesIssuesQB->idEquals($id)->delete($id);
    });
  }

  public function report($attributes)
  {
    $qb = $this->archivesIssuesQB;

    if ($attributes['type'] == 'return') {
      $qb = $qb->onlyReturn($attributes['startDate'], $attributes['endDate']);
    }
    else if ($attributes['type'] == 'borrow') {
      $qb = $qb->whereDateBetween($attributes['startDate'], $attributes['endDate']);
      $qb = $qb->onlyBorrow();
    }

    $results = $qb->with(['employees', 'archives', 'user'])->get(1000, 1);

    $pdfResults = [];

    foreach ($results as $value) {
      $rowData = [
        'noWarkah' => $value['archives']['warkahId'],
        'peminjam' => $value['employees']['nama'],
        'keperluan' => $value['keperluan'],
        'provinsi' => $value['archives']['provinsi'],
        'kabupaten' => $value['archives']['kabupaten'],
        'kota' => $value['archives']['kota'],
        'kecamatan' => $value['archives']['kecamatan'],
        'alamat' => $value['archives']['alamat'],
        'tanggalPinjam' => Carbon::parse($value['createdAt'])->toDateString(),
        'tanggalKembali' => Carbon::parse($value['returnAt'])->toDateString()
      ];

      array_push($pdfResults, $rowData);
    }

    if ($attributes['export'] == 'xls' || $attributes['export'] == 'xlsx' || $attributes['export'] == 'csv') {
      \Excel::create('Laporan-Warkah', function($excel) use ($pdfResults) {

        $excel->sheet('Peminjaman-Warkah', function($sheet) use ($pdfResults) {
            $sheet->loadView('excel.report', ['pdfResults' => $pdfResults]);
        });

      })->download($attributes['export']);
    }
    else if ($attributes['export'] == 'pdf') {
      $pdf = \PDF::loadView('pdf.report', ['pdfResults' => $pdfResults]);
      
      return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->download('Laporan-Warkah.pdf');
    }
    else {
      return $results;
    }
  }
}
