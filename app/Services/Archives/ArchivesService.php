<?php

namespace App\Services\Archives;

use App\Services\BaseService;

class ArchivesService extends BaseService implements ArchivesServiceInterface
{
  public function __construct()
  {
    $this->archivesQB                   = \App::make('\App\Models\QueryBuilder\Archives\ArchivesQueryBuilderInterface');
    $this->archivesPpatQB               = \App::make('\App\Models\QueryBuilder\ArchivesPpat\ArchivesPpatQueryBuilderInterface');
    $this->archivesWarisanQB            = \App::make('\App\Models\QueryBuilder\ArchivesWarisan\ArchivesWarisanQueryBuilderInterface');
    $this->archivesHibahQB              = \App::make('\App\Models\QueryBuilder\ArchivesHibah\ArchivesHibahQueryBuilderInterface');
    $this->archivesAkjbQB               = \App::make('\App\Models\QueryBuilder\ArchivesAktaJualBeli\ArchivesAktaJualBeliQueryBuilderInterface');
    $this->archivesPhipQB               = \App::make('\App\Models\QueryBuilder\ArchivesPhip\ArchivesPhipQueryBuilderInterface');
    $this->archivesPhipPendaftaran      = \App::make('\App\Models\QueryBuilder\ArchivesPhipPendaftaran\ArchivesPhipPendaftaranQueryBuilderInterface');
    $this->archivesPhipHgb              = \App::make('\App\Models\QueryBuilder\ArchivesPhipHgb\ArchivesPhipHgbQueryBuilderInterface');
    $this->archivesPhipPemecahan        = \App::make('\App\Models\QueryBuilder\ArchivesPhipPemecahan\ArchivesPhipPemecahanQueryBuilderInterface');
    $this->archivesPhipSertifikatHilang = \App::make('\App\Models\QueryBuilder\ArchivesPhipSertifikatHilang\ArchivesPhipSertifikatHilangQueryBuilderInterface');
    $this->archivesPhipSertifikatRusak  = \App::make('\App\Models\QueryBuilder\ArchivesPhipSertifikatRusak\ArchivesPhipSertifikatRusakQueryBuilderInterface');
  }

  public function getAll($attributes)
  {
    $limit = isset($attributes['limit']) ? $attributes['limit'] : 10;
    $page = isset($attributes['page']) ? $attributes['page'] : 1;

    $qb = $this->archivesQB;
    $total = $this->archivesQB->count();
    $results = $qb->with(['archivesLocations'])->get($limit, $page);

    $totalPage = (int) ceil($total / $limit);

    return ['results' => $results, 'total' => $total, 'totalPage' => $totalPage, 'page' => $page];
  }

  public function getById($id)
  {
    $result = $this->archivesQB->idEquals($id)->with(['archivesLocations', 'archivesPpat', 'archivesPpat.archivesWarisan', 
      'archivesPpat.archivesAktaJualBeli', 'archivesPpat.archivesHibah', 'archivesPhip', 'archivesPhip.archivesPhipHgb', 'archivesPhip.archivesPhipPemecahan', 'archivesPhip.archivesPhipPendaftaran', 'archivesPhip.archivesPhipSertifikatHilang', 'archivesPhip.archivesPhipSertifikatRusak'])->firstOrFail();

    return $result;
  }

  public function getByWarkahId($id)
  {
    $result = $this->archivesQB->warkahIdEquals($id)->with(['archivesLocations', 'archivesPpat', 'archivesPpat.archivesWarisan', 
      'archivesPpat.archivesAktaJualBeli', 'archivesPpat.archivesHibah', 'archivesPhip', 'archivesPhip.archivesPhipHgb', 'archivesPhip.archivesPhipPemecahan', 'archivesPhip.archivesPhipPendaftaran', 'archivesPhip.archivesPhipSertifikatHilang', 'archivesPhip.archivesPhipSertifikatRusak'])->firstOrFail();

    return $result;
  }

  public function create($attributes)
  {
    return $this->atomic(function() use ($attributes) {
      $archiveAttributes = $attributes;
      
      if (isset($archiveAttributes['archivesPpat']))
      {
        unset($archiveAttributes['archivesPpat']);
      }

      if (isset($archiveAttributes['archivesPhip']))
      {
        unset($archiveAttributes['archivesPhip']);
      }

      $archive = $this->archivesQB->create($archiveAttributes)->toArrayCamel();

      /*=================================
      =            Save PPAT            =
      =================================*/

      $archivePpat = $this->archivesPpatQB->create(['archiveId' => $archive['id']]);
      
      if (isset($attributes['archivesPpat']['archivesWarisan']))
      {
        $attributes['archivesPpat']['archivesWarisan'] = array_merge($attributes['archivesPpat']['archivesWarisan'], ['archivesPpatId' => $archivePpat['id']]);

        $this->archivesWarisanQB->create($attributes['archivesPpat']['archivesWarisan']);
      }
      else 
      {
        $this->archivesWarisanQB->create(['archivesPpatId' => $archivePpat['id']]);
      }
      if (isset($attributes['archivesPpat']['archivesHibah']))
      {
        $attributes['archivesPpat']['archivesHibah'] = array_merge($attributes['archivesPpat']['archivesHibah'], ['archivesPpatId' => $archivePpat['id']]);
        
        $this->archivesHibahQB->create($attributes['archivesPpat']['archivesHibah']);
      }
      else 
      {
        $this->archivesHibahQB->create(['archivesPpatId' => $archivePpat['id']]);
      }
      if (isset($attributes['archivesPpat']['archivesAktaJualBeli']))
      {
        $attributes['archivesPpat']['archivesAktaJualBeli'] = array_merge($attributes['archivesPpat']['archivesAktaJualBeli'], ['archivesPpatId' => $archivePpat['id']]);
        
        $this->archivesAkjbQB->create($attributes['archivesPpat']['archivesAktaJualBeli']);
      }
      else 
      {
        $this->archivesAkjbQB->create(['archivesPpatId' => $archivePpat['id']]);
      }
      
      /*=====  End of Save PPAT  ======*/

      /*=================================
      =            Save PHIP            =
      =================================*/
      
      $archivePhip = $this->archivesPhipQB->create(['archiveId' => $archive['id']]);

      if (isset($attributes['archivesPhip']['archivesPhipPendaftaran']))
      {
        $attributes['archivesPhip']['archivesPhipPendaftaran'] = array_merge($attributes['archivesPhip']['archivesPhipPendaftaran'], ['archivesPhipId' => $archivePhip['id']]);

        $this->archivesPhipPendaftaran->create($attributes['archivesPhip']['archivesPhipPendaftaran']);
      }
      else
      {
        $this->archivesPhipPendaftaran->create(['archivesPhipId' => $archivePhip['id']]);
      }
      if (isset($attributes['archivesPhip']['archivesPhipHgb']))
      {
        $attributes['archivesPhip']['archivesPhipHgb'] = array_merge($attributes['archivesPhip']['archivesPhipHgb'], ['archivesPhipId' => $archivePhip['id']]);
        
        $this->archivesPhipHgb->create($attributes['archivesPhip']['archivesPhipHgb']);
      }
      else
      {
        $this->archivesPhipHgb->create(['archivesPhipId' => $archivePhip['id']]);
      }
      if (isset($attributes['archivesPhip']['archivesPhipPemecahan']))
      {
        $attributes['archivesPhip']['archivesPhipPemecahan'] = array_merge($attributes['archivesPhip']['archivesPhipPemecahan'], ['archivesPhipId' => $archivePhip['id']]);
        
        $this->archivesPhipPemecahan->create($attributes['archivesPhip']['archivesPhipPemecahan']);
      }
      else
      {
        $this->archivesPhipPemecahan->create(['archivesPhipId' => $archivePhip['id']]);
      }
      if (isset($attributes['archivesPhip']['archivesPhipSertifikatHilang']))
      {
        $attributes['archivesPhip']['archivesPhipSertifikatHilang'] = array_merge($attributes['archivesPhip']['archivesPhipSertifikatHilang'], ['archivesPhipId' => $archivePhip['id']]);
        
        $this->archivesPhipSertifikatHilang->create($attributes['archivesPhip']['archivesPhipSertifikatHilang']);
      }
      else
      {
        $this->archivesPhipSertifikatHilang->create(['archivesPhipId' => $archivePhip['id']]);
      }
      if (isset($attributes['archivesPhip']['archivesPhipSertifikatRusak']))
      {
        $attributes['archivesPhip']['archivesPhipSertifikatRusak'] = array_merge($attributes['archivesPhip']['archivesPhipSertifikatRusak'], ['archivesPhipId' => $archivePhip['id']]);
        
        $this->archivesPhipSertifikatRusak->create($attributes['archivesPhip']['archivesPhipSertifikatRusak']);
      }
      else
      {
        $this->archivesPhipSertifikatRusak->create(['archivesPhipId' => $archivePhip['id']]);
      }
      
      /*=====  End of Save PHIP  ======*/

      return $this->getById($archive['id']);
    });
  }

  public function update($id, $attributes)
  {
    return $this->atomic(function() use ($id, $attributes) {
      $archiveAttributes = $attributes;
      
      if (isset($archiveAttributes['archivesPpat'])) 
      {
        unset($archiveAttributes['archivesPpat']);
      }

      if (isset($archiveAttributes['archivesPhip']))
      {
        unset($archiveAttributes['archivesPhip']);
      }

      $this->archivesQB->idEquals($id)->update($archiveAttributes);

      if (isset($attributes['archivesPpat']))
      {
        if (!isset($attributes['archivesPpat']['id'])) 
        {
          $archivePpat = $this->archivesPpatQB->create(['archiveId' => $id]);

          if (isset($attributes['archivesPpat']['archivesWarisan']))
          {
            $attributes['archivesPpat']['archivesWarisan'] = array_merge($attributes['archivesPpat']['archivesWarisan'], ['archivesPpatId' => $archivePpat['id']]);

            $this->archivesWarisanQB->create($attributes['archivesPpat']['archivesWarisan']);
          }
          if (isset($attributes['archivesPpat']['archivesHibah']))
          {
            $attributes['archivesPpat']['archivesHibah'] = array_merge($attributes['archivesPpat']['archivesHibah'], ['archivesPpatId' => $archivePpat['id']]);
            
            $this->archivesHibahQB->create($attributes['archivesPpat']['archivesHibah']);
          }
          if (isset($attributes['archivesPpat']['archivesAktaJualBeli']))
          {
            $attributes['archivesPpat']['archivesAktaJualBeli'] = array_merge($attributes['archivesPpat']['archivesAktaJualBeli'], ['archivesPpatId' => $archivePpat['id']]);
            
            $this->archivesAkjbQB->create($attributes['archivesPpat']['archivesAktaJualBeli']);
          }
        }
        else 
        {
          if (isset($attributes['archivesPpat']['archivesWarisan']))
          {
            $this->archivesWarisanQB->archivesPpatIdEquals($attributes['archivesPpat']['id'])->update($attributes['archivesPpat']['archivesWarisan']);
          }
          if (isset($attributes['archivesPpat']['archivesHibah']))
          {
            $this->archivesHibahQB->archivesPpatIdEquals($attributes['archivesPpat']['id'])->update($attributes['archivesPpat']['archivesHibah']);
          }
          if (isset($attributes['archivesPpat']['archivesAktaJualBeli']))
          {
            $this->archivesAkjbQB->archivesPpatIdEquals($attributes['archivesPpat']['id'])->update($attributes['archivesPpat']['archivesAktaJualBeli']);
          }
        }
      }


      if (isset($attributes['archivesPhip']))
      {
        if (!isset($attributes['archivesPhip']['id'])) 
        {
          $archivePhip = $this->archivesPhipQB->create(['archiveId' => $id]);

          if (isset($attributes['archivesPhip']['archivesPhipPendaftaran']))
          {
            $attributes['archivesPhip']['archivesPhipPendaftaran'] = array_merge($attributes['archivesPhip']['archivesPhipPendaftaran'], ['archivesPhipId' => $archivePhip['id']]);

            $this->archivesPhipPendaftaran->create($attributes['archivesPhip']['archivesPhipPendaftaran']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipHgb']))
          {
            $attributes['archivesPhip']['archivesPhipHgb'] = array_merge($attributes['archivesPhip']['archivesPhipHgb'], ['archivesPhipId' => $archivePhip['id']]);
            
            $this->archivesPhipHgb->create($attributes['archivesPhip']['archivesPhipHgb']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipPemecahan']))
          {
            $attributes['archivesPhip']['archivesPhipPemecahan'] = array_merge($attributes['archivesPhip']['archivesPhipPemecahan'], ['archivesPhipId' => $archivePhip['id']]);
            
            $this->archivesPhipPemecahan->create($attributes['archivesPhip']['archivesPhipPemecahan']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipSertifikatHilang']))
          {
            $attributes['archivesPhip']['archivesPhipSertifikatHilang'] = array_merge($attributes['archivesPhip']['archivesPhipSertifikatHilang'], ['archivesPhipId' => $archivePhip['id']]);
            
            $this->archivesPhipSertifikatHilang->create($attributes['archivesPhip']['archivesPhipSertifikatHilang']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipSertifikatRusak']))
          {
            $attributes['archivesPhip']['archivesPhipSertifikatRusak'] = array_merge($attributes['archivesPhip']['archivesPhipSertifikatRusak'], ['archivesPhipId' => $archivePhip['id']]);
            
            $this->archivesPhipSertifikatRusak->create($attributes['archivesPhip']['archivesPhipSertifikatRusak']);
          }
        }
        else
        {
          if (isset($attributes['archivesPhip']['archivesPhipPendaftaran']))
          {
            $attributes['archivesPhip']['archivesPhipPendaftaran'] = array_merge($attributes['archivesPhip']['archivesPhipPendaftaran']);

            $this->archivesPhipPendaftaran->archivesPhipIdEquals($attributes['archivesPhip']['id'])->update($attributes['archivesPhip']['archivesPhipPendaftaran']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipHgb']))
          {
            $attributes['archivesPhip']['archivesPhipHgb'] = array_merge($attributes['archivesPhip']['archivesPhipHgb']);
            
            $this->archivesPhipHgb->archivesPhipIdEquals($attributes['archivesPhip']['id'])->update($attributes['archivesPhip']['archivesPhipHgb']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipPemecahan']))
          {
            $attributes['archivesPhip']['archivesPhipPemecahan'] = array_merge($attributes['archivesPhip']['archivesPhipPemecahan']);
            
            $this->archivesPhipPemecahan->archivesPhipIdEquals($attributes['archivesPhip']['id'])->update($attributes['archivesPhip']['archivesPhipPemecahan']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipSertifikatHilang']))
          {
            $attributes['archivesPhip']['archivesPhipSertifikatHilang'] = array_merge($attributes['archivesPhip']['archivesPhipSertifikatHilang']);
            
            $this->archivesPhipSertifikatHilang->archivesPhipIdEquals($attributes['archivesPhip']['id'])->update($attributes['archivesPhip']['archivesPhipSertifikatHilang']);
          }
          if (isset($attributes['archivesPhip']['archivesPhipSertifikatRusak']))
          {
            $attributes['archivesPhip']['archivesPhipSertifikatRusak'] = array_merge($attributes['archivesPhip']['archivesPhipSertifikatRusak']);
            
            $this->archivesPhipSertifikatRusak->archivesPhipIdEquals($attributes['archivesPhip']['id'])->update($attributes['archivesPhip']['archivesPhipSertifikatRusak']);
          }
        }
      }

      return $this->getById($id);
    });
  }

  public function delete($id)
  {
    return $this->atomic(function() use ($id) {
      return $this->archivesQB->idEquals($id)->delete($id);
    });
  }
}
