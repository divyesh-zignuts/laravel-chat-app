<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conversation;
use App\Http\Controllers\Functions;

class Newinquiry extends Command
{
    use Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inquiry:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("messagebird Cron job started!");

        try {
            $messageBird = new \MessageBird\Client(config('constants.messagebird_api_key'));

            $optionalParameters = [
                'limit'     => 20,
                'status'    => 'all',
            ];
            // Take 20 objects, set limit and status
            $conversations = $messageBird->conversations->getList($optionalParameters);
            //\Log::info([$conversations]);
            foreach ($conversations->items as $conversation) {

                /**create last recived date formate Y-m-d H:i:s */
                $date       = explode('T', $conversation->lastReceivedDatetime);
                $time       = explode('.', $date[1]);
                $lastdate   = $date[0] . " " . $time[0];
                $imageUrl     = null;
                $videoUrl     = null;
                $audioUrl     = null;
                $fileUrl      = null;

                /**Get message Using conversation Id */
                $list = $this->getLastMessageNew($conversation->id);

                if ($list->type == 'hsm') {
                    $params         = $list->content->hsm->params ?? null;
                    $templateName   = $list->content->hsm->templateName ?? null;
                    $language       = $list->content->hsm->language->code ?? null;

                    /**Hsm message convertinto text */
                    $hsm_message =  $this->readHsmMessageNew($params, $templateName, $language);

                    if ($hsm_message) {
                        unset($list->content->hsm);
                        $list->content->text = $hsm_message;
                    }
                }
                else if($list->type == 'image')
                {
                    $imageUrl     = $request->message['content']['image']['url']??null;
                    $list->content->text = basename($imageUrl)??"";
                }
                else if($list->type == 'file')
                {
                    $fileUrl = $request->message['content']['file']['url']??null;
                    $list->content->text = basename($fileUrl)??"";
                }
                else if($list->type == 'video')
                {
                    $videoUrl = $request->message['content']['video']['url']??null;
                    $list->content->text = basename($videoUrl)??"";
                }
                else if($list->type == 'audio')
                {
                    $audioUrl = $request->message['content']['audio']['url']??null;
                    $list->content->text = basename($audioUrl)??"";
                }
                /**get contact details */
                $contactDetails = $this->getContactDetailsCurl($conversation->contact->id);

                /**check if conversation exiest or not if exiest then update conversation last recived datetime otherwise create a new conversation */
                $con = Conversation::where('conversation_id', $conversation->id)->first();
                if ($con) {
                    $con->update([
                        'channel_id'                => $list->channelId,
                        'contact_id'                => $conversation->contact->id,
                        'contact_details'           => json_encode($contactDetails),
                        'platform'                  => $list->platform,
                        'direction'                 => $list->direction,
                        'displayName'               => $contactDetails->displayName,
                        'phone'                     => $contactDetails->phone,
                        'last_message_id'           => $list->id,
                        'last_message'              => $list->content->text ?? null,
                        'last_message_details'      => json_encode($list),
                        'last_received_datetime'    => $lastdate,
                        'image_url'                 => $imageUrl,
                        'file_url'                  => $fileUrl,
                        'video_url'                 => $videoUrl,
                        'audio_url'                 => $audioUrl
                    ]);
                } else {
                    $input['conversation_id']           = $conversation->id;
                    $input['channel_id']                = $list->channelId;
                    $input['contact_id']                = $conversation->contact->id ?? null;
                    $input['contact_details']           = json_encode($contactDetails);
                    $input['last_message_id']           = $list->id;
                    $input['platform']                  = $list->platform;
                    $input['direction']                 = $list->direction;
                    $input['displayName']               = $contactDetails->displayName;
                    $input['phone']                     = $contactDetails->phone;
                    $input['last_message']              = $list->content->text ?? null;
                    $input['last_message_details']      = json_encode($list);
                    $input['last_received_datetime']    = $lastdate;
                    $input['image_url']                 = $imageUrl;
                    $input['file_url']                  = $fileUrl;
                    $input['video_url']                 = $videoUrl;
                    $input['audio_url']                 = $audioUrl;
                    $new = Conversation::create($input);
                }
            }
            \Log::info("messagebird Cron job Finish with success!");
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::info(['messagebird Cron job Finish with Error' => $e]);
        } catch (\Exception $e) {
            \Log::info(['messagebird Cron job Finish with Error' => $e]);
        }
    }
}
