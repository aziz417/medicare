<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Notifications extends Component
{
    /**
     * Store the user ID
     * @var string | App\Models\User
     */
    public $user;

    /**
     * Store the unread notifications count
     * @var number
     */
    public $unread;

    /**
     * Store the user notifications
     * @var array
     */
    public $notifications;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($limit = 20)
    {
        $notifications = auth()->user()->notifications;
        $this->user = json_encode(auth()->user()->getPublicData());
        $this->notifications = $notifications->take($limit);
        $this->unread = $notifications->filter(function($item){ return empty($item->read_at); })->count();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return <<<'blade'
        <div 
            id="notifications" 
            data-user="{{ $user }}"
            data-items="{{ $notifications }}" 
            data-unread="{{ $unread }}"
        ></div>
        blade;
    }
}
