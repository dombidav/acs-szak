<?php

namespace App\Events\CrudEvents\Successful;

use App\Events\Event;
use App\Helpers\LogHelper;
use App\Models\ResourceModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ResourceModelDeletedEvent extends Event
{
    use InteractsWithSockets, SerializesModels;

    /** @var ResourceModel $original */
    private $original;

    /**
     * Create a new event instance.
     *
     * @param ResourceModel $original
     */
    public function __construct($original)
    {
        $this->original = $original;
        if($original->usesLog){
            LogHelper::Notify($original->getTable() . ' with id "' . $original->getKey() . '" was deleted by ' . Auth::user()->name . 'Original: /"' . $original->jsonSerialize() . '"/');
        }
    }
}
