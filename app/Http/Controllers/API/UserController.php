<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Functions;
use App\Models\User;

class UserController extends Controller
{
    use Functions;

    public function login(Request $request){
        try {
            $v = validator($request->all(), [
                'user_name' => 'required',
                'password'  => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendResponse(false, $v->errors()->first());
            }
            $url = config('constants.crm_url').'/Api/getLogindetail.php?user_name='.$request->user_name.'&password='.$request->password;
            
            /**crm Login api  */
            $result = $this->getData($url);
            
            if(isset($result->error)){
                return $this->sendResponse(false, $result->error);
            }

            $user = User::where('customer_id', $result->id)->first();

            $input['name']         =  $result->name;
            $input['email']        =  $request->user_name;
            $input['customer_id']  =  $result->id;
            $input['profile_pic']  =  $result->profile_pic;
            $input['user_details'] =  json_encode($result);

            if (!$user) {
                $user = User::create($input);
            }
            else{
                $user->user_details =  json_encode($result);
                $user->profile_pic  =  $input['profile_pic'];
                $user->email        =  $input['email'];
                $user->save();
            }

            $accessTokenObject = $user->createToken('Login');

            $data = [
                'token' => $accessTokenObject->accessToken,
                'user' => $user,
            ];

            /**Add crm user action trail */
            if ($user) {
                $action_id   = $user->id; //User Id id
                $action_type = 'L'; //L = Login
                $module_name = "Login"; //module name base module table
                $created_by  = $user->id;
                $trail = $this->addCrmUserActionTrail($action_id, $action_type, $module_name,$created_by);
            }
            /**End manage trail */
            return $this->sendResponse(true, 'Login successfully', $data);
        } catch (\Illuminate\Database\QueryException $e){
            return $this->sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }

    /** Logout user */
    public function logout()
    {
        try{
            $accessToken = auth()->user()->token();
            \DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                    'revoked' => true
                ]);

            $accessToken->revoke();
        } catch (\Illuminate\Database\QueryException $e){
            return $this->sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }

        return $this->sendResponse(true, 'success');
    }

    /**change Password */
    public function changePassword(Request $request){
        try {
            $v = validator($request->all(), [
                'user_name'    => 'required',
                'old_password' => 'required',
                'password'     => 'required|confirmed',
            ]);

            if ($v->fails()) {
                return $this->sendResponse(false, $v->errors()->first());
            }

            $url = config('constants.crm_url').'/Api/getLogindetail.php?user_name='.$request->user_name.'&change_password='.$request->password.'&confirm_password='.$request->password_confirmation;
            
            /**crm change Password api  */
            $result = $this->getData($url);

            //dd($result);
            if(isset($result->error) && ($result->error != 'password changed successfuly')){
                return $this->sendResponse(false, $result->error);
            }else{
                return $this->sendResponse(true,'success',$result->error);
            }
        } catch (\Illuminate\Database\QueryException $e){
            return $this->sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }

    /**Send password link to user email*/
    public function forgetPassword(Request $request){
       try{
            $v = validator($request->all(), [
                'email' => 'required|email',
            ]);

            if ($v->fails()) {
                return $this->sendResponse(false, $v->errors()->first());
            }

            $url = config('constants.crm_url').'/Api/getLogindetail.php?fgt-email='.$request->email;
            
            /**crm forget Password api  */
            $result = $this->getData($url);
            //dd($result);
            if(isset($result->error)){
                return $this->sendResponse(false, $result->error);
            }else{
                return $this->sendResponse(true,'success',$result->message);
            }
        } catch (\Illuminate\Database\QueryException $e){
            return $this->sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }

    /**get All User List */
    public function getUserList(Request $request){
        try{
            $url  = config('constants.crm_url').'/ChatApi/get_user_list.php';
            $list = $this->getData($url);
            $user = [];
            $i = 0;
            if(isset($list->users) && $list->users){
                /**response convert into key value array*/
                foreach($list->users as $key => $value){
                    $user[$i]['id']   = $key;
                    $user[$i]['name'] = $value;
                    $i++;
                }
                return $this->sendResponse(true, 'User list get successfully',$user); 
            }else{
                return $this->sendResponse(false, 'someting went wrong!!'); 
            }          
        } catch (\Illuminate\Database\QueryException $e){
            return $this->sendResponse(false, $e->getMessage());
        } catch (\Exception $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }
}
