<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CauHoi extends Model
{
    //
    use HasFactory;

    protected $table = 'cau_hois'; // Tên bảng của bạn
    protected $primaryKey = 'ma_cau_hoi'; // Khóa chính của bảng
    public $incrementing = false;
    protected $keyType = 'string';

    // Cần điền fillable hoặc guarded nếu bạn dùng mass assignment
    protected $fillable = [
        'noi_dung',
        'hinh_anh',
        'ghi_chu',
        'do_kho',
        'ma_mon_hoc',
        'pham_vi',
        'trang_thai',
        'ma_giang_vien'
    ];

    // Quan hệ một-nhiều: Một câu hỏi có nhiều đáp án
    public function dapAns()
    {
        return $this->hasMany(DapAn::class, 'ma_cau_hoi', 'ma_cau_hoi');
    }

    // Quan hệ nhiều-một: Một câu hỏi thuộc về một môn học
    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }

    // Quan hệ nhiều-một: Một câu hỏi được tạo bởi một giảng viên (nguoi_dung)
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_giang_vien', 'ma_giang_vien');
    }
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
    public function deThi()
{
    return $this->belongsToMany(
        DeThi::class,
        'chi_tiet_de_thi_va_cau_hois',
        'ma_cau_hoi', 
        'ma_de_thi',   
        'ma_cau_hoi', 
        'ma_de_thi'   
    )->withTimestamps();
}
}
