<?php

namespace App\UseCases;

use App\UseCases\Interfaces\UseCaseInterface;

abstract class BaseUseCase implements UseCaseInterface
{
    /**
     * Execute the use case
     * 
     * @param array $data Input data for the use case
     * @return mixed Result of the use case execution
     */
    abstract public function execute(array $data);
} 