<?php

namespace App;

use Mail;
use Illuminate\Database\Eloquent\Model;

class UninstallTracker extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uninstall_tracking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason_id',
        'plugin',
        'url',
        'user_email',
        'site',
        'admin_email',
        'first_name',
        'last_name',
        'user_name',
        'reason_info',
        'server_info',
        'locale',
        'hash',
        'version',
        'multisite'

    ];
}
