<?php

namespace App\Http\Controllers;

use App\Models\BaiKiemTra;
use App\Models\BangDiem;
use App\Models\DapAn;
use App\Models\DeThi;
use App\Models\LienHe;
use App\Models\SinhVien;
use App\Models\LichSuLamBai;
use App\Models\CauHoi;
use App\Models\GiangVien;
use App\Models\SinhVienLopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{



    public function layDanhSachMonHoc()
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;

        $sinhVien = SinhVien::with('nguoiDung')->where('ma_nguoi_dung', $maNguoiDung)->first();

        if (!$sinhVien) {
            return view('student.dashboard')->with(['monDangHoc' => collect()]);
        }

        $maSinhVien = $sinhVien->ma_sinh_vien;

        // 1. Lấy toàn bộ lớp mà sinh viên đã học
        $lopSinhVien = DB::table('sinh_vien_lop_hoc')
            ->where('ma_sinh_vien', $maSinhVien)
            ->pluck('ma_lop_hoc');

        if ($lopSinhVien->isEmpty()) {
            return view('student.dashboard')->with(['monDangHoc' => collect()]);
        }

        // 2. Lấy tất cả môn học mà sinh viên được dạy bởi giảng viên (qua lớp của SV)
        $monDangHoc = DB::table('phan_quyen_days as pqd')
            ->join('mon_hocs as mh', 'mh.ma_mon_hoc', '=', 'pqd.ma_mon_hoc')
            ->join('giangviens as gv', 'gv.ma_giang_vien', '=', 'pqd.ma_giang_vien')
            ->join('nguoidungs as nd', 'nd.ma_nguoi_dung', '=', 'gv.ma_nguoi_dung')
            ->join('lop_hocs as lh', 'lh.ma_lop_hoc', '=', 'pqd.ma_lop_hoc')
            ->whereIn('pqd.ma_lop_hoc', $lopSinhVien)
            ->select(
                'pqd.ma_mon_hoc',
                'mh.ten_mon_hoc',
                'mh.hoc_ky',
                'pqd.ma_giang_vien',
                'nd.ho_ten as ten_giang_vien',
                'nd.hinh_anh',
                'pqd.ma_lop_hoc',
                'lh.ten_lop_hoc',
                'lh.nam_hoc',
                'lh.hoc_ky'
            )
            ->distinct()
            ->get();

        return view('student.dashboard')->with([
            'monDangHoc' => $monDangHoc
        ]);
    }

    //hàm hiển thị danh sách bài kiêm tra trang exam_list
    public function hienThiDanhSachBaiKiemTra()
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;

        $sinhVien = SinhVien::where('ma_nguoi_dung', $maNguoiDung)->firstOrFail();
        $maSinhVien = $sinhVien->ma_sinh_vien;

        // 1. Lấy tất cả lớp mà sinh viên đã/đang học
        $cacLopCuaSinhVien = DB::table('sinh_vien_lop_hoc')
            ->where('ma_sinh_vien', $maSinhVien)
            ->pluck('ma_lop_hoc');

        if ($cacLopCuaSinhVien->isEmpty()) {
            return view('student.exam_list', ['danhSachBaiKiemTra' => collect()]);
        }

        // 2. Lấy danh sách quyền giảng viên được dạy ở lớp sinh viên đã học
        $phanQuyen = DB::table('phan_quyen_days')
            ->whereIn('ma_lop_hoc', $cacLopCuaSinhVien)
            ->select('ma_lop_hoc', 'ma_mon_hoc', 'ma_giang_vien')
            ->distinct()
            ->get();

        // Nếu không có quyền nào phù hợp => không có bài kiểm tra nào được phép xem
        if ($phanQuyen->isEmpty()) {
            return view('student.exam_list', ['danhSachBaiKiemTra' => collect()]);
        }

        // 3. Truy vấn bài kiểm tra khớp phân quyền và sinh viên chưa làm
        $danhSachBaiKiemTra = DB::table('bai_kiem_tras as bkt')
            ->join('de_this as dt', 'bkt.ma_de_thi', '=', 'dt.ma_de_thi')
            ->join('mon_hocs as mh', 'dt.ma_mon_hoc', '=', 'mh.ma_mon_hoc')
            ->join('giangviens as gv', 'dt.ma_giang_vien', '=', 'gv.ma_giang_vien')
            ->join('nguoidungs as nd', 'gv.ma_nguoi_dung', '=', 'nd.ma_nguoi_dung')
            ->leftJoin('bang_diems as bd', function ($join) use ($maSinhVien) {
                $join->on('bd.ma_bai_kiem_tra', '=', 'bkt.ma_bai_kiem_tra')
                    ->where('bd.ma_sinh_vien', '=', $maSinhVien);
            })
            ->where('bkt.trang_thai', 'mo')
            ->whereNull('bd.ma_bang_diem')
            ->whereIn('bkt.ma_lop_hoc', $phanQuyen->pluck('ma_lop_hoc'))
            ->whereIn('dt.ma_mon_hoc', $phanQuyen->pluck('ma_mon_hoc'))
            ->whereIn('dt.ma_giang_vien', $phanQuyen->pluck('ma_giang_vien'))
            ->select(
                'bkt.ma_bai_kiem_tra',
                'bkt.ten_bai_kiem_tra',
                'mh.ten_mon_hoc',
                'nd.ho_ten as ten_giang_vien',
                'bkt.ma_lop_hoc',
                'dt.so_luong_cau_hoi',
                'dt.thoi_gian_lam_bai'
            )
            ->distinct()
            ->get();

        return view('student.exam_list', compact('danhSachBaiKiemTra'));
    }


    public function hienThiBaiKiemTraTheoId($id)
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;

        $sinhVien = SinhVien::with('nguoiDung')->where('ma_nguoi_dung', $maNguoiDung)->firstOrFail();
        $maSinhVien = $sinhVien->ma_sinh_vien;

        $baiKiemTra = BaiKiemTra::findOrFail($id);

        // Lấy danh sách các lớp mà sinh viên đã hoặc đang học
        $cacMaLopSinhVien = DB::table('sinh_vien_lop_hoc')
            ->where('ma_sinh_vien', $maSinhVien)
            ->pluck('ma_lop_hoc')
            ->toArray();

        // Nếu bài kiểm tra không thuộc lớp của sinh viên => không cho vào
        if (!in_array($baiKiemTra->ma_lop_hoc, $cacMaLopSinhVien)) {
            abort(403, 'Bạn không có quyền truy cập bài kiểm tra này.');
        }

        $deThis = DeThi::findOrFail($baiKiemTra->ma_de_thi);

        $cauHoiList = DB::table('chi_tiet_bai_kiem_tra_va_cau_hois as ct')
            ->join('cau_hois as ch', 'ct.ma_cau_hoi', '=', 'ch.ma_cau_hoi')
            ->where('ct.ma_bai_kiem_tra', $id)
            ->select('ch.*')
            ->get();

        foreach ($cauHoiList as $cauHoi) {
            $cauHoi->dap_an = DB::table('dap_ans')
                ->where('ma_cau_hoi', $cauHoi->ma_cau_hoi)
                ->select('ma_dap_an', 'noi_dung', 'ket_qua_dap_an')
                ->get();
        }

        return view('student.exam', [
            'baiKiemTra' => $baiKiemTra,
            'cauHoiList' => $cauHoiList,
            'deThis' => $deThis,
            'hoTenSinhVien' => $sinhVien->nguoiDung->ho_ten,
            'ma_sinh_vien' => $sinhVien->ma_sinh_vien,
        ]);
    }



    //hàm tính điểm trang exam
    public function tinhDiemKiemTra(Request $request)
    {
        $data = json_decode($request->input('bai_lam_json'), true);
        $deThi = $data['ma_de_thi'] ?? null;
        $monHoc = $data['ma_mon_hoc'] ?? null;
        $maNguoiDung = Auth::user()->ma_nguoi_dung;
        $sinhVien = SinhVien::with('nguoiDung')->where('ma_nguoi_dung', $maNguoiDung)->first();
        $maSinhVien = $data['ma_sinh_vien'] ?? null;
        $baiLam = $data['bai_lam'] ?? [];

        if ($maSinhVien) {
            $maSinhVien = $sinhVien->ma_sinh_vien;
        } else {
            return back()->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        $soCauDung = 0;
        $tongSoCau = count($baiLam);
        $maLichSu = 'LS' . now()->format('His') . rand(10, 99);
        $maBaiKiemTra = BaiKiemTra::where('ma_de_thi', $deThi)->value('ma_bai_kiem_tra');


        foreach ($baiLam as $item) {
            $maCauHoi = $item['ma_cau_hoi'];
            $maDapAnChon = $item['ma_dap_an'];

            $dapAnDung = DapAn::where('ma_cau_hoi', $maCauHoi)
                ->where('ket_qua_dap_an', 1)
                ->pluck('ma_dap_an')->toArray();
            $isCorrect = count($dapAnDung) === count($maDapAnChon)
                && !array_diff($dapAnDung, $maDapAnChon)
                && !array_diff($maDapAnChon, $dapAnDung);

            if ($isCorrect) {
                $soCauDung++;
            }
            foreach ($maDapAnChon as $maDapAn) {
                LichSuLamBai::create([
                    'ma_lich_su_lam_bai' => $maLichSu,
                    'ma_bai_kiem_tra' => $maBaiKiemTra,
                    'ma_cau_hoi' => $maCauHoi,
                    'ma_dap_an_chon' => $maDapAn,
                ]);
            }
        }
        $diem = $tongSoCau > 0 ? round(($soCauDung / $tongSoCau) * 10, 2) : 0;
        $soCauSai = $tongSoCau - $soCauDung;
        BangDiem::create([
            'ma_bang_diem' => 'MBD' . now()->format('is') . rand(10, 99),
            'ma_sinh_vien' => $maSinhVien,
            'ma_mon_hoc' => $monHoc,
            'ma_de_thi' => $deThi,
            'diem_so' => $diem,
            'so_cau_dung' => $soCauDung,
            'so_cau_sai' => $soCauSai,
            'ma_bai_kiem_tra' => $maBaiKiemTra,
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Đã hoàn thành bài kiểm tra. Vui lòng xem kết quả!');
    }
    //trang history
    public function hienThiBangDiemSinhVien()
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;

        $sinhVien = SinhVien::with('nguoiDung')
            ->where('ma_nguoi_dung', $maNguoiDung)
            ->firstOrFail();

        $bangDiems = BangDiem::with([
            'baiKiemTra.deThi.monHoc',
            'baiKiemTra.deThi.giangVien.nguoiDung'
        ])
            ->where('ma_sinh_vien', $sinhVien->ma_sinh_vien)
            ->orderByDesc('created_at')
            ->get();

        return view('student.history', compact('bangDiems', 'sinhVien'));
    }
    //trang info
    public function hienThiThongTinSinhVien()
    {
        if (Auth::check()) {

            $user = Auth::user();

            return view('student.info')->with('user', $user);
        }
        return redirect('/login');
    }
    //cập nhật thông tin info
    public function luuThongTinSinhVien(Request $request)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'dia_chi' => 'required|string|max:255',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'ngay_sinh' => 'required|date',
        ]);

        $user = Auth::user();
        $user->ho_ten = $validated['ho_ten'];
        $user->so_dien_thoai = $validated['so_dien_thoai'];
        $user->dia_chi = $validated['dia_chi'];
        $user->gioi_tinh = $validated['gioi_tinh'];
        $user->ngay_sinh = $validated['ngay_sinh'];
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
    //trang changepassword
    public function doiMatKhauSinhVien(Request $request)
    {
        $validated = $request->validate([
            'mat_khau_cu' => 'required',
            'mat_khau_moi' => 'required|string|min:6',
            'xac_nhan_mat_khau' => 'required|same:mat_khau_moi',
        ], [
            'mat_khau_cu.required' => 'Vui lòng nhập mật khẩu cũ.',
            'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'xac_nhan_mat_khau.required' => 'Vui lòng xác nhận mật khẩu.',
            'xac_nhan_mat_khau.same' => 'Xác nhận mật khẩu không trùng khớp.',
        ]);

        $user = Auth::user();
        if (!Hash::check($validated['mat_khau_cu'], $user->mat_khau)) {
            return back()->with('error', 'Mật khẩu cũ không đúng!');
        }
        $user->mat_khau = bcrypt($validated['mat_khau_moi']);
        $user->save();

        return redirect()->back()->with('success', 'Thay đổi mật khẩu thành công!');
    }
    //trang announce
    public function danhSachThongBaoSinhVien()
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;

        $sinhVien = SinhVien::where('ma_nguoi_dung', $maNguoiDung)->first();
        if (!$sinhVien) {
            return view('student.announce')->with('thongBaos', collect());
        }

        // Lấy danh sách mã lớp học từ bảng trung gian sinh_vien_lop_hoc
        $danhSachMaLopHoc = DB::table('sinh_vien_lop_hoc')
            ->where('ma_sinh_vien', $sinhVien->ma_sinh_vien)
            ->pluck('ma_lop_hoc');

        $thongBaos = DB::table('thong_baos as tb')
            ->leftJoin('giangviens as gv', 'tb.nguoi_gui', '=', 'gv.ma_giang_vien')
            ->leftJoin('nguoidungs as nd', 'gv.ma_nguoi_dung', '=', 'nd.ma_nguoi_dung')
            ->leftJoin('phan_quyen_days as pq', function ($join) {
                $join->on('pq.ma_lop_hoc', '=', 'tb.ma_lop_hoc')
                    ->on('pq.ma_giang_vien', '=', 'tb.nguoi_gui');
            })
            ->leftJoin('mon_hocs as mh', 'pq.ma_mon_hoc', '=', 'mh.ma_mon_hoc')
            ->whereIn('tb.ma_lop_hoc', $danhSachMaLopHoc)
            ->select('tb.*', 'nd.ho_ten as ten_giang_vien', 'mh.ten_mon_hoc')
            ->orderByDesc('tb.ngay_gui')
            ->get()
            ->unique('ma_thong_bao')
            ->values();

        return view('student.announce')->with('thongBaos', $thongBaos);
    }

    //trang exam_detail
    public function hienThiLichSuLamBai($maBaiKiemTra)
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;
        $sinhVien = SinhVien::where('ma_nguoi_dung', $maNguoiDung)->first();

        $lichSu = DB::table('lich_su_lam_bais')
            ->join('bang_diems', 'lich_su_lam_bais.ma_bai_kiem_tra', '=', 'bang_diems.ma_bai_kiem_tra')
            ->where('bang_diems.ma_sinh_vien', $sinhVien->ma_sinh_vien)
            ->where('lich_su_lam_bais.ma_bai_kiem_tra', $maBaiKiemTra)
            ->select('lich_su_lam_bais.*')
            ->get();

        if ($lichSu->isEmpty()) {
            return back()->with('error', 'Không tìm thấy dữ liệu lịch sử.');
        }

        $maCauHoiList = $lichSu->pluck('ma_cau_hoi')->unique()->toArray();

        $cauHoiList = CauHoi::whereIn('ma_cau_hoi', $maCauHoiList)
            ->with('dapAns')
            ->get();

        $dapAnChon = [];
        foreach ($lichSu as $item) {
            $dapAnChon[$item->ma_cau_hoi][] = $item->ma_dap_an_chon;
        }

        $dapAnDung = [];
        foreach ($cauHoiList as $cauHoi) {
            $dapAnDung[$cauHoi->ma_cau_hoi] = $cauHoi->dapAns->where('ket_qua_dap_an', 1)->pluck('ma_dap_an')->toArray();
        }

        return view('student.exam_detail', [
            'ma_bai_kiem_tra' => $maBaiKiemTra,
            'sinhVien' => $sinhVien,
            'cauHoiList' => $cauHoiList,
            'dapAnChon' => $dapAnChon,
            'dapAnDung' => $dapAnDung,
        ]);
    }
    //gửi liên hệ 
    public function guiLienHe(Request $request)
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;
        $maSinhVien = SinhVien::where('ma_nguoi_dung', $maNguoiDung)->first();
        $validated = $request->validate([
            'errorTitle' => 'required|string|min:5|max:255',
            'errorContent' => 'required|string|min:10|max:255',
            'teacherEmail' => 'required|email|max:255|exists:nguoidungs,email'
        ], [
            'errorTitle.required' => 'Không để trống tiêu đề',
            'errorTitle.min' => 'Tiêu đề phải có ít nhất 5 ký tự',
            'errorContent.required' => 'Không để trống nội dung',
            'errorContent.min' => 'Nội dung phải có ít nhất 10 ký tự',
            'teacherEmail.required' => 'Không để trống email giảng viên',
            'teacherEmail.email' => 'Email không hợp lệ',
            'teacherEmail.exists' => 'Email giảng viên không tồn tại trong hệ thống',
        ]);

        $giangVien = Auth::user()::where('email', $validated['teacherEmail'])->first();
        if (!$giangVien) {
            return back()->withErrors([
                'teacherEmail' => 'Không tìm thấy giảng viên với email này.'
            ])->withInput();
        }
        $nguoiDung = Auth::user()::where('email', $validated['teacherEmail'])->first();
        $maGiangVien = GiangVien::where('ma_nguoi_dung', $nguoiDung->ma_nguoi_dung)->first();
        $maLienHe = 'LH' . now()->format('is') . rand(10, 99);
        LienHe::create([
            'ma_lien_he' => $maLienHe,
            'tieu_de' => $validated['errorTitle'],
            'noi_dung' => $validated['errorContent'],
            'ma_sinh_vien' => $maSinhVien->ma_sinh_vien,
            'ma_giang_vien' => $maGiangVien->ma_giang_vien,
        ]);

        return redirect()->back()->with('success', 'Gửi liên hệ thành công');
    }


}
