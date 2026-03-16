<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodeServer extends Model
{
    use HasFactory;
    protected $fillable = ['episode_id', 'server_name', 'video_url', 'type'];
}