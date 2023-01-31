<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Functions;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\MineConversation;
use App\Models\UserProfile;

class MessageController extends Controller
{
    use Functions;
    //Get Convergation list 
    public function index(Request $request){
        try {
            $validator = validator($request->all(), [
                'conversation_id' => 'required',
                'page'            => 'required',
                'perPage'         => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->sendResponse(false, $validator->errors()->first());
            }

            /**Get message list based on conversation id  */
            $messages = Message::select('id','message_id','platform','direction','message','is_read','message_date_time','image_url','file_url','audio_url','video_url')->where('conversation_id',$request->conversation_id)->orderBy('message_date_time','DESC');
            
            /**add pagination */
            $nextpage = (($request->page??1)+1);
            $limit    = $request->perPage;
            $offset   = (($request->page??1)-1)*$limit;
            
            /**get data in pagination*/
            if($request->perPage && $request->page){
                $messages->take($limit)->skip($offset);
            }
            $messages = $messages->get()->toArray();

            /**End */
            
            $date       = [];
            $allMessage = [];
           /* foreach ($messages as $key => $row)
            {
                $date[$key]  = $row['message_date_time'];
            }   
            $messages = array_multisort($date, SORT_ASC,$messages);
            $allMessage = $messages;*/
            $messages     = array_reverse($messages);
            
            /**Get Conversation Details */
            $conversation = Conversation::select('id','conversation_id','channel_id','platform','displayName','phone')->where('conversation_id',$request->conversation_id)->first();

            /**Get save user profile  */
            $user_profile = UserProfile::where('conversation_id',$request->conversation_id)->first();
            
            /**update message is_read flag */
            Message::where('conversation_id',$request->conversation_id)->where('is_read',0)->update(['is_read'=>1]);
            
            /**response object */
            $data = [
                'nextpage'             => $nextpage,
                'conversation_details' => $conversation,
                'user_profile'         => $user_profile,
                'messages'             =>  $messages
            ];

            return $this->sendResponse(true,'success',$data);
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }

    /**send message */
    public function sendMessage(Request $request){
        $v = validator($request->all(), [
            //'channel_from' => 'required',
           // 'channel_to' => 'required',
            'message'         => 'nullable',
            'conversation_id' => 'required'
            //'type' => 'required|in:text,image,video,audio,file,location,event,rich,menu,buttons,link',
        ]);

        if ($v->fails()) {
            return $this->sendResponse(false, $v->errors()->first());
        }
        try {
            $messageBird  = new \MessageBird\Client(env('API_ACCESS_KEY')); // Set your own API access key here.
            $conversation = Conversation::where('conversation_id',$request->conversation_id)->first();
            
            $today =\Carbon\Carbon::now();
            $hour =$today->diffInHours($conversation->lastReceivedDatetime);
            //dd($hour);
            $content = new \MessageBird\Objects\Conversation\Content();
            
            //if(isset($request->content['text'])){
            // $content->text = $request->content['text'];
            //} 
            /*
            if(isset($request->content['image'])){
                $content->image = $request->content['image'];
            } 
            if(isset($request->content['video'])){
                $content->video = $request->content['video'];
            } 
            if(isset($request->content['audio'])){
                $content->audio = $request->content['audio'];
            } 
            if(isset($request->content['file'])){
                $content->file = $request->content['file'];
            } 
            if(isset($request->content['externalAttachments'])){
                $content->externalAttachments = $request->content['externalAttachments'];
            }*/
            
            
            $sendMessage = new \MessageBird\Objects\Conversation\SendMessage();
            $sendMessage->from = $conversation->channel_id;
            $sendMessage->to = $conversation->phone; // Channel-specific, e.g. MSISDN for SMS.
            
            if($hour>24 && $conversation->platform == 'whatsapp'){
                if($request->message && !$request->file){
                    $content->hsm = array(
                                        "language"=> array(
                                        "code"=> "en"
                                        ),
                                        "namespace"=> "bbfd512b_be09_4ca7_ad35_a49ad92f5409",
                                        "params"=>[
                                        array(
                                            "default"=> $request->message
                                        )
                                        ],
                                        "templateName"=> "text_message"
                                    );
                    $sendMessage->content = $content;
                    $sendMessage->type = 'hsm';
                }
                else if($request->file && !$request->message){
                    if($request->hasFile('file')){
                        $file = $request->file('file');
                        $fullName=  $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();;
                        $fileName = $file->move(public_path('files/'),$fullName);
                        $path = url('/files/'.$fullName);
                        $content->file = array(
                                                "url"=>$path,
                                                "caption"=>$fullName,
                                            );
                        $sendMessage->type = 'file';
                    }
                }
            }else{
                if($request->message){
                    $content->text = $request->message;
                    $sendMessage->content = $content;
                    $sendMessage->type = 'text';
                }
                else if($request->hasFile('file')){
                    $file     = $request->file('file');
                    $fullName =  $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();;
                    $fileName = $file->move(public_path('files/'),$fullName);
                    $path = url('/files/'.$fullName);
                    $content->file = [
                        'url'     => $path,
                        "caption" => $fullName,
                    ];
                    $sendMessage->content = $content;
                    $sendMessage->type    = 'file';
                }
            }

            $sendResult = $messageBird->conversationSend->send($sendMessage);
        
            /**check message id exiest or not */
            $message = Message::where('message_id',$sendResult->id)->first();
            if(!$message){
                /**create messge */
                $input['conversation_id'] = $conversation->conversation_id;
                $input['channel_id']      = $conversation->channelId;
                $input['message_id']      = $sendResult->id;
                $input['platform']        = $conversation->platform;
                $input['direction']       = $conversation->direction??'sent';
                $input['message']         = $request->message;
                $input['is_read']         = 1;
                $message                  = Message::create($input);
                /**Add crm user action trail */
                if ($message) {
                    $action_id   = $message->id; //User Id id
                    $action_type = 'S'; //S = Send
                    $module_name = "Message Send"; //module Message Send
                    $created_by  = auth()->user()->id;

                    $trail = $this->addCrmUserActionTrail($action_id, $action_type, $module_name,$created_by);
                }
            }
            $exiest = MineConversation::where('conversation_id',$conversation->conversation_id)->where('customer_id',auth()->user()->customer_id)->first();
            if(!$exiest){
                $mine['conversation_id'] = $conversation->conversation_id;
                $mine['customer_id']     = auth()->user()->customer_id??0;
                $mine['created_by']      = auth()->user()->id;
                
                /**create My conversation */
                $mineConversation = MineConversation::create($mine);
            
                /**Add crm user action trail */
                if ($mineConversation) {
                    $action_id   = $mineConversation->id; //Assign Id
                    $action_type = 'AS'; //A = Assign
                    $module_name = "Assign"; //module name base module 
                    
                    $trail = $this->addCrmUserActionTrail($action_id, $action_type, $module_name);
                }
            }
            return $this->sendResponse(true,'success',$sendResult);
        } catch (\MessageBird\Exceptions\AuthenticateException $e) {
            // That means that your accessKey is unknown
            return $this->sendResponse(false, $e->getMessage());
        } catch (\MessageBird\Exceptions\BalanceException $e) {
            // That means that you are out of credits, so do something about it.
            return $this->sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }

    /**create webhook (not implement in web app) */
    public function webhook(Request $request){
        $messageBird = new \MessageBird\Client(env('API_ACCESS_KEY')); // Set your own API access key here.
        //dd($request->all());
        try {
            $webhook         = new \MessageBird\Objects\Conversation\Webhook();
            $webhook->url    = $request->url;
            $webhook->events = $request->events;
            
            //$webhooks =$messageBird->conversationWebhooks->delete('e15285b488444579b7495363f14b8fa7');
            $webhooks = $messageBird->conversationWebhooks->create($webhook);
            
            //$webhooks = $messageBird->conversationWebhooks->getList();

            return $this->sendResponse(true,'success',$webhooks);
        } catch (\MessageBird\Exceptions\AuthenticateException $e) {
            // That means that your accessKey is unknown
            return $this->sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }
}
