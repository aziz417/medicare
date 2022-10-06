<!DOCTYPE html>
<head>
    <title>Appointment with {{ $auth->isRole('doctor') ? $appointment->patient->name : $appointment->doctor->name }}</title>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>

@php
$forceRoute = route('common.video.call', [
    'appointment'=>$appointment->id, 
    'start_by' => $auth->role ?? 'user',
    'force_token' => md5($auth->id),
    'starter_name' => $auth->name ?? null
]);
@endphp

<body style="height: 100vh;margin: 0;">
    @if( $appointment->timeIsApeared() || $force )
    <div style="height: 100%" id="video"></div>
    @elseif($appointment->getRemainingWaitingTime('seconds') != 'expired')
    <div class="app-close" style="display: grid;place-content: center;height: 100vh; text-align: center;margin: 10px;">
        <h1 id="remaining"></h1>
        @if( $auth->id == $appointment->doctor_id )
        <a href="{{ $forceRoute }}">Force Start</a>
        @endif
    </div>
    @else
    <div class="app-close" style="display: grid;place-content: center;height: 100vh;margin: 10px;text-align: center;">
        <h1>This appointment is expired, contact with support!</h1>
        @if( $appointment->user_id != $auth->id )
        <p>If you want to start again, <a href="{{ $forceRoute }}">click here</a></p>
        @endif
        <button onclick="window.close()" style="border: 1px solid #f00;padding: 10px 20px;border-radius: 10px;font-size: 25px;cursor: pointer;color: #f00;background-color: aliceblue;width: auto;max-width: 220px;display: block;margin: 0 auto;">Close Window</button>
    </div>
    @endif

    {{-- <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script> --}}
    @if( $appointment->timeIsApeared() || $force )
    <script src='https://medcrypter.com/external_api.js'></script>
    <script type="text/javascript">
        const domain = 'medcrypter.com';
        const options = {
            roomName: '{{ config('app.name') }} Appointment - {{ str_replace('#','',$appointment->appointment_code) }}',
            width: '100%',
            height: '100%',
            configOverwrite: {
                doNotStoreRoom: true
            },
            interfaceConfigOverwrite: {
                filmStripOnly: false,
                TOOLBAR_BUTTONS: ['hangup','microphone', 'camera', 'settings', 'raisehand', 'fullscreen', 'tileview'],
                DEFAULT_BACKGROUND: '#474747',
                DEFAULT_LOCAL_DISPLAY_NAME: 'me',
                DEFAULT_LOGO_URL: 'images/watermark.png',
                DEFAULT_REMOTE_DISPLAY_NAME: 'User',
                DEFAULT_WELCOME_PAGE_LOGO_URL: 'images/watermark.png',
            },
            userInfo: {
                email: '{{ $auth->email }}',
                displayName: '{{ $auth->name }}',
                avatarUrl: '{{ asset($auth->avatar()) }}'
            },
            parentNode: document.querySelector('#video')
        };
        window.onload = ()=>{
            const api= window.api = new JitsiMeetExternalAPI(domain, options);
            // api.on('participantLeft', (data)=>console.log(data, "PARTICIPANT_LEFT"))
            api.on('readyToClose', () => {
                api.dispose();
                document.querySelector('#video').innerHTML = `<div class="app-close" style="display: grid;place-content: center;height: 100vh;"><h1>Thanks for using {{ config('app.name') }}!</h1><button onclick="window.close()" style="border: 1px solid #f00;padding: 10px 20px;border-radius: 10px;font-size: 25px;cursor: pointer;color: #f00;background-color: aliceblue;width: auto;max-width: 220px;display: block;margin: 0 auto;">Close Window</button></div>`;
            });
        }
    </script>
    @endif

    @if( $appointment->getRemainingWaitingTime('seconds') > 0 && !$force )
    <script type="text/javascript">
        var countDownDate = new Date("{{ $appointment->scheduled_at->subMinutes(5)->format('Y-m-d H:i:s') }}").getTime();
        var x = setInterval(function() {
          var now = new Date().getTime();
          var distance = countDownDate - now;
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);
          document.getElementById("remaining").innerHTML = "Start within: "+days + "d " + hours + "h "
          + minutes + "m " + seconds + "s ";
          // If the count down is finished, write some text
          if (distance <= 0) {
            clearInterval(x);
            document.getElementById("remaining").innerHTML = "Refreshing...";
            window.location.reload();
          }
        }, 1000);
    </script>
    @endif
</body>

</html>
