<?php

namespace App;

use Mail;
use Illuminate\Database\Eloquent\Model;

class TrackerDetails extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plugin_tracking_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tracking_id',
        'log',
        'tracking_date',
    ];
}