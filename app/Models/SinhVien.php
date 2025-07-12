<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SinhVien extends Model
{
    protected $table = 'sinhviens';
    protected $primaryKey = 'ma_sinh_vien';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_sinh_vien',
        'ma_nguoi_dung',
        'mssv',
        'trang_thai',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function lienHes()
    {
        return $this->hasMany(LienHe::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    // Lấy tất cả các bản ghi lịch sử lớp học
    public function sinhVienLopHocs()
    {
        return $this->hasMany(SinhVienLopHoc::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    public function lopHienTai()
    {
        return $this->hasOne(SinhVienLopHoc::class, 'ma_sinh_vien', 'ma_sinh_vien')
            ->where('is_hien_tai', 1);
    }
    public function lopHocs()
    {
        return $this->belongsToMany(
            LopHoc::class,
            'sinh_vien_lop_hoc',
            'ma_sinh_vien',
            'ma_lop_hoc'
        );
    }
}
