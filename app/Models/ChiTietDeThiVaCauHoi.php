<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDeThiVaCauHoi extends Model
{
    //
    protected $table = 'chi_tiet_de_thi_va_cau_hois';
    protected $primaryKey = 'ma_chi_tiet_dtch';
    public $incrementing = false;
    protected $typeKey = 'string';
    protected $fillable = [
        'ma_chi_tiet_dtch',
        'ma_de_thi',
        'ma_cau_hoi'
    ];
     public function deThi()
    {
        return $this->belongsTo(DeThi::class, 'ma_de_thi', 'ma_de_thi');
    }

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'ma_cau_hoi', 'ma_cau_hoi');
    }
}
