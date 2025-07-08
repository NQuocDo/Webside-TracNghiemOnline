<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeThi extends Model
{
    //
    protected $table = 'de_this';
    protected $primaryKey = 'ma_de_thi';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'ma_de_thi',
        'ten_de_thi',
        'thoi_gian_lam_bai',
        'so_luong_cau_hoi',
        'trang_thai',
        'ma_mon_hoc',
        'ma_giang_vien'
    ];
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_giang_vien', 'ma_giang_vien');
    }

    // Một đề thi thuộc về một môn học
    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
    public function cauHoi()
    {
        return $this->belongsToMany(
            CauHoi::class,
            'chi_tiet_de_thi_va_cau_hois',
            'ma_de_thi',
            'ma_cau_hoi',
            'ma_de_thi',
            'ma_cau_hoi'
        )
            ->using(ChiTietDeThiVaCauHoi::class)      // Dùng pivot model tùy chỉnh
            ->withPivot('thu_tu')
            ->orderBy('pivot_thu_tu')
            ->withTimestamps();
    }
    //Note: lý do có sự lặp là do Laravel cần chỉ rõ khoá chính và khoá ngoại
    public function baiKiemTras()
    {
        return $this->hasMany(BaiKiemTra::class, 'ma_de_thi', 'ma_de_thi');
    }
}
