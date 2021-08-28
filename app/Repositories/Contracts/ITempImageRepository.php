<?php


namespace App\Repositories\Contracts;


interface ITempImageRepository extends IBaseRepository
{

    /**
     * @param $file
     * @param string $type
     * @return mixed
     */
    public function saveImage($file, $type = 'person');

    /**
     * @return mixed
     */
    public function getTempImage();

}
