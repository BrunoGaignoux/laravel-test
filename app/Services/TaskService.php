<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class TaskService
{
    /*
     * @var TaskRepository $repository
     */
    protected TaskRepository $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->repository->tasks();
    }

    /**
     * @throws \ErrorException
     */
    public function create(array $data): Task
    {
        $validator = Validator::make($data, [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException();
        }

        try {
            $this->repository->startTransaction();
            $entity = $this->repository->store($data);
            $this->repository->commitTransaction();
            return $entity;
        } catch (\ErrorException $errorException) {
            $this->repository->rollbackTransaction();
            throw new \ErrorException($errorException->getMessage());
        }
    }

    /**
     * @throws \ErrorException
     */
    public function delete(int $id): bool
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException();
        }

        $model = $this->repository->find($id);

        if (empty($model)) {
            throw new NotFoundResourceException();
        }

        try {
            $this->repository->startTransaction();
            $deleted = $this->repository->delete($id);
            $this->repository->commitTransaction();
            return $deleted;
        } catch (\ErrorException $errorException) {
            $this->repository->rollbackTransaction();
            throw new \ErrorException($errorException->getMessage());
        }
    }

    /**
     * @throws \ErrorException
     */
    public function changeStatus(int $id, $status): Task
    {
        $validator = Validator::make([ 'id' => $id, 'status' => $status ], [
            'id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException();
        }

        $model = $this->repository->find($id);

        if (empty($model)) {
            throw new NotFoundResourceException();
        }

        try {
            $this->repository->startTransaction();
            $this->repository->update($model, ['completed' => $status]);
            $this->repository->commitTransaction();
            return $model;
        } catch (\ErrorException $errorException) {
            $this->repository->rollbackTransaction();
            throw new \ErrorException($errorException->getMessage());
        }
    }
}
