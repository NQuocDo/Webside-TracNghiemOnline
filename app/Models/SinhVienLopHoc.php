<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVienLopHoc extends Model
{
    //
    protected $table = 'sinh_vien_lop_hoc';
    protected $primaryKey = 'ma_sv_lh';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_sv_lh',
        'ma_sinh_vien',
        'ma_lop_hoc',
        'hoc_ky',
        'nam_hoc',
    ];

    public $timestamps = false;

    // Quan hệ: Mỗi bản ghi thuộc về 1 sinh viên
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    // Quan hệ: Mỗi bản ghi thuộc về 1 lớp học
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop_hoc', 'ma_lop_hoc');
    }
  
}
