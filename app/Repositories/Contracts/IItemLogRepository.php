<?php


namespace App\Repositories\Contracts;


interface IItemLogRepository extends IBaseRepository
{


    /**
     * @param $request
     * @return mixed
     */
    public function saveLog($request);

}
