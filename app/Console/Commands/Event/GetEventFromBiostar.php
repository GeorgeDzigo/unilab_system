<?php

namespace App\Console\Commands\Event;

use App\Services\Events\Contracts\IGetBiostarEventService;
use App\Services\Events\Objects\SaveEventLogService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @property IGetBiostarEventService getBiostarEventService
 * @property SaveEventLogService saveEventLogService
 */
class GetEventFromBiostar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Biostar event and save our side.';

    /**
     * Create a new command instance.
     *
     * @param IGetBiostarEventService $getBiostarEventService
     * @param SaveEventLogService $saveEventLogService
     */
    public function __construct(
        IGetBiostarEventService $getBiostarEventService,
        SaveEventLogService $saveEventLogService
    )
    {
        parent::__construct();
        $this->getBiostarEventService = $getBiostarEventService;
        $this->saveEventLogService = $saveEventLogService;
    }

    /**
     * Execute command console.
     */
    public function handle()
    {
        DB::beginTransaction();
        $processUniqId = uniqid();

        Log::info('Start Get event from biostar and save', ['unique_id' => $processUniqId]);

        /**
         * @var $biostarData Collection
         */
        $biostarData = $this->getBiostarEventService->setEndDate(Carbon::now()->subHours(10))
                                ->setEndDate(Carbon::now())
                                    ->setEventLogsData()
                                        ->getEventLogsData();

        Log::info('Biostar event qty', ['unique_id' => $processUniqId, 'qty' => $biostarData->count()]);

        $this->saveEventLogService->setBiostarEventLogData($biostarData)
                            ->parseAndSaveData();

        Log::info('end Get event from biostar and save',['unique_id' => $processUniqId]);

        DB::commit();
    }

}
