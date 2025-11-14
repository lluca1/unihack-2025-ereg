<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'media_type',
        'media_path',
        'thumbnail_path',
        'mime_type',
    ];

    /**
     * Get the expositions the exhibit is attached to ordered by pivot position.
     */ 
    public function expositions()
    {
        return $this->belongsToMany(Exposition::class)
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /**
     * Get the user that uploaded the exhibit media.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
                  