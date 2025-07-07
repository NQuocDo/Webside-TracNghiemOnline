<?php

namespace App\Models;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NguoiDung extends Authenticatable
{
    use HasFactory;
    //
    protected $table = 'nguoidungs';
    protected $primaryKey = 'ma_nguoi_dung';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_nguoi_dung',
        'email',
        'mat_khau',
        'vai_tro',
        'ho_ten',
        'gioi_tinh',
        'ngay_sinh',
        'dia_chi',
        'so_dien_thoai',
        'trang_thai_tai_khoan'
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'mat_khau' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }
    public function giangVien()
    {
        return $this->hasOne(GiangVien::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
        public function sinhVien()
    {
        return $this->hasOne(SinhVien::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

}
