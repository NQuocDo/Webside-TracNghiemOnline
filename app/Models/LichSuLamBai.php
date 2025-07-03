<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuLamBai extends Model
{
    //
    protected $table = 'lich_su_lam_bais';

    public $timestamps = true;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'ma_lich_su_lam_bai',
        'ma_bai_kiem_tra',
        'ma_cau_hoi',
        'ma_dap_an_chon',
    ];
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }
}
