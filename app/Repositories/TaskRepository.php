<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;

class TaskRepository extends BaseRepository
{
    /*
     * @var Task $model
     */
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function tasks(): array
    {
        return $this->model->all()->toArray();
    }
}
