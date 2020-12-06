<?php

namespace App\Events\CrudEvents\Successful;

use App\Events\Event;
use App\Helpers\LogHelper;
use App\Models\ResourceModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ResourceModelUpdatedEvent extends Event
{
    use InteractsWithSockets, SerializesModels;

    /** @var ResourceModel $newModel */
    public $newModel;
    /** @var ResourceModel $original */
    private $original;

    /**
     * Create a new event instance.
     *
     * @param ResourceModel $newModel
     * @param ResourceModel $original
     */
    public function __construct($newModel, $original)
    {
        $newModel->refresh();
        $this->newModel = $newModel;
        $this->original = $original;
        if($newModel->usesLog){
            LogHelper::Notify($newModel->getTable() . ' with id "' . $newModel->getKey() . '" was modified by ' . Auth::user()->name . 'Original: /"' . $original->jsonSerialize() . '"/');
        }
    }
}
