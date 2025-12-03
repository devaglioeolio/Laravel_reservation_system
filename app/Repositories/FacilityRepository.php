<?php

namespace App\Repositories;

use App\Models\Facility;

class FacilityRepository
{
    public function getAll($page = 1, $perPage = 10)
    {
        return Facility::orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findById($id)
    {
        return Facility::findOrFail($id);
    }

    public function create(array $data)
    {
        return Facility::create($data);
    }

    public function update($id, array $data)
    {
        $facility = $this->findById($id);
        $facility->update($data);
        return $facility;
    }

    public function delete($id)
    {
        return Facility::destroy($id);
    }
}
