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
        'ma_lop_hoc',
        'mssv',
        'trang_thai',
    ];
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop_hoc', 'ma_lop_hoc');
    }
    public function lienHes()
    {
        return $this->hasMany(LienHe::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }
}
