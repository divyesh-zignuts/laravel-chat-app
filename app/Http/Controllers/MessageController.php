<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageCreated;

class MessageController extends Controller
{
    /**Get User Details */
    public function getUserDetails($id)
    {
        $receiver_id = $id;

        $user = User::where('id',$id)->first();

		//dd(\DB::getQueryLog());
		$success = [
                'message' => "User Details",
                'success' => true,
                'data' => $user,
        ];
        echo json_encode($success);
    }

    /**Get User Conversation list */
    public function getUserChat($id)
    {
        $authUser = auth()->user();
		//DB::enableQueryLog();
        $sender_id = $authUser->id;
        $receiver_id = $id;

        $messages = Message::whereRaw('(sender_id='.$sender_id.' AND receiver_id='.$receiver_id.') OR (sender_id='.$receiver_id.' AND receiver_id='.$sender_id.')')->get();

		//dd(\DB::getQueryLog());
		$success = [
                'message' => "Messages",
                'success' => true,
                'data' => $messages,
        ];
        echo json_encode($success);
    }

    /**Send Message */
    public function sendMessage(Request $request)
    {
        $receiver_id = $request['reciver_id'];
        $sender_id = auth()->user()->id;
        $message = $request->message;

        $message = Message::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'is_read' => 0,
        ]);

        /**update last message */
        User::whereIn('id',[$receiver_id, $sender_id])->update(['last_message' => $request->message]);

        //Broadcast message
		broadcast(new MessageCreated($message));

		$success = [
                'message' => "Send Message Successfully",
                'success' => true,
                'data' => $message,
        ];
        echo json_encode($success);
    }
}
