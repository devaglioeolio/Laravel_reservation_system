<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FacilityService;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    protected $service;

    public function __construct(FacilityService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        return response()->json($this->service->getAll($page, $perPage));
    }

        public function show($id)
    {
        return response()->json($this->service->getOne($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        return response()->json($this->service->create($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->service->delete($id));
    }
}
