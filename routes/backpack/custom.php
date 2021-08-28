<?php

// --------------------------
// Custom Backpack Routes   |
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => [
        config('backpack.base.web_middleware', 'web'),
        config('backpack.base.middleware_key', 'admin'),
    ],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    /**
    * Statistics
    */
    Route::middleware([
        'permission:' . config('permissions.statistics.view.key')
    ])->group(function () {
        Route::get('statistic/{action}', 'StatisticController@showPerson');
    });


    /**
    * Competition Type
    */
    Route::middleware([
        'permission:' . config('permissions.competition_type.view.key')
    ])->group(function () {
        Route::crud('competitiontype', 'CompetitionTypeController');
    });


    /**
    * Competitions
    */
    Route::middleware([
        'permission:' . config('permissions.competition.view.key')
    ])->group( function () {
        Route::crud('competition', 'CompetitionController');
    });


    Route::middleware([
        'permission:' . config('permissions.test_attach.view.key'),
    ])->group(function () {
        Route::get('announcedcompetitions', 'AnnouncedCompetitionsController@index')->name('announcedCompetitions');
        Route::get('announcedcompetitions/{id}', 'AnnouncedCompetitionsController@announcedCompetition')->name('announcedCompetition');

        Route::get('announcedcompetitions/{id}/department/{department_id}', 'AnnouncedCompetitionsController@showTestsToAddByDepartment')->name('showTestsToAddByDepartment');
        Route::post('announcedcompetitions/{id}', 'AnnouncedCompetitionsController@attachTest')->name('attachTest');
    });

    /**
     * Person positions
     */
    Route::middleware([
        'permission:' . config('permissions.position.manage.key')
    ])->group(function () {
        Route::crud('position', 'PositionController');
    });

    /**
     * Person departments
     */
    Route::middleware([
        'permission:' . config('permissions.department.manage.key')
    ])->group(function () {
        Route::crud('department', 'DepartmentController');
    });

        /**
         * Persons
         */
        Route::middleware([
            'permission:' . config('permissions.person.manage.key')
        ])->group(function () {

            Route::crud('person', 'PersonController');

        /**
         * Get history data.
         */
        Route::get('person/{id}/history', 'HistoryLogController@history')
            ->name('person.get_history');

        /**
         * Get person event.
         */
        Route::get('person/{id}/statistic', 'EventStatisticController@logStatistic')
            ->name('person.event_statistic');

        /**
         * Get save data.
         */
        Route::post('person/get-save-data', 'PersonController@getSaveData')
            ->name('person.get_save_data');

        /**
         * Save Person data.
         */
        Route::post('person/save', 'PersonController@save')
            ->name('person.save');

        /**
         * Upload person temp image.
         */
        Route::post('person/upload-temp-image', 'PersonController@uploadTempImage')
            ->name('person.upload_temp_image');

    });

    /**
     * Item types.
     */
    Route::middleware([
        'permission:' . config('permissions.item_type.manage.key')
    ])->group(function () {
        Route::crud('item-type', 'ItemTypeController');
    });

    /**
     * Items
     */
    Route::middleware([
        'permission:' . config('permissions.item.manage.key')
    ])->group(function () {
        Route::crud('item', 'ItemController');
    });

    /**
     * Item log
     */
    Route::middleware([
        'permission:' . config('permissions.item_log.list.key')
    ])->group(function () {
        Route::crud('log-item', 'ItemLogController');
    });

    /**
     * Item log manage.
     */
    Route::prefix('log-item')
    ->middleware([
        'permission:' . config('permissions.item_log.manage.key')
    ])->group(function () {

        /**
         * Get save data.
         */
        Route::post('get-data', 'ItemLogController@getData')
            ->name('item_log.get_data');

        // Check item.
        Route::post('item-log/check-item', 'ItemLogController@checkItem')
                ->name('item_log.check_item');

        // Check person.
        Route::post('item-log/check-person', 'ItemLogController@checkPerson')
            ->name('item_log.check_person');

        // Save item log.
        Route::post('item-log/save', 'ItemLogController@saveLog')
            ->name('item_log.save');

    });

    /**
     * Event log view.
     */
    Route::middleware([
            'permission:' . config('permissions.event.view.key')
    ])->group(function () {
        Route::crud('events', 'EventController');
    });


    /**
     * Test Questions
     */
    Route::middleware([
        'permission:' . config('permissions.test_options.view.key')
    ])->group(function () {
        Route::crud('testsquestions', 'TestsQuestionController');
    });
     /**
     * Tests
     */
    Route::middleware([
        'permission:' . config('permissions.test_options.view.key')
    ])->group(function () {
        Route::crud('tests', 'TestsCrudController');
    });
    Route::middleware([
        'permission:' . config('permissions.submitted_test_access.view.key')
    ])->group(function () {

        Route::prefix('/submittedTests')->name('submittedTests.')->group(function() {

            Route::get('/', 'SubmittedTestsController@competitions')->name('competitions');
            Route::get('/competition/{competition_id}', 'SubmittedTestsController@departments')->name('competition');
            Route::get('/competition/{competition_id}/department/{department_id}', 'SubmittedTestsController@departments')->name('departments');
            Route::get('/competition/{competition_id}/department/{department_id}/tests', 'SubmittedTestsController@tests')->name('tests');
            Route::get('/competition/{competition_id}/department/{department_id}/tests/{sumitted_test_id}', 'SubmittedTestsController@test')->name('test');



        });

    });

    /**
     * Card Scanner.
     */
    Route::post('scan', 'ScannerController@scan')
            ->name('scan');

    /**
    * Template Manage.
    */
    Route::middleware([
        'permission:' . config('permissions.template.view.key')
    ])->group(function () {
        Route::get('templates', 'TemplateController@index')
            ->name('template.list');

        Route::get('templates/add', 'TemplateController@addForm')
            ->name('template.add-form');

        Route::post('templates/add', 'TemplateController@store')
            ->name('template.store');

        Route::post('templates/remove', 'TemplateController@destroy')
            ->name('template.remove');

        Route::post('templates/copy', 'TemplateController@copy')
            ->name('template.copy');

        Route::get('templates/update/{id}','TemplateController@updateForm')
            ->name('template.update');
        Route::post('templates/update/{id}','TemplateController@update')
            ->name('template.update.store');
    });

    /**
    * Email Manage.
    */
    Route::middleware([
        'permission:' . config('permissions.email_send.view.key')
    ])->group(function () {

        Route::get('mail/dashboard', 'EmailController@dashboard')
            ->name('mail.dashboard');

        Route::get('mail/send', 'EmailController@send')
            ->name('mail.send');

        Route::post('mail/send', 'EmailController@store')
            ->name('mail.store');

        Route::get('mail/group/{id}', 'EmailController@list')
            ->name('mail.list');
    });

}); // this should be the absolute last line of this file
