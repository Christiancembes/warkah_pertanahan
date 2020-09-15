<?php

namespace App\Http\Controllers\API;

use App\ArchivesLocations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\ArchivesLocationsTransformer;

class ArchivesLocationsController extends Controller
{
    public function __construct()
    {
        $this->archivesLocationsService = \App::make('\App\Services\ArchivesLocations\ArchivesLocationsServiceInterface');
    }

    public function index(Request $request)
    {
        $results = $this->archivesLocationsService->getAll($request->all());

        return \JsonSerializer::collection('archivesLocations', $results['results'], new ArchivesLocationsTransformer, [ 'meta' => [ 'total' => $results['total'], 'totalPage' => $results['totalPage'], 'page' => $results['page'] ] ]);
    }

    public function store(Request $request)
    {
        $result = $this->archivesLocationsService->create($request->all());

        return \JsonSerializer::item('archivesLocations', $result, new ArchivesLocationsTransformer);
    }

    public function show(ArchivesLocations $archivesLocations, $id)
    {
        $result = $this->archivesLocationsService->getById($id, $archivesLocations->all());

        return \JsonSerializer::item('archivesLocations', $result, new ArchivesLocationsTransformer);
    }

    public function update(Request $request, ArchivesLocations $archivesLocations, $id)
    {
        $result = $this->archivesLocationsService->update($id, $request->all());

        return \JsonSerializer::item('archivesLocations', $result, new ArchivesLocationsTransformer);
    }

    public function destroy(ArchivesLocations $archivesLocations, $id)
    {
        $this->archivesLocationsService->delete($id);
    }
}
