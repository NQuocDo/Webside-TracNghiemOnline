<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonHoc extends Model
{
    //
    use HasFactory;

    protected $table = 'mon_hocs'; // Tên bảng của bạn
    protected $primaryKey = 'ma_mon_hoc'; // Khóa chính của bảng
    protected $keyType = 'string';

    protected $fillable = [
        'ten_mon_hoc',
        'so_tin_chi',
        'hoc_ky',
        'mo_ta',
        'do_kho',
        'tieu_chi_ket_thuc_mon',
        'trang_thai'
    ];

    // Quan hệ một-nhiều: Một môn học có nhiều câu hỏi
    public function cauHois()
    {
        return $this->hasMany(CauHoi::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }

    // Quan hệ nhiều-một: Một môn học thuộc về một giảng viên
    public function giangViens()
    {
        return $this->belongsToMany(GiangVien::class, 'phan_quyen_days', 'ma_mon_hoc', 'ma_giang_vien');
    }
    public function phanQuyenDays()
    {
        return $this->hasMany(PhanQuyenDay::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
    public function deThis()
    {
        return $this->hasMany(DeThi::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
    public function baiKiemTras()
    {
        return $this->hasMany(BaiKiemTra::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
}
