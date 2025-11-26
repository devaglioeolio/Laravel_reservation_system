<?php

namespace App\Repositories;

use App\Models\Facility;

class FacilityRepository
{
    public function getAll()
    {
        return Facility::all();
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
