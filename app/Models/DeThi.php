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
            'chi_tiet_de_thi_va_cau_hois',  // tên bảng 
            'ma_de_thi',                   // khoá ngoại ở bảng ctdtvch đến bảng de_thi
            'ma_cau_hoi',                  // khoá ngoại ở bảng ctdtvch  đến bảng cau_hois
            'ma_de_thi',                   // khóa chính của bảng de_thi
            'ma_cau_hoi'                   // khóa chính của bảng cau_hois
        )->withTimestamps();
    }
    //Note: lý do có sự lặp là do Laravel cần chỉ rõ khoá chính và khoá ngoại
    public function baiKiemTras()
    {
        return $this->hasMany(BaiKiemTra::class, 'ma_de_thi', 'ma_de_thi');
    }
}
