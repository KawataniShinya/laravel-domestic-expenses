<?php

namespace App\Http\Repositories;

use App\Http\Services\CommonRepository;
use Illuminate\Support\Facades\DB;

class CommonRepositoryImpl implements CommonRepository
{

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollBack()
    {
        DB::rollBack();
    }
}
