<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ZoomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoSettingController extends Controller
{
    protected $zoom;
    public function __construct(ZoomHelper $zoom)
    {
        $this->zoom = $zoom;
    }

    public function index()
    {
        return view('admin.doctors.video-settings');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'provider' => 'required|string'
        ]);
        // jitsi | zoom
        $user->setMeta('video_call_provider', $request->provider);

        return back()->withSuccess("Updated Video Settings!");
    }

    public function updateZoomSetting(Request $request)
    {
        $request->validate([
            'apiKey' => 'required|string',
            'apiSecret' => 'required|string',
            'meetingNumber' => 'required|numeric',
            'passWord' => 'required|string',
        ]);
        $user = $request->user();
        $user->setMeta('zoom_meeting_credentials', $request->only(['apiKey', 'apiSecret', 'meetingNumber', 'passWord']));
        return back()->withInfo("Zoom meeting data updated!");
    }

    public function connect()
    {
        return redirect($this->zoom->authorize());
    }

    public function zoomCallback(Request $request)
    {
        $token = $this->zoom->callback($request->token);
        if( $token && $token['access_token'] ){
            $user = $request->user();
            $user->setMeta("zoom_details", $token);
            $user->setMeta("zoom_token", $token['access_token']);
            return route('admin.video.settings')->withInfo("Zoom API connected successfully!");
        }
        return route('admin.video.settings')->withWarning("Zoom API connecting failed!");
    }
}
