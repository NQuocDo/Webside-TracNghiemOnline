<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DapAn extends Model
{
    //
    use HasFactory;

    protected $table = 'dap_ans'; // Tên bảng của bạn
    protected $primaryKey = 'ma_dap_an'; // Khóa chính của bảng\
    protected $keyType = 'string';
    public $incrementing = false; 

    protected $fillable = [
        'ma_dap_an',
        'noi_dung',
        'ket_qua_dap_an',
        'ma_cau_hoi'
    ];

    // Quan hệ nhiều-một: Một đáp án thuộc về một câu hỏi
    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'ma_cau_hoi', 'ma_cau_hoi');
    }
}
