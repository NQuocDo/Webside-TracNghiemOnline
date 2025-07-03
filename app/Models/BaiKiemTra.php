<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiKiemTra extends Model
{
    use HasFactory;

    protected $table = 'bai_kiem_tras';
    protected $primaryKey = 'ma_bai_kiem_tra';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_bai_kiem_tra',
        'ma_de_thi',
        'ten_bai_kiem_tra',
        'trang_thai',
        'thoi_gian_khoa',
    ];

    public function deThi()
    {
        return $this->belongsTo(DeThi::class, 'ma_de_thi', 'ma_de_thi');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }

    public function giangVien()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_giang_vien', 'ma_nguoi_dung');
    }
    public function lichSuLamBais()
    {
        return $this->hasMany(LichSuLamBai::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }
}

