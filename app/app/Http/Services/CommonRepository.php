<?php

namespace App\Http\Services;

interface CommonRepository
{
    public function beginTransaction();
    public function commit();
    public function rollBack();
}
