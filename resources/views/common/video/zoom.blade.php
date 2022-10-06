<!DOCTYPE html>
<head>
    <title>Live Call</title>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    @if( $appointment->timeIsApeared() || $force )
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.7.7/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.7.7/css/react-select.css" />
    <style type="text/css">
        body {
            padding-top: 50px;
        }

        .navbar-inverse {
            background-color: #313131;
            border-color: #404142;
        }

        .navbar-header h4 {
            margin: 0;
            padding: 15px 15px;
            color: #c4c2c2;
        }

        .navbar-right h5 {
            margin: 0;
            padding: 9px 5px;
            color: #c4c2c2;
        }

        .navbar-inverse .navbar-collapse,
        .navbar-inverse .navbar-form {
            border-color: transparent;
        }
    </style>
    @else
    <style type="text/css">
        body {
            height: 100vh;
            margin: 0;
        }
    </style>
    @endif
</head>

@php
$forceRoute = route('common.video.call', [
    'appointment'=>$appointment->id, 
    'start_by' => $auth->role ?? 'user',
    'force_token' => md5($auth->id),
    'starter_name' => $auth->name ?? null
]);
@endphp

<body>
    @if( $appointment->timeIsApeared() || $force )
        <nav id="nav-tool" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <h4><i class="fa fa-chromecast"></i> MedicsBD : Live Appointment </h4>
                </div>
                <div class="navbar-form navbar-right">
                    <h5><i class="far fa-user-circle" style=""></i> Patient Name : Test User</h5>
                </div>
            </div>
        </nav>
        <div id="zmmtg-root"></div>
        <div id="aria-notify-area"></div>
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

    @if( $appointment->timeIsApeared() || $force )
    <!-- import ZoomMtg dependencies -->
    <script src="https://source.zoom.us/1.7.7/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/1.7.7/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/1.7.7/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/1.7.7/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/1.7.7/lib/vendor/jquery.min.js"></script>
    <script src="https://source.zoom.us/1.7.7/lib/vendor/lodash.min.js"></script>
    <!-- import ZoomMtg -->
    <script src="https://source.zoom.us/zoom-meeting-1.7.7.min.js"></script>
    <script>
        @php( $zoomData = $appointment->doctor->getMeta('zoom_meeting_credentials',[]) )

        ZoomMtg.preLoadWasm();
        ZoomMtg.prepareJssdk();
        var meetConfig = {
            apiKey: "{{ $zoomData['apiKey'] ?? null }}",
            apiSecret: "{{ $zoomData['apiSecret'] ?? null }}",
            meetingNumber: {{ $zoomData['meetingNumber'] ?? 0 }},
            userName: "{{ $auth->name }}",
            passWord: "{{ $zoomData['passWord'] ?? null }}",
            leaveUrl: "{{ route('app.close') }}", // route('app.close''admin.appointments.show', $appointment->id)
            role: {{ $auth->isAdmin() ? 1 : 0  }}
        };
        var signature = ZoomMtg.generateSignature({
            meetingNumber: meetConfig.meetingNumber,
            apiKey: meetConfig.apiKey,
            apiSecret: meetConfig.apiSecret,
            role: meetConfig.role,
            success: function(res) {
                console.log(res.result);
            }
        });
        ZoomMtg.init({
            leaveUrl: meetConfig.leaveUrl,
            isSupportAV: true,
            success: function() {
                ZoomMtg.join({
                    meetingNumber: meetConfig.meetingNumber,
                    userName: meetConfig.userName,
                    signature: signature,
                    apiKey: meetConfig.apiKey,
                    passWord: meetConfig.passWord,
                    success: function(res) {
                        $('#nav-tool').hide();
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            },
            error: function(res) {
                console.log(res);
            }
        });
    </script>
    @endif

    @if( $appointment->getRemainingWaitingTime('seconds') > 0 )
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
