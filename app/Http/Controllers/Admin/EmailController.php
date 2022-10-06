<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Template;
use App\Jobs\EmailSenderJob;
use Illuminate\Http\Request;
use App\Helpers\AppMailMessage;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function index()
    {
        $users = User::whereRole(['doctor', 'user', 'patient'])->get();
        $templates = Template::where('type', 'email')->get();
        return view('admin.sender.email', compact('users', 'templates'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'users' => 'required|array',
            'template' => 'nullable|string',
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);
        $users = User::find($request->users);
        $template = Template::getTemplate($request->template);
        $send = [];
        if( !$template ){
            $template = Template::create([
                'name' => $request->subject,

                'subject' => $request->subject,
                'content' => $request->content,
                
                'hidden' => true,
                'key' => str()->slug($request->subject) .'-'. str()->random(5),
                'type' => 'email'
            ]);
        }
        $users->each(function($user)use($request, $template){
            if( $user->email && $template ){
                EmailSenderJob::dispatch($user, $template);
            }
        });
        return back()->withSuccess("Email send successfully!");
    }
}
