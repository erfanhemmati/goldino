<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->query()->where('email', $email)->first();
    }

    /**
     * @inheritDoc
     */
    public function findById(int $userId): ?User
    {
        return $this->model->query()->where('id', $userId)->first();
    }
}
