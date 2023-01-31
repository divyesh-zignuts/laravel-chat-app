@extends('layouts.app')

@section('content')
<div class="content" style="min-height: 83vh;">
    <!-- Animated -->
    <div class="animated fadeIn">
        <!-- Widgets  -->
        <div class="row">

            <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

            <h5 class="font-weight-bold mb-3 text-center text-lg-start">Member</h5>

            <div class="card">
                <div class="card-body">

                <ul class="list-unstyled mb-0">
                    @foreach($users as $user)
                    <li onClick="getUserChat({{$user->id}});" class="p-2 border-bottom" style="background-color: #eee;">
                        <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-8.webp" alt="avatar"
                                    class="rounded-circle d-flex align-self-center me-3 shadow-1-strong" width="60">
                                <div class="pt-1">
                                    <p class="fw-bold mb-0">{{ $user->name }}</p>
                                    <p class="small text-muted">{{ $user->last_message ?? '' }}</p>
                                </div>
                            </div>
                            <!-- <div class="pt-1">
                            <p class="small text-muted mb-1">Just now</p>
                            <span class="badge bg-danger float-end">1</span>
                            </div> -->
                        </a>
                    </li>
                    @endforeach
                </ul>

                </div>
            </div>

            </div>
            
            <!-- chat body-->
            
			<div class="col-md-6 col-lg-7 col-xl-8" >
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title"><div class="openChatUser"></div></strong>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title box-title">Live Chat</h4>
                        <div class="card-content">
                            <div class="messenger-box">
                                <div class="chat-message scroll">

                                </div>
                                <div class="send-mgs" style="margin-top:70px;">
                                    <div class="yourmsg">
                                        <input id="message" class="form-control" type="text" style="margin-top:85px;">
                                    </div>
                                    <button class="btn msg-send-btn sendMessage">
                                        <i class="pe-7s-paper-plane"></i>
                                    </button>
                                </div>
                            </div><!-- /.messenger-box -->
                        </div>
                    </div> <!-- /.card-body -->
                </div><!-- /.card -->
            </div>
        </div>
        <!-- /Widgets -->
		
       <div class="clearfix"></div>
      
    </div>
    <!-- .animated -->
</div>
@endsection
@section('script')
<script src="{{ asset('assets/js/chat.js') }}"></script>
    <!-- <script>
        var user_id = "{{ $users[0] ? $users[0]['id'] : null }}";
       // var messages = [];
        console.log(user_id);

       
        if(user_id)
        {
            getUserChat(user_id);
        }
        function getUserChat(id){
            var html1 = "";
            var html = "";
            $(".openChatUser span").remove();
            $(".chat-message").append(html);
            var chat_id = id;

            /** Get User Details */
            jQuery.ajax({
                type:'GET',
                url:'/getUserDetails/'+id,
                success:function(userResponse){
                    var userDetails = JSON.parse(userResponse)
                    user = userDetails.data;
                    console.log(user.length)
                    html1 += '<span><input type="hidden" id="reciver_id" class="reciver_id" name="reciver_id" value="'+user.id+'">'+user.name+'</span>';
                    
                    $(".openChatUser").append(html1);
                }
            });

            /** Get User Message List */
            jQuery.ajax({
                type:'GET',
                url:'/getUserChat/'+id,
                success:function(response){
                   var messages = response.data;
                    data = JSON.parse(response);
                    messages = data.data;
                    if(messages.length > 0)
                    {
                        for (let message of messages) {
                            console.log(message);
                            if(message.sender_id == chat_id)
                            {
                                html +='<ul><li><div class="msg-received msg-container"><div class="avatar"> <img src="images/avatar/64-1.jpg" alt=""> <div class="send-time">11.11 am</div> </div> <div class="msg-box"> <div class="inner-box"> <div class="name"> </div> <div class="meg">'+message.message+'  </div> </div> </div> </div> </li> </ul>';
                            }
                            else
                            {
                                html +='<ul><li><div class="msg-sent msg-container"><div class="avatar"><img src="images/avatar/64-2.jpg" alt=""> <div class="send-time">11.11 am</div></div><div class="msg-box"> <div class="inner-box"> <div class="name"></div> <div class="meg"> '+message.message+' </div> </div> </div> </div> </li></ul> ';
                            }

                            
                        }
                        $(".chat-message").append(html);
                    }
                    else
                    {
                        $(".chat-message ul").remove();
                    }
                    
                }
            });
        }

        /**Message Send */
        jQuery(document).on('click','.sendMessage', function(){;
            var sender_id = "{{ auth()->user()->id }}";
            var reciver_id = jQuery("#reciver_id").val();
            var message = jQuery("#message").val();
            jQuery.ajax({
                type:'POST',
                url:'/sendMessage',
                data:{sender_id:sender_id,reciver_id:reciver_id,message:message},
                success:function(response){
                    jQuery("#message").val('')
                    $(".chat-message").append('<ul><li><div class="msg-sent msg-container"><div class="avatar"><img src="images/avatar/64-2.jpg" alt=""> <div class="send-time">11.11 am</div></div><div class="msg-box"> <div class="inner-box"> <div class="name"></div> <div class="meg"> '+message+' </div> </div> </div> </div> </li></ul> ');                    
                }
            });
        })
    </script> -->
    <script>
          
        window.Echo.channel('message_created')
         .listen('MessageCreated',(response) =>{
            //data = JSON.parse(response);
            var r_id = jQuery("#reciver_id").val();
            var s_id = {{ auth()->user()->id }}
            if((s_id == response.message.receiver_id) && (r_id == response.message.sender_id))
            {
                $(".chat-message").append('<ul><li><div class="msg-received msg-container"><div class="avatar"> <img src="images/avatar/64-1.jpg" alt=""> <div class="send-time">11.11 am</div> </div> <div class="msg-box"> <div class="inner-box"> <div class="name"> </div> <div class="meg">'+ response.message.message+'  </div> </div> </div> </div> </li> </ul>');
            }
         });

         window.Echo.channel('public-channel')
         .listen('PublicChannel',(data) =>{
             console.log(data);
         });
    </script>
@endsection