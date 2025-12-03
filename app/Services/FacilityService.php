<?php

namespace App\Services;

use App\Repositories\FacilityRepository;

class FacilityService
{
    protected $repo;

    public function __construct(FacilityRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll($page = 1, $perPage = 10)
    {
        return $this->repo->getAll($page, $perPage);
    }

    public function getOne($id)
    {
        return $this->repo->findById($id);
    }

    public function create(array $data)
    {
        // 예: 시설 이름 중복 검사, 유효성 추가 가능
        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
