<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\CrmUserActionTrail;
use Illuminate\Support\Facades\Http;

trait Functions
{
    // send json response
    public function sendResponse($status, $message, $data = null)
    {
        if ($status) {
            return response()->json(['message' => $message, 'data' => $data], 200);
        } else {
            return response()->json(['error' => $message], 400);
        }
    }

    /**For manage crm user action trail like user do anything in crm admin panel so manage the trail */
    public function addCrmUserActionTrail($action_id,$action_type, $module_name = null, $created_by = null)
    {
        $input['action_id']   = $action_id;
        $input['action_type'] = $action_type;
        $input['module_name'] = $module_name;
        $input['created_by']  = auth()->user() ? auth()->user()->id : null;

        if ($created_by != null) {
            $input['created_by'] = $created_by;
        }

        $trail = CrmUserActionTrail::create($input);
        return $trail;
    }

    /**Get contact details Curl */
    public function getContactDetailsCurl($contact_id){
        $response = Http::withHeaders([
                        'Authorization' => 'AccessKey '.config('constants.messagebird_api_key'),
                    ])->get('https://contacts.messagebird.com/v2/contacts/'.$contact_id);
        // dd($response->body());
        $result   = json_decode($response->body());
        return $result;
    }

    /**Get Last message Curl */
    public function getLastMessageCurl($conversationId){
        $response = Http::withHeaders([
                        'Authorization' => 'AccessKey '.config('constants.messagebird_api_key'),
                    ])->get('https://conversations.messagebird.com/v1/messages/'.$conversationId);
        
        $list = json_decode($response->body());
        //dd($list);
        return $list;
    }

    /**get Last message using conversation ID */
    public function getLastMessageNew($conversationId){
        $response = Http::withHeaders([
                        'Authorization' => 'AccessKey '.config('constants.messagebird_api_key'),
                    ])->get('https://conversations.messagebird.com/v1/conversations/'.$conversationId.'/messages');
        
        $list     = json_decode($response->body());
        //dd($list);
        return $list->items[0]??null;
    }

    /**read pre-approved message */
    public function readHsmMessage($params,$temptate_name,$language){
        //\Log::info($params);
        $response = Http::withHeaders([
                        'Authorization' => 'AccessKey '.config('constants.messagebird_api_key'),
                    ])->get('https://integrations.messagebird.com/v2/platforms/whatsapp/templates/'.$temptate_name.'/'.$language);
       
        $result   = json_decode($response->body());

        if(isset($result->components)){
            foreach($result->components as $value){
                if($value->type == "BODY"){
                    $i       = 1;
                    $string  = $value->text;
                    $Html    = $string;
                    if(!empty($params)){
                        foreach($params as $v){
                           // \Log::info($v->default.':'.$i);
                            $Html = str_replace("{{".$i."}}", $v['default'], $Html);
                            //$text = str_replace("{{".$i."}}",$v['default'],$value->text);
                            $i++;
                        }
                    }
                }
            }
            return $Html;
        }else{
            return false;
        }
    }

    /**read pre-approved message */
    public function readHsmMessageNew($params,$temptate_name,$language){
        //\Log::info($params);
        $response = Http::withHeaders([
                        'Authorization' => 'AccessKey '.config('constants.messagebird_api_key'),
                    ])->get('https://integrations.messagebird.com/v2/platforms/whatsapp/templates/'.$temptate_name.'/'.$language);
       
        $result   = json_decode($response->body());

        if(isset($result->components)){
            foreach($result->components as $value){
                if($value->type == "BODY"){
                    $i       = 1;
                    $string  = $value->text;
                    $Html    = $string;
                    if(!empty($params)){
                        foreach($params as $v){
                           // \Log::info($v->default.':'.$i);
                            $Html = str_replace("{{".$i."}}", $v->default, $Html);
                            //$text = str_replace("{{".$i."}}",$v['default'],$value->text);
                            $i++;
                        }
                    }
                }
            }
            return $Html;
        }else{
            return false;
        }
    }

    /**Get Curl Message */
    public function getData($url){
        $response = Http::get($url);        
        $result   = json_decode($response->body());
        return $result;
    }

    /**Post Curl */
    public function postData($url,$data=[]){
        $response = Http::post($url, $data);
        $result   = json_decode($response->body());
        return $result;
    }
}
