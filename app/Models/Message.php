<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id','receiver_id','message','is_read'];

    //public $fillable = ['sender_id','receiver_id','message','file','thumbnail','file_url','file_type','file_size','conversation_id','is_read','delete_status'];
}
