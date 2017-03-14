<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait as SyncableGraphNodeTrait;
use Eloquent;

class User extends Authenticatable
{
    use SyncableGraphNodeTrait;

    /**
     * The keys of the array are the names of the fields on the Graph node.
     * The values of the array are the names of the columns in the local database.
     */
    protected static $graph_node_field_aliases = [
        'id' => 'facebook_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

}
