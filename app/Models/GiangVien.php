<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    //
    protected $table = 'giangviens';
    protected $primaryKey = 'ma_giang_vien';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'ma_giang_vien',
        'ma_nguoi_dung',
        'hoc_vi',
    ];
    public function thongBaos()
    {
        return $this->hasMany(ThongBao::class, 'nguoi_gui', 'ma_giang_vien');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
    public function cauHois()
    {
        return $this->hasMany(CauHoi::class, 'ma_giang_vien', 'ma_giang_vien');
    }
    public function monHocs()
    {
        return $this->belongsToMany(MonHoc::class, 'phan_quyen_days', 'ma_giang_vien', 'ma_mon_hoc')
            ->withTimestamps();
    }
    public function lopHocs()
    {
        return $this->belongsToMany(LopHoc::class, 'phan_quyen_days', 'ma_giang_vien', 'ma_lop_hoc')->withPivot('ma_mon_hoc')->distinct();
    }
    public function deThis()
    {
        return $this->hasMany(DeThi::class, 'ma_giang_vien', 'ma_giang_vien');
    }
    public function baiKiemTras()
    {
        return $this->hasMany(BaiKiemTra::class, 'ma_giang_vien', 'ma_giang_vien');
    }
    public function lienHes()
    {
        return $this->hasMany(LienHe::class, 'ma_giang_vien', 'ma_giang_vien');
    }
}
