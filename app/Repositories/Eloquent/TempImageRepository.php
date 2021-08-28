<?php


namespace App\Repositories\Eloquent;


use App\Models\Person;
use App\Models\TempImage;
use App\Repositories\Contracts\ITempImageRepository;
use App\Traits\Upload;
use Illuminate\Database\Eloquent\Model;

class TempImageRepository extends BaseRepository implements ITempImageRepository
{

    use Upload;

    /**
     * @var TempImage
     */
    protected $localTempImage;

    /**
     * TempImageRepository constructor.
     * @param TempImage $model
     */
    public function __construct
    (
        TempImage $model
    )
    {
        parent::__construct($model);
    }

    /**
     * @return TempImage
     */
    public function getTempImage()
    {
        return $this->localTempImage;
    }

    /**
     * @param $file
     * @param string $type
     * @return mixed|void
     */
    public function saveImage($file, $type = 'person')
    {

        $tempMainPath = 'public';

        if ( $type == 'artist_images' ) {
            $tempMainPath = Person::IMAGES_MAIN_TEMP_PATH;
        }

        // Upload filed and get image full path.
        $imagePath = $this->imageUpload($file, $tempMainPath);

        // Save temp image url in db.
        $this->saveTempInDb($imagePath, $type);

    }

    /**
     * @param $imageName
     * @param $type
     * @return mixed
     */
    public function saveTempInDb($imageName, $type)
    {
        $this->localTempImage =  $this->model->create([
            'src'           => $imageName,
            'type'          => $type
        ]);
    }

}
