<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Models\ExceptionManagement;
use DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Exception $e,$request) {
            $user_id = 0;
            if (auth()->user()) 
            {
                $user_id = auth()->user()->id;
            }
            //rollback all the transactions so far
            DB::rollback();
            //begin new transaction for database insert of exception_management_object
            DB::beginTransaction();
            try 
            {
                $exception_management_object = ExceptionManagement::create(
                    [
                        'message'=>$e->getMessage(),
                        'stack_trace'=>json_encode($e->getTrace()),
                        'file'=>$e->getFile(),
                        'line'=>$e->getLine(),
                        'header_info'=>json_encode($request->header()),
                        'ip'=>$request->ip(),'created_by'=>$user_id
                    ]);
    
                DB::commit();
            } 
            catch (\Exception $e) 
            {
                DB::rollback();
            }
            if($e->getMessage() == 'Route [login] not defined.'){
                $code = 401;//Unauthenticated error code
                $error = 'Unauthenticated';
            }else{
                $code = 555;//Authenticated error code
                $error = $e->getMessage();
            }
            return response()->json(['error' => $error], $code);
            //
        });
    }
}
