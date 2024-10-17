<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\DetailStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
    public function detailStoks() {
        return $this->hasMany(DetailStok::class);
    }


}
