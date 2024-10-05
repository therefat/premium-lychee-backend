<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddressBook extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id','isDefault','email', 'phone','city','upzila','zip', 'address'];

    public  function user():belongsTo{
        return $this->belongsTo(User::class);
    }

}
