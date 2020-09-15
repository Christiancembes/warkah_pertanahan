<?php

namespace App\Http\Controllers\API;

use App\ArchivesIssues;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\ArchivesIssuesTransformer;

class ArchivesIssuesController extends Controller
{
    public function __construct()
    {
        $this->archivesIssuesService = \App::make('\App\Services\ArchivesIssues\ArchivesIssuesServiceInterface');
    }

    public function index(Request $request)
    {
        $results = $this->archivesIssuesService->getAll($request->all());

        return \JsonSerializer::collection('archivesIssues', $results['results'], new ArchivesIssuesTransformer, [ 'meta' => [ 'total' => $results['total'], 'totalPage' => $results['totalPage'], 'page' => $results['page'] ] ]);
    }

    public function store(Request $request)
    {
        $result = $this->archivesIssuesService->create($request->all());

        return \JsonSerializer::item('archivesIssues', $result, new ArchivesIssuesTransformer);
    }

    public function show(ArchivesIssues $archivesIssues, $id)
    {
        $result = $this->archivesIssuesService->getById($id, $archivesIssues->all());

        return \JsonSerializer::item('archivesIssues', $result, new ArchivesIssuesTransformer);
    }

    public function update(Request $request, ArchivesIssues $archivesIssues, $id)
    {
        $result = $this->archivesIssuesService->update($id, $request->all());

        return \JsonSerializer::item('archivesIssues', $result, new ArchivesIssuesTransformer);
    }

    public function destroy(ArchivesIssues $archivesIssues, $id)
    {
        $this->archivesIssuesService->delete($id);
    }

    public function search(Request $request, $keyword)
    {
        $results = $this->archivesIssuesService->searchByWarkahId($keyword, $request->all());

        return \JsonSerializer::collection('archivesIssues', $results['results'], new ArchivesIssuesTransformer, [ 'meta' => [ 'total' => $results['total'], 'totalPage' => $results['totalPage'], 'page' => $results['page'] ] ]);
    }

    public function report(Request $request)
    {
        $results = $this->archivesIssuesService->report($request->all());

        if ($request->all()['export'] == 'none')
            return \JsonSerializer::collection('archivesIssues', $results, new ArchivesIssuesTransformer);
        else
            return $results;
    }
}
