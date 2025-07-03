<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BangDiem extends Model
{
    use HasFactory;

    protected $table = 'bang_diems'; // hoặc 'bang_diem' nếu đó là tên bảng

    protected $primaryKey = 'ma_bang_diem';
    public $incrementing = false; // vì bạn dùng mã thủ công (MBDxxxxx)
    protected $keyType = 'string';

    protected $fillable = [
        'ma_bang_diem',
        'ma_sinh_vien',
        'ma_mon_hoc',
        'ma_bai_kiem_tra',
        'diem_so',
        'so_cau_dung',
        'so_cau_sai'
    ];

    // Optional: quan hệ
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
    public function deThi()
    {
        return $this->belongsTo(DeThi::class, 'ma_de_thi', 'ma_de_thi');
    }

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_giang_vien', 'ma_giang_vien');
    }
}

