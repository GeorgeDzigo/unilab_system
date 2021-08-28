<?php


namespace App\Providers;


use App\Repositories\Contracts\IBaseRepository;
use App\Repositories\Contracts\IEventLogRepository;
use App\Repositories\Contracts\IItemLogRepository;
use App\Repositories\Contracts\IPeoplePositionRepository;
use App\Repositories\Contracts\IPersonRepository;
use App\Repositories\Contracts\ITempImageRepository;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\EventLogRepository;
use App\Repositories\Eloquent\ItemLogRepository;
use App\Repositories\Eloquent\PeoplePositionRepository;
use App\Repositories\Eloquent\PersonRepository;
use App\Repositories\Eloquent\TempImageRepository;
use App\Services\Events\Contracts\IChangeBiostarUserStatus;
use App\Services\Events\Contracts\IGetBiostarEventService;
use App\Services\Events\Contracts\ISaveEventLogService;
use App\Services\Events\Objects\ChangeBiostarUserStatus;
use App\Services\Events\Objects\GetBiostarEventService;
use App\Services\Events\Objects\SaveEventLogService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(ITempImageRepository::class, TempImageRepository::class);
        $this->app->bind(IPersonRepository::class, PersonRepository::class);
        $this->app->bind(IPeoplePositionRepository::class, PeoplePositionRepository::class);
        $this->app->bind(IItemLogRepository::class, ItemLogRepository::class);
        $this->app->bind(IEventLogRepository::class, EventLogRepository::class);

        //Services
        $this->app->bind(IGetBiostarEventService::class, GetBiostarEventService::class);
        $this->app->bind(ISaveEventLogService::class, SaveEventLogService::class);
        $this->app->bind(IChangeBiostarUserStatus::class, ChangeBiostarUserStatus::class);

    }


    public function boot()
    {

    }

}
