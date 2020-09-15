<?php

namespace App\Http\Controllers\API;

use App\Employees;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\EmployeesTransformer;

class EmployeesController extends Controller
{
    public function __construct()
    {
        $this->employeesService = \App::make('\App\Services\Employees\EmployeesServiceInterface');
    }

    public function index(Request $request)
    {
        $results = $this->employeesService->getAll($request->all());

        return \JsonSerializer::collection('employees', $results['results'], new EmployeesTransformer, [ 'meta' => [ 'total' => $results['total'], 'totalPage' => $results['totalPage'], 'page' => $results['page'] ] ]);
    }

    public function store(Request $request)
    {
        $result = $this->employeesService->create($request->all());

        return \JsonSerializer::item('employees', $result, new EmployeesTransformer);
    }

    public function show(Employees $employees, $id)
    {
        $result = $this->employeesService->getById($id, $employees->all());

        return \JsonSerializer::item('employees', $result, new EmployeesTransformer);
    }

    public function update(Request $request, Employees $employees, $id)
    {
        $result = $this->employeesService->update($id, $request->all());

        return \JsonSerializer::item('employees', $result, new EmployeesTransformer);
    }

    public function destroy(Employees $employees, $id)
    {
        $this->employeesService->delete($id);
    }
}
