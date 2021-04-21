<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TwoFactorVerify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // @var User
    public $code, $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $code)
    {
        $this->code = $code;
        $this->user = $user;
    }
}
