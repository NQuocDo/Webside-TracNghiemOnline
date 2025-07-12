<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietBaiKiemTraVaCauHoi extends Model
{
    protected $table = 'chi_tiet_bai_kiem_tra_va_cau_hois';
    protected $primaryKey = 'ma_chi_tiet_bktch';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_chi_tiet_bktch',
        'ma_bai_kiem_tra',
        'ma_chi_tiet_dtch',
        'ma_cau_hoi',
        'thu_tu',
    ];

    public $timestamps = true; // để tự động xử lý created_at, updated_at

    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }

    public function chiTietDeThiVaCauHoi()
    {
        return $this->belongsTo(ChiTietDeThiVaCauHoi::class, 'ma_chi_tiet_dtch', 'ma_chi_tiet_dtch');
    }
    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'ma_cau_hoi', 'ma_cau_hoi');
    }
    public function chiTietDeThi()
    {
        return $this->belongsTo(ChiTietDeThiVaCauHoi::class, 'ma_chi_tiet_dtch', 'ma_chi_tiet_dtch');
    }
}
