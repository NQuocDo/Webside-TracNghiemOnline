<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVienLopHoc extends Model
{
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
        'is_hien_tai',
    ];

    public $timestamps = false;

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop_hoc', 'ma_lop_hoc');
    }
}
