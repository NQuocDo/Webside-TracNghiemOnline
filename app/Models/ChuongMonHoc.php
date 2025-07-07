<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuongMonHoc extends Model
{
    //
    use HasFactory;
    protected $table = 'chuong_mon_hocs';
    protected $primaryKey = 'ma_chuong';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'ma_chuong',
        'ten_chuong',
        'so_thu_tu',
        'ma_mon_hoc',
    ];

    // Quan hệ: Mỗi chương thuộc 1 môn học
    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
    public function cauHois()
{
    return $this->hasMany(CauHoi::class, 'ma_chuong', 'ma_chuong');
}
}
