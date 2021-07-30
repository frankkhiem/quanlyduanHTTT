<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use stdClass;

class ResultCategoriesImport implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $status;
    public $message;
    public $description;

    public function __construct($status, $message, $rowsReaded = null, $arrayRowsFail = null)
    {
        $this->status = $status;
        $this->message = $message;
        if ( $status === 'finished' ) {
            $this->description = new stdClass();
            $this->description->totalRowsReaded = $rowsReaded;
            $this->description->arrayRowsFail = $arrayRowsFail;
        } else {
            $this->description = null;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notifications_for_admin');
    }
}