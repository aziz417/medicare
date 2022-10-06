<div 
    id="chat-box" 
    class="user-{{ $user->role }}" 
    data-type="{{ $type == 'page' ? 'page' : 'popup' }}"
    data-user="{{ json_encode($user->getPublicData()) }}" 
    data-room="{{ json_encode($room->getPublicData()) }}"
    data-link="{{ $chatPageLink }}"
></div>