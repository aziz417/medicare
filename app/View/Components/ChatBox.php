<?php

namespace App\View\Components;

use App\Models\Appointment;
use Illuminate\View\Component;

class ChatBox extends Component
{
    /**
     * Store the room ID
     * @var string | App\Models\Appointment
     */
    public $room;

    /**
     * Store the user ID
     * @var string | App\Models\User
     */
    public $user;

    /**
     * Chat Box Type
     * @var string
     */
    public $type; // popup|page

    /**
     * Appointment Link
     * @var string
     */
    public $chatPageLink;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Appointment $room, $type = 'popup')
    {
        $this->room = $room;
        $this->type = $type;
        $this->user = auth()->user();
        $this->chatPageLink = $this->getLink();

    }

    public function getLink()
    {
        if( $this->user->isAdmin() ){
            return ($this->type == 'popup') ? 
                    route('admin.appointments.action', ['appointment'=>$this->room->id, 'action'=>'chat']) :
                    route('admin.appointments.show', $this->room->id);
        }
        return ($this->type == 'popup') ? 
                route('user.appointments.action', ['appointment'=>$this->room->id, 'action'=>'chat']) :
                route('user.appointments.show', $this->room->id);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if( ! $this->room->userCanJoinRoom(auth()->id()) ){
            return null;
        }
        return view('components.chatbox');
        // Uses: <x-chat-box type="popup" :room="$appointment" />
    }
}
