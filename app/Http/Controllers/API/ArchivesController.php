<?php

namespace App\Http\Controllers\API;

use App\Archives;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\ArchivesTransformer;

class ArchivesController extends Controller
{
    public function __construct()
    {
        $this->archivesService = \App::make('\App\Services\Archives\ArchivesServiceInterface');
    }

    public function index(Request $request)
    {
        $results = $this->archivesService->getAll($request->all());

        return \JsonSerializer::collection('archives', $results['results'], new ArchivesTransformer, [ 'meta' => [ 'total' => $results['total'], 'totalPage' => $results['totalPage'], 'page' => $results['page'] ] ]);
    }

    public function store(Request $request)
    {
        $result = $this->archivesService->create($request->all());

        return \JsonSerializer::item('archives', $result, new ArchivesTransformer);
    }

    public function show(Request $request, $id)
    {
        if (isset($request->all()['type']) && $request->all()['type'] == 'warkahId') {
            $result = $this->archivesService->getByWarkahId($id);
        }
        else {
            $result = $this->archivesService->getById($id);
        }

        return \JsonSerializer::item('archives', $result, new ArchivesTransformer);
    }

    public function update(Request $request, Archives $archives, $id)
    {
        $result = $this->archivesService->update($id, $request->all());

        return \JsonSerializer::item('archives', $result, new ArchivesTransformer);
    }

    public function destroy(Archives $archives, $id)
    {
        $this->archivesService->delete($id);
    }
}
