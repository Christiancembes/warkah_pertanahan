<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\ArchivesLocations;
use App\Archives;
use App\ArchivesPpat;
use App\ArchivesWarisan;
use App\ArchivesAktaJualBeli;
use App\ArchivesHibah;
use App\ArchivesIssues;
use App\ArchivesPhip;
use App\ArchivesPhipPendaftaran;
use App\ArchivesPhipHgb;
use App\ArchivesPhipPemecahan;
use App\ArchivesPhipSertifikatHilang;
use App\ArchivesPhipSertifikatRusak;
use App\ArchivesIssuesLogs;
use App\Employees;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('\App\Services\ArchivesLocations\ArchivesLocationsServiceInterface', '\App\Services\ArchivesLocations\ArchivesLocationsService');
        $this->app->bind('\App\Services\Archives\ArchivesServiceInterface', '\App\Services\Archives\ArchivesService');
        $this->app->bind('\App\Services\ArchivesIssues\ArchivesIssuesServiceInterface', '\App\Services\ArchivesIssues\ArchivesIssuesService');
        $this->app->bind('\App\Services\ArchivesIssuesLogs\ArchivesIssuesLogsServiceInterface', '\App\Services\ArchivesIssuesLogs\ArchivesIssuesLogsService');
        $this->app->bind('\App\Services\Employees\EmployeesServiceInterface', '\App\Services\Employees\EmployeesService');
        $this->app->bind('\App\Services\Counter\CounterServiceInterface', '\App\Services\Counter\CounterService');

        $this->app->bind('\App\Models\QueryBuilder\ArchivesLocations\ArchivesLocationsQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesLocations\ArchivesLocationsQueryBuilder(new ArchivesLocations);
        });

        $this->app->bind('\App\Models\QueryBuilder\Archives\ArchivesQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\Archives\ArchivesQueryBuilder(new Archives);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesPpat\ArchivesPpatQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesPpat\ArchivesPpatQueryBuilder(new ArchivesPpat);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesWarisan\ArchivesWarisanQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesWarisan\ArchivesWarisanQueryBuilder(new ArchivesWarisan);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesAktaJualBeli\ArchivesAktaJualBeliQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesAktaJualBeli\ArchivesAktaJualBeliQueryBuilder(new ArchivesAktaJualBeli);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesHibah\ArchivesHibahQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesHibah\ArchivesHibahQueryBuilder(new ArchivesHibah);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesPhip\ArchivesPhipQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesPhip\ArchivesPhipQueryBuilder(new ArchivesPhip);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesPhipPendaftaran\ArchivesPhipPendaftaranQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesPhipPendaftaran\ArchivesPhipPendaftaranQueryBuilder(new ArchivesPhipPendaftaran);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesPhipHgb\ArchivesPhipHgbQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesPhipHgb\ArchivesPhipHgbQueryBuilder(new ArchivesPhipHgb);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesPhipPemecahan\ArchivesPhipPemecahanQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesPhipPemecahan\ArchivesPhipPemecahanQueryBuilder(new ArchivesPhipPemecahan);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesPhipSertifikatHilang\ArchivesPhipSertifikatHilangQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesPhipSertifikatHilang\ArchivesPhipSertifikatHilangQueryBuilder(new ArchivesPhipSertifikatHilang);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesPhipSertifikatRusak\ArchivesPhipSertifikatRusakQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesPhipSertifikatRusak\ArchivesPhipSertifikatRusakQueryBuilder(new ArchivesPhipSertifikatRusak);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesIssues\ArchivesIssuesQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesIssues\ArchivesIssuesQueryBuilder(new ArchivesIssues);
        });

        $this->app->bind('\App\Models\QueryBuilder\ArchivesIssuesLogs\ArchivesIssuesLogsQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\ArchivesIssuesLogs\ArchivesIssuesLogsQueryBuilder(new ArchivesIssuesLogs);
        });

        $this->app->bind('\App\Models\QueryBuilder\Employees\EmployeesQueryBuilderInterface', function($app) {
            return new \App\Models\QueryBuilder\Employees\EmployeesQueryBuilder(new Employees);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
