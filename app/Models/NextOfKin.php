<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'relationship',
        'phone',
        'email',
        'address',
    ];

    /**
     * Get the user that owns the next of kin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
