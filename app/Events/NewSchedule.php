<?php

namespace App\Events;

use App\Feedback;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSchedule implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $feedback;
    public $userNotification;
    public $client;

    public function __construct(Feedback $feedback)
    {
        $this->client = User::where('id',$feedback->client_id)->get();
        $this->feedback         = $feedback;
        $this->userNotification = $feedback->barbershop_id;
    }

    public function broadcastOn()
    {
        return new Channel("newFeedBack");
    }

    public function broadcastAs()
    {
        return 'SendFeedback';
    }

    public function broadcastWith()
    {
        return [
            'mensagem' => "Novo feedback de " .$this->client[0]->name
        ];
    }
}
