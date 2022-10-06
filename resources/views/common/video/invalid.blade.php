<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denied!</title>
</head>
@php($forceRoute = route('common.video.call', ['appointment'=>$appointment->id, 'start_by' => $auth->role ?? 'user', 'force_token' => md5($auth->id), 'starter_name' => $auth->name ?? null
]) )
<body>
    <div class="app-close" style="display: grid;place-content: center;height: 100vh; text-align: center;">
        @if( !$appointment->isCompleted() )
        <h1>You are not supposed to be here!</h1>
        @else
        <h1>This appointment is already completed</h1>
        @if( $appointment->user_id != $auth->id )
        <p>If you want to start again, <a href="{{ $forceRoute }}">click here</a></p>
        @endif
        
        @endif
        <button onclick="window.close()" style="border: 1px solid #f00;padding: 10px 20px;border-radius: 10px;font-size: 25px;cursor: pointer;color: #f00;background-color: aliceblue;width: auto;max-width: 220px;display: block;margin: 0 auto;">Close Window</button>
    </div>
</body>
</html>