<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhanQuyenDay extends Model
{
    //
    protected $table = 'phan_quyen_days';
    protected $primaryKey = 'ma_phan_quyen';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'ma_phan_quyen',
        'ma_giang_vien',
        'ma_lop_hoc',
        'ma_mon_hoc'
    ];

    // Mối quan hệ với Giảng viên (thông qua NguoiDung)
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_giang_vien', 'ma_giang_vien');
    }

    // Mối quan hệ với Môn học
    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
    public function lopHoc()
{
    return $this->belongsTo(LopHoc::class, 'ma_lop_hoc', 'ma_lop_hoc');
}

}
