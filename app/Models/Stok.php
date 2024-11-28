<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stok extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();

            $barang = Barang::find($model->barang_id);

            if ($model->tipe === 'masuk') {
                $barang->stok += $model->stok;
            } elseif ($model->tipe === 'keluar') {
                $barang->stok -= $model->stok;
            }

            $barang->save();
        });
        static::updating(function ($model) {

            $barang = Barang::find($model->barang_id);

            if ($model->tipe === 'masuk') {
                $barang->stok += $model->stok - $model->getOriginal('stok');
            } elseif ($model->tipe === 'keluar') {
                $barang->stok -= $model->stok - $model->getOriginal('stok');
            }

            $barang->save();
        });
        static::deleting(function ($stok) {
            $barang = Barang::find($stok->barang_id);

            if ($barang) {
                if ($stok->tipe === 'masuk') {
                    $barang->stok -= $stok->stok;
                } elseif ($stok->tipe === 'keluar') {
                    $barang->stok += $stok->stok;
                }

                $barang->save();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
