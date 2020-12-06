<?php

namespace App\Events\CrudEvents\Failed;

use App\Events\Event;
use App\Helpers\LogHelper;
use App\Models\ResourceModel;
use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ResourceModelFailedToDeleteEvent extends Event
{
    use InteractsWithSockets, SerializesModels;

    /** @var ResourceModel $newModel */
    public $newModel;

    /**
     * Create a new event instance.
     *
     * @param Exception $error
     * @param ResourceModel $resourceModel
     */
    public function __construct($error, $resourceModel)
    {
        if($resourceModel->usesLog){
            LogHelper::Error($resourceModel->getTable() . ' was unsuccessfully deleted by ' . Auth::user()->name . ' error was "' . $error->getMessage() . '"');
        }
    }
}
