<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiKiemTra extends Model
{
    use HasFactory;

    protected $table = 'bai_kiem_tras';
    protected $primaryKey = 'ma_bai_kiem_tra';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_bai_kiem_tra',
        'ma_de_thi',
        'ten_bai_kiem_tra',
        'trang_thai',
        'trang_thai_hien_thi',
        'thoi_gian_khoa',
        'ma_lop_hoc'
    ];

    public function deThi()
    {
        return $this->belongsTo(DeThi::class, 'ma_de_thi', 'ma_de_thi');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }

    public function giangVien()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_giang_vien', 'ma_nguoi_dung');
    }
    public function lichSuLamBais()
    {
        return $this->hasMany(LichSuLamBai::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop_hoc');
    }
    public function cauHoiTrongBaiKiemTra()
    {
        return $this->hasManyThrough(
            CauHoi::class,
            ChiTietBaiKiemTraVaCauHoi::class,
            'ma_bai_kiem_tra',          // FK trên bảng chi_tiet_bai_kiem_tra_va_cau_hois
            'ma_cau_hoi',               // FK trên bảng cau_hois
            'ma_bai_kiem_tra',          // Local key của bảng bai_kiem_tras
            'ma_chi_tiet_dtch'          // Ta cần lấy từ bảng trung gian qua chi_tiet_de_thi_va_cau_hois
        )->join('chi_tiet_de_thi_va_cau_hois', 'chi_tiet_bai_kiem_tra_va_cau_hois.ma_chi_tiet_dtch', '=', 'chi_tiet_de_thi_va_cau_hois.ma_chi_tiet_dtch')
            ->orderBy('chi_tiet_bai_kiem_tra_va_cau_hois.thu_tu');
    }
    public function chiTietCauHoi()
    {
        return $this->hasMany(ChiTietBaiKiemTraVaCauHoi::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra')
            ->orderBy('thu_tu');
    }
}

