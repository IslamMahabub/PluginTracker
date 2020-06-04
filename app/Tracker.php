<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plugin_tracking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site',
        'url',
        'admin_email',
        'first_name',
        'last_name',
        'hash',
        'plugin',
    ];
}
