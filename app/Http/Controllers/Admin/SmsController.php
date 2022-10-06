<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Template;
use App\Jobs\SmsSenderJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    public function index()
    {
        $users = User::whereRole(['doctor', 'user', 'patient'])->get();
        $templates = Template::where('type', 'sms')->get();
        return view('admin.sender.sms', compact('users', 'templates'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'users' => 'required|array',
            'template' => 'nullable|string',
            'content' => 'required|string',
        ]);
        $users = User::find($request->users);
        $template = Template::getTemplate($request->template) ?? new Template();
        $send = [];
        $users->each(function($user)use($request, $template){
            $content = $template->compileCustomContent($request->content, [], $user);
            if( $user->mobile && $content ){
                SmsSenderJob::dispatch($user, $content);//->onConnection('redis');
            }
        });
        
        return back()->withSuccess("SMS send successfully!");
    }
}
