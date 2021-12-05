<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function CreditCardAttributes()
    {
        return $this->hasOne(related: 'App\Models\CreditCardAttributes', foreignKey: 'client_id', localKey: 'id');
    }
}
