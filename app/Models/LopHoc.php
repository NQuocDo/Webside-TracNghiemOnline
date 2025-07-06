<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
    //
    protected $table = 'lop_hocs';

    /**
     * Tên cột khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'ma_lop_hoc';

    /**
     * Loại dữ liệu của khóa chính.
     * Eloquent mặc định là integer, nhưng ma_lop_hoc là varchar.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Cho biết ID có tự động tăng hay không.
     * Vì ma_lop_hoc được tạo bởi trigger, không phải tự động tăng.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'ma_lop_hoc', // Có thể để trống nếu trigger tự tạo
        'ten_lop_hoc',
        'ma_mon_hoc',
        'ma_giang_vien',
        'nganh', // Thêm cột 'nganh' từ hình ảnh bạn cung cấp
        'mo_ta',
        'nam_hoc',
        'hoc_ky',
        'trang_thai'
    ];

    /**
     * Các thuộc tính nên được chuyển đổi thành kiểu ngày tháng.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }
    public function sinhViens()
    {
        return $this->hasMany(SinhVien::class, 'ma_lop_hoc', localKey: 'ma_lop_hoc');
    }

    public function thongBaos()
    {
        return $this->hasMany(ThongBao::class, 'ma_lop_hoc', 'ma_lop_hoc');
    }

    public function phanQuyenDays()
    {
        return $this->hasMany(PhanQuyenDay::class, 'ma_lop_hoc', 'ma_lop_hoc');
    }
    public function giangViens()
    {
        return $this->belongsToMany(GiangVien::class, 'phan_quyen_days', 'ma_lop_hoc', 'ma_giang_vien')->withPivot('ma_mon_hoc')->distinct();
    }
}