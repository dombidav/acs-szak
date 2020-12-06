<?php

namespace App\Providers;

use App\Events\CrudEvents\Failed\ResourceModelFailedToCreateEvent;
use App\Events\CrudEvents\Failed\ResourceModelFailedToDeleteEvent;
use App\Events\CrudEvents\Failed\ResourceModelFailedToUpdateEvent;
use App\Events\CrudEvents\Successful\ResourceModelCreatedEvent;
use App\Events\CrudEvents\Successful\ResourceModelDeletedEvent;
use App\Events\CrudEvents\Successful\ResourceModelUpdatedEvent;
use App\Listeners\ExampleListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /** CRUD EVENTS */
        ResourceModelCreatedEvent::class => [],
        ResourceModelUpdatedEvent::class => [],
        ResourceModelDeletedEvent::class => [],

        ResourceModelFailedToCreateEvent::class => [],
        ResourceModelFailedToUpdateEvent::class => [],
        ResourceModelFailedToDeleteEvent::class => [],
        /** END OF CRUD EVENTS */
    ];
}
