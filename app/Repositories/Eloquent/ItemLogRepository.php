<?php


namespace App\Repositories\Eloquent;


use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Person;
use App\Repositories\Contracts\IBaseRepository;
use App\Repositories\Contracts\IItemLogRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemLogRepository extends BaseRepository implements IItemLogRepository
{

    /**
     * ItemLogRepository constructor.
     * @param ItemLog $model
     */
    public function __construct
    (
        ItemLog $model
    )
    {
        parent::__construct($model);
    }

    /**
     * @param $request
     * @throws \Exception
     */
    public function saveLog($request)
    {

        try {

            DB::beginTransaction();

            /**
             * @var $person Person
             */
            $person = Person::where('personal_number',$request->get('person_id'))->first();

            if (is_null($person)) {
                throw new \Exception('Person not found', 500);
            }

            if ( !$person->status ) {
                throw new \Exception('აღნიშნული პირი არ არის აქტიური!', 500);
            }

            /**
             * @var $item Item
             */
            $item = Item::find($request->get('item_id'));

            if (is_null($item)) {
                throw new \Exception('მოწყობილობა არ მოიძებნა', 500);
            }

            if ( !$item->status ) {
                throw new \Exception('მოწყობილობა არ არის აქტიური!', 500);
            }

            /**
             * @var $itemLog ItemLog
             */
            $itemLog = ItemLog::create([
                'person_id' => $person->id,
                'item_id'   => $item->id,
                'action'    => $request->get('action'),
                'user_id'   => backpack_user()->id
            ]);

            $itemLog->personPositions()->sync($person->activePositions->pluck('id')->toArray());
            $itemLog->save();

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw new \Exception($ex->getMessage(), 500);
        }

    }

}
