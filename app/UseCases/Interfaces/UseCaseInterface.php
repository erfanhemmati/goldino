<?php

namespace App\UseCases\Interfaces;

interface UseCaseInterface
{
    /**
     * Execute the use case
     * 
     * @param array $data Input data for the use case
     * @return mixed Result of the use case execution
     */
    public function execute(array $data);
} 