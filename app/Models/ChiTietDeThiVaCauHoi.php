<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChiTietDeThiVaCauHoi extends Pivot
{
    //
    protected $table = 'chi_tiet_de_thi_va_cau_hois';
    protected $primaryKey = 'ma_chi_tiet_dtch';
    public $incrementing = false;
    protected $typeKey = 'string';
    protected $fillable = [
        'ma_chi_tiet_dtch',
        'ma_de_thi',
        'ma_cau_hoi',
        'thu_tu',
    ];
    public function deThi()
    {
        return $this->belongsTo(DeThi::class, 'ma_de_thi', 'ma_de_thi');
    }

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'ma_cau_hoi', 'ma_cau_hoi');
    }
    public function chiTietBaiKiemTra()
    {
        return $this->hasMany(
            ChiTietBaiKiemTraVaCauHoi::class,
            'ma_chi_tiet_dtch',
            'ma_chi_tiet_dtch'
        );
    }
}
