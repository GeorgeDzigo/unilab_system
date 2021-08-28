<?php


namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Upload
{

    /**
     * @param UploadedFile $file
     * @param string $filePath
     * @return bool
     */
    public function imageUpload(UploadedFile $file, string $filePath)
    {
        $imageFullPath = Storage::put($filePath,$file);

        return $imageFullPath;
    }

    /**
     * @param UploadedFile $file
     * @param string $filePath
     * @return bool
     */
    public function fileUpload(UploadedFile $file, string $filePath)
    {
        $imageFullPath = Storage::put($filePath,$file);

        return $imageFullPath;
    }

}
