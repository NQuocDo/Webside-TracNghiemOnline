<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienHe extends Model
{
    //
    protected $table = 'lien_he';
    protected $primaryKey = 'ma_lien_he';
    public $incrementing = false;
    protected $typeKey = 'string';
    public $timestamps = true;
    protected $fillable = [
        'ma_lien_he',
        'tieu_de',
        'noi_dung',
        'ma_sinh_vien',
        'ma_giang_vien',
    ];
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_giang_vien', 'ma_giang_vien');
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }
}
