<?php

namespace App\Events\CrudEvents\Successful;

use App\Events\Event;
use App\Helpers\LogHelper;
use App\Models\ResourceModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ResourceModelCreatedEvent extends Event
{
    use InteractsWithSockets, SerializesModels;

    /** @var ResourceModel $newModel */
    public $newModel;

    /**
     * Create a new event instance.
     *
     * @param ResourceModel $newModel
     */
    public function __construct($newModel)
    {
        $newModel->refresh();
        $this->newModel = $newModel;
        if($newModel->usesLog){
            LogHelper::Notify('New ' . $newModel->getTable() . ' was created by ' . Auth::user()->name . ' with id "' . $newModel->getKey() . '"');
        }
    }
}
