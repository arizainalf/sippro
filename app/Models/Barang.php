<?php

namespace App\Models;

use App\Models\Stok;
use Ramsey\Uuid\Uuid;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
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
    public function stokMasuks() {
        return $this->hasMany(StokMasuk::class);
    }

    public function stokKeluars() {
        return $this->hasMany(StokKeluar::class);
    }
    public function stoks() {
        return $this->hasMany(Stok::class);
    }
    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }


}
