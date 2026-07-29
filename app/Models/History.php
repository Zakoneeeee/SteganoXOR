<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    // Tambahkan notes, mse, psnr ke dalam array
    protected $fillable = ['user_id', 'action_type', 'file_name', 'file_path', 'message_length', 'xor_key', 'notes',];
}