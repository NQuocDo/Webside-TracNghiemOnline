<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    //
    protected $table='thong_baos';
    protected $primaryKey="ma_thong_bao";
    public $incrementing='false';
    protected $keyType='string';

    protected $fillable=[
        'ma_thong_bao',
        'tieu_de',
        'noi_dung',
        'nguoi_gui',
        'ngay_gui',
        'ma_lop_hoc'
    ];  

    public function giangVien(){
        return $this->belongsTo(GiangVien::class,'nguoi_gui','ma_giang_vien');
    }
    public function lopHoc()
{
    return $this->belongsTo(LopHoc::class, 'ma_lop_hoc', 'ma_lop_hoc');
}
}

