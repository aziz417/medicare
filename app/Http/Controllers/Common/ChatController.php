<?php

namespace App\Http\Controllers\Common;

use App\Models\Asset;
use App\Models\Message;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessages;
use App\Events\Broadcast\MessageSent;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('chat');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Appointment $room)
    {
        $perpage = 50;
        $user = $request->user();
        $messages = $room->messages()
            ->orderBy('created_at', 'DESC')
            ->paginate($perpage);
        return ChatMessages::collection($messages->reverse())->additional([
            'status' => true,
            'previous' => $messages->appends($request->all())->nextPageUrl() ?? false,
            'perpage' => $perpage
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Appointment $room)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        $message = $room->messages()->create([
            'message' => $request->input('message'),
            'user_id' => auth()->id()
        ]);

        broadcast(new MessageSent($message->user, $message))->toOthers();

        if( $message ){
            return (new ChatMessages($message))->additional(['status' => true, 'message' => 'Message Sent']);
        }
        return response()->json([
            'status' => false, 
            'message' => 'Message Not Sent', 
        ]);
    }

    public function upload(Request $request, Appointment $room)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpeg,jpg,png,pdf,txt'
        ]);
        $file = $request->file('document');
        $path = $request->path ?? "documents";
        $path = save_file($file, "uploads/{$path}", $request->name);
        $asset = Asset::create([
            'model_id' => $room->id, 
            'model' => Appointment::class, 
            'name' => $request->name ?? $file->getClientOriginalName(), 
            'content' => $path, 
            'link' => asset($path), 
            'type' => $file->getClientMimeType()
        ]);

        if( $asset ){
            $message = $room->messages()->create([
                'message' => [
                    'type' => 'file', 
                    'data' => $asset->only(['name', 'link', 'type', 'id'])
                ],
                'user_id' => auth()->id()
            ]);

            broadcast(new MessageSent($message->user, $message))->toOthers();

            if( $message ){
                return (new ChatMessages($message))->additional(['status' => true, 'message' => 'Message Sent']);
            }
            return response()->json([
                'status' => false, 
                'message' => 'Message Not Sent', 
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "File upload failed!"
        ]);
    }

}
