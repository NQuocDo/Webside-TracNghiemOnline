<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SinhVien extends Model
{
    //
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
    public function lopHocs()
    {
        return $this->belongsToMany(
            LopHoc::class,
            'sinh_vien_lop_hoc',
            'ma_sinh_vien',
            'ma_lop_hoc'
        )->withPivot('hoc_ky', 'nam_hoc');
    }

    public function sinhVienLopHocs()
    {
        return $this->hasMany(SinhVienLopHoc::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }
    public function lopHienTai()
    {
        return $this->hasOne(SinhVienLopHoc::class, 'ma_sinh_vien', 'ma_sinh_vien')
            ->orderByDesc('nam_hoc')
            ->orderByDesc('hoc_ky');
    }
    public function tatCaLop()
    {
        return $this->hasMany(SinhVienLopHoc::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }
}
