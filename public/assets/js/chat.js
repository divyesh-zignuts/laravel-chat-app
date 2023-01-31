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
  
