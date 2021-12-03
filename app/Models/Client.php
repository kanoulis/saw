<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'surname'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'user_id', 'id')->orderByDesc('payments.created_at');
    }
}
