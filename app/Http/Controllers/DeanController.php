<?php

namespace App\Http\Controllers;


use App\Models\BaiKiemTra;
use App\Models\LopHoc;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\NguoiDung;
use App\Models\MonHoc;
use App\Models\GiangVien;
use App\Models\CauHoi;
use App\Models\PhanQuyenDay;
use App\Models\LichSuLamBai;
use App\Models\ChuongMonHoc;
use App\Models\SinhVienLopHoc;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DeanController extends Controller
{
    //
    public function hienThiTenTruongKhoa()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $monHoc = MonHoc::all();
            $giangVien = Auth::user()->where('vai_tro', 'giang_vien')->get();
            $sinhVien = Auth::user()->where('vai_tro', 'sinh_vien')->get();
            $baiKiemTra = BaiKiemTra::all();
            $lichSuLamBai = LichSuLamBai::select('ma_lich_su_lam_bai')
                ->groupBy('ma_lich_su_lam_bai')
                ->get();

            return view('dean.dashboard')
                ->with('user', $user)
                ->with('sinhVien', $sinhVien)
                ->with('monHoc', $monHoc)
                ->with('giangVien', $giangVien)
                ->with('baiKiemTra', $baiKiemTra)
                ->with('lichSuLamBai', $lichSuLamBai);
        }
        return redirect('/login');
    }
    public function hienThiThongTinTruongKhoa()
    {
        if (Auth::check()) {

            $user = Auth::user();

            return view('dean.dean_info')->with('user', $user);
        }
        return redirect('/login');
    }

    //Quản lý sinh viên
    public function hienThiDanhSachSinhVien(Request $request)
    {
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');
        $maLopHoc = $request->input('ma_lop_hoc');
        $query = SinhVien::with(['nguoiDung', 'lopHienTai.lopHoc', 'tatCaLop.lopHoc'])
            ->whereHas('nguoiDung', function ($query) use ($tuKhoaTimKiem) {
                $query->where('vai_tro', 'sinh_vien');

                if (!empty($tuKhoaTimKiem)) {
                    $query->where('ho_ten', 'like', '%' . $tuKhoaTimKiem . '%');
                }

                $query->where(function ($q) {
                    $q->where('trang_thai_tai_khoan', 'hoat_dong')
                        ->orWhere(function ($q2) {
                            $q2->where('trang_thai_tai_khoan', 'khong_hoat_dong')
                                ->where('updated_at', '>=', now()->subMonths(6));
                        });
                });
            });

        // Nếu có lọc lớp học
        if (!empty($maLopHoc)) {
            $query->whereHas('lopHienTai', function ($q) use ($maLopHoc) {
                $q->where('ma_lop_hoc', $maLopHoc);
            });
        }

        $danhSachSinhVien = $query->paginate(10);
        $danhSachLopHoc = LopHoc::where('trang_thai', 'hien')->get();

        return view('dean.student_management', [
            'danhSachSinhVien' => $danhSachSinhVien,
            'tuKhoaTimKiem' => $tuKhoaTimKiem,
            'maLopHoc' => $maLopHoc,
            'danhSachLopHoc' => $danhSachLopHoc
        ]);
    }

    public function xoaSinhVien($id)
    {
        $sinhVien = SinhVien::
            where('ma_sinh_vien', $id)
            ->first();
        if ($sinhVien) {
            $sinhVien->trang_thai = 'an';
            $sinhVien->save();
        }

        return redirect()->route('student_management')->with('success', 'Xoá sinh viên thành công');
    }
    public function thayDoiTrangThaiSinhVien($id)
    {

        $sinhVien = SinhVien::with('nguoiDung')->where('ma_nguoi_dung', $id)->first();

        $nguoiDung = $sinhVien->nguoiDung;
        $nguoiDung->trang_thai_tai_khoan = $nguoiDung->trang_thai_tai_khoan === 'hoat_dong' ? 'khong_hoat_dong' : 'hoat_dong';
        $nguoiDung->save();

        return response()->json([
            'success' => true,
            'new_status' => $nguoiDung->trang_thai_tai_khoan
        ]);
    }
    public function themNhieuSinhVienVaoLop(Request $request)
    {
        $request->validate([
            'ma_sinh_viens' => 'required|array',
            'ma_lop_hoc' => 'required|string',
            'hoc_ky' => 'required|numeric',
            'nam_hoc' => 'required|numeric',
        ]);
        $daThem = 0;
        $boQua = 0;
        foreach ($request->ma_sinh_viens as $maSV) {
            $daTonTai = SinhVienLopHoc::where('ma_sinh_vien', $maSV)
                ->where('ma_lop_hoc', $request->ma_lop_hoc)
                ->where('hoc_ky', $request->hoc_ky)
                ->where('nam_hoc', $request->nam_hoc)
                ->exists();

            if (!$daTonTai) {
                SinhVienLopHoc::create([
                    'ma_sv_lh' => 'MSVLH' . strtoupper(substr(uniqid(), -2)) . rand(10, 99),
                    'ma_sinh_vien' => $maSV,
                    'ma_lop_hoc' => $request->ma_lop_hoc,
                    'hoc_ky' => $request->hoc_ky,
                    'nam_hoc' => $request->nam_hoc,
                ]);
                $daThem++;
            } else {
                $boQua++;
            }
        }

        return redirect()->back()->with('success', 'Đã thêm sinh viên vào lớp học!');
    }
    public function capNhatThongTinSinhVien(Request $request, $ma_sinh_vien)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'mssv' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'lop_hien_tai' => 'nullable|string|exists:lop_hocs,ma_lop_hoc',
        ]);
        $sinhVien = SinhVien::with('nguoiDung', 'lopHienTai')->findOrFail($ma_sinh_vien);
        $sinhVien->mssv = $request->mssv;
        $sinhVien->save();
        if ($sinhVien->nguoiDung) {
            $nguoiDung = $sinhVien->nguoiDung;
            $nguoiDung->ho_ten = $request->ho_ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->gioi_tinh = $request->gioi_tinh;
            $nguoiDung->save();
        }

        if (!empty($request->lop_hien_tai)) {
            if ($sinhVien->lopHienTai) {
                $sinhVien->lopHienTai->ma_lop_hoc = $request->lop_hien_tai;
                $sinhVien->lopHienTai->save();
            } else {
                SinhVienLopHoc::create([
                    'ma_sv_lh' => 'MSVLH' . strtoupper(substr(uniqid(), -2)) . rand(10, 99),
                    'ma_sinh_vien' => $sinhVien->ma_sinh_vien,
                    'ma_lop_hoc' => $request->lop_hien_tai,
                    'hoc_ky' => now()->month <= 6 ? 2 : 1,
                    'nam_hoc' => now()->year,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Đã cập nhật thông tin sinh viên thành công!');
    }


    //Quản lý môn học
    public function hienThiMonHoc(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "truong_khoa") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }
        $query = MonHoc::query()->where('trang_thai', 'hien');
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('ten_mon_hoc', 'like', "%{$keyword}%");
        }

        $danhSachMonHoc = $query->paginate(5)->appends($request->all());

        return view('dean.subject_management')->with('danhSachMonHoc', $danhSachMonHoc);
    }

    public function themMonHoc(Request $request)
    {
        $messages = [
            'name_subject.required' => 'Tên môn học không được bỏ trống.',
            'credit_subject.required' => 'Số tín chỉ không được bỏ trống.',
            'semester_subject.required' => 'Học kỳ không được bỏ trống.',
            'difficulty_subject.required' => 'Vui lòng chọn độ khó.',
        ];
        $validator = Validator::make($request->all(), [
            'name_subject' => 'required|string|max:255',
            'credit_subject' => 'required|integer|min:1|max:10',
            'semester_subject' => 'required|integer|min:1|max:6',
            'description_subject' => 'nullable|string|max:1000',
            'criteria_subject' => 'nullable|string|max:500',
            'difficulty_subject' => 'required|in:Dễ,Trung bình,Khó',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        MonHoc::create([
            'ten_mon_hoc' => $request->input('name_subject'),
            'so_tin_chi' => $request->input('credit_subject'),
            'hoc_ky' => $request->input('semester_subject'),
            'mo_ta' => $request->input('description_subject'),
            'tieu_chi_ket_thuc_mon' => $request->input('criteria_subject'),
            'do_kho' => $request->input('difficulty_subject'),
        ]);

        return redirect()->route('subject_management')->with('success', 'Thêm môn học thành công');
    }

    public function xoaMonHoc($id)
    {
        $monHoc = MonHoc::where('ma_mon_hoc', $id)->first();

        if (!$monHoc) {
            return redirect()->back()->with('error', 'Không tìm thấy môn học.');
        }

        if ($monHoc->phanQuyenDays()->exists()) {
            return redirect()->back()->with('error', 'Không thể xoá môn học vì đã được phân quyền cho giảng viên.');
        }

        $monHoc->trang_thai = 'an';
        $monHoc->save();

        return redirect()->route('subject_management')->with('success', 'Xoá môn học thành công.');
    }
    public function suaMonHoc(Request $request, $id)
    {
        $validated = $request->validate([
            'name_subject' => 'required',
        ]);
        $monHoc = MonHoc::findOrFail($id);
        $monHoc->update([
            'ten_mon_hoc' => $request->name_subject,
            'so_tin_chi' => $request->credit_subject,
            'hoc_ky' => $request->semester_subject,
            'mo_ta' => $request->description_subject,
            'tieu_chi_ket_thuc_mon' => $request->criteria_subject,
            'do_kho' => $request->difficulty_subject,
        ]);

        return redirect()->back()->with('success', 'Cập nhật môn học thành công');
    }

    public function hienThiDanhSachChuong(Request $request)
    {
        $monHocId = $request->input('ma_mon_hoc');
        $keyword = $request->input('keyword');

        $danhSachMonHoc = MonHoc::where('trang_thai', 'hien')->get();

        $query = ChuongMonHoc::with('monHoc')->orderBy('ma_mon_hoc')->orderBy('so_thu_tu');

        if ($monHocId) {
            $query->where('ma_mon_hoc', $monHocId);
        }

        $danhSachChuong = $query->paginate(10);

        return view('dean.chapter_management', [
            'danhSachChuong' => $danhSachChuong,
            'danhSachMonHoc' => $danhSachMonHoc,
            'monHocDaChon' => $monHocId,
            'keyword' => $keyword,
        ]);
    }
    public function themChuong(Request $request)
    {
        $request->validate([
            'ma_mon_hoc' => 'required|exists:mon_hocs,ma_mon_hoc',
            'ten_chuong' => 'required|string|max:255',
            'so_thu_tu' => 'required|integer|min:1|max:20',
        ]);
        do {
            $maChuong = 'CH' . strtoupper(substr(uniqid(), -4)) . rand(10, 99);
        } while (ChuongMonHoc::where('ma_chuong', $maChuong)->exists());
        $trung = ChuongMonHoc::where('ma_mon_hoc', $request->ma_mon_hoc)
            ->where('so_thu_tu', $request->so_thu_tu)
            ->exists();

        if ($trung) {
            return redirect()->back()->withInput()->with('error', 'Chương số này đã tồn tại trong môn học được chọn.');
        }

        ChuongMonHoc::create([
            'ma_chuong' => $maChuong,
            'ten_chuong' => $request->ten_chuong,
            'so_thu_tu' => $request->so_thu_tu,
            'ma_mon_hoc' => $request->ma_mon_hoc,
        ]);

        return redirect()->route('chapter_management')->with('success', 'Thêm chương thành công!');
    }
    public function suaChuong(Request $request)
    {
        $request->validate([
            'ma_chuong' => 'required|exists:chuong_mon_hocs,ma_chuong',
            'ten_chuong' => 'required|string|max:255',
            'ma_mon_hoc' => 'required|exists:mon_hocs,ma_mon_hoc',
            'so_thu_tu' => 'required|integer|min:1|max:20',
        ]);

        $chuong = ChuongMonHoc::findOrFail($request->ma_chuong);
        $trung = ChuongMonHoc::where('ma_mon_hoc', $request->ma_mon_hoc)
            ->where('so_thu_tu', $request->so_thu_tu)
            ->where('ma_chuong', '!=', $request->ma_chuong)
            ->exists();

        if ($trung) {
            return redirect()->route('chapter_management')->with('error', 'Chương số này đã tồn tại trong môn học được chọn.');
        }

        $chuong->update([
            'ten_chuong' => $request->ten_chuong,
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'so_thu_tu' => $request->so_thu_tu,
        ]);

        return redirect()->route('chapter_management')->with('success', 'Cập nhật chương thành công!');
    }
    public function xoaChuong($id)
    {
        $chuong = ChuongMonHoc::withCount('cauHois')->find($id);

        if (!$chuong) {
            return redirect()->route('chapter_management')->with('error', 'Không tìm thấy chương để xóa.');
        }
        if ($chuong->cau_hois_count > 0) {
            return redirect()->route('chapter_management')->with('error', 'Không thể xóa chương vì đang có câu hỏi liên kết.');
        }

        try {
            $chuong->delete();
            return redirect()->route('chapter_management')->with('success', 'Xóa chương thành công!');
        } catch (\Exception $e) {
            return redirect()->route('chapter_management')->with('error', 'Xảy ra lỗi khi xóa: ' . $e->getMessage());
        }
    }


    //Quản lý giảng viên
    public function hienThiDanhSachGiangVien(Request $request)
    {
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');
        $thoiGianGioiHan = Carbon::now()->subMonths(6);
        $danhSachGiangVien = GiangVien::with(['nguoiDung', 'monHocs'])
            ->whereHas('nguoiDung', function ($query) use ($tuKhoaTimKiem, $thoiGianGioiHan) {
                $query->where('vai_tro', 'giang_vien')
                    ->where(function ($q) use ($thoiGianGioiHan) {
                        $q->where('trang_thai_tai_khoan', '!=', 'khong_hoat_dong')
                            ->orWhere(function ($subQ) use ($thoiGianGioiHan) {
                                $subQ->where('trang_thai_tai_khoan', 'khong_hoat_dong')
                                    ->where('updated_at', '>=', $thoiGianGioiHan);
                            });
                    });

                if ($tuKhoaTimKiem) {
                    $query->where('ho_ten', 'like', '%' . $tuKhoaTimKiem . '%');
                }
            })
            ->paginate(5);
        return view('dean.lecturer_list', [
            'danhSachGiangVien' => $danhSachGiangVien,
            'tuKhoaTimKiem' => $tuKhoaTimKiem,
        ]);
    }
    public function thayDoiTrangThaiGiangVien($id)
    {
        $nguoiDung = NguoiDung::where('ma_nguoi_dung', $id)->first();

        if (!$nguoiDung) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tài khoản người dùng.'
            ], 404);
        }
        if ($nguoiDung->vai_tro !== 'giang_vien') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản này không phải là giảng viên và không thể thay đổi trạng thái theo cách này.'
            ], 403);
        }
        $nguoiDung->trang_thai_tai_khoan = $nguoiDung->trang_thai_tai_khoan === 'hoat_dong' ? 'khong_hoat_dong' : 'hoat_dong';
        $nguoiDung->save();

        return response()->json([
            'success' => true,
            'new_status' => $nguoiDung->trang_thai_tai_khoan,
            'message' => 'Trạng thái tài khoản đã được cập nhật thành công.'
        ]);
    }
    public function doiMatKhauGiangVien(Request $request)
    {
        $request->validate([
            'ma_giang_vien' => 'required|exists:giangviens,ma_giang_vien',
            'mat_khau_moi' => 'required|min:6',
            'xac_nhan_mat_khau' => 'required|same:mat_khau_moi',
        ]);

        $giangVien = GiangVien::with('nguoiDung')
            ->where('ma_giang_vien', $request->ma_giang_vien)
            ->first();

        if (!$giangVien || !$giangVien->nguoiDung) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản giảng viên.');
        }

        $nguoiDung = $giangVien->nguoiDung;
        $nguoiDung->mat_khau = bcrypt($request->mat_khau_moi);
        $nguoiDung->save();

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công.');
    }

    //Quản lý ngân hàng câu hỏi
    public function hienThiDanhSachCauHoiTheoBoLoc(Request $request)
    {
        $maGiangVien = $request->input('ma_giang_vien');
        $maMonHoc = $request->input('ma_mon_hoc');
        $tuKhoaTimKiem = $request->input('search');

        $query = CauHoi::with(['monHoc', 'giangVien', 'dapAns']);

        if ($maGiangVien) {
            $query->where('ma_giang_vien', $maGiangVien);
        }

        if ($maMonHoc) {
            $query->where('ma_mon_hoc', $maMonHoc);
        }

        if ($tuKhoaTimKiem) {
            $query->where('noi_dung', 'like', '%' . $tuKhoaTimKiem . '%');
        }

        $danhSachCauHoi = $query->paginate(10);
        $danhSachGiangVien = NguoiDung::where('vai_tro', 'giang_vien')->get();
        $danhSachMonHoc = MonHoc::where('trang_thai', 'hien')->get();

        return view('dean.question_bank', compact('danhSachCauHoi', 'danhSachGiangVien', 'danhSachMonHoc'));
    }

    //Quản lý quyền dạy học
    public function hienthiQuyenGiangDay(Request $request)
    {
        $giangVienId = $request->input('giang_vien_id');
        $danhSachGiangVien = GiangVien::with('nguoiDung')->get();
        $danhSachLopHoc = LopHoc::where('trang_thai', 'hien')
            ->orderByDesc('nam_hoc')
            ->orderBy('hoc_ky')
            ->get();

        // Nếu đang lọc theo giảng viên
        if ($giangVienId) {
            $danhSachPhanQuyen = PhanQuyenDay::where('ma_giang_vien', $giangVienId)->paginate(5);
        } else {
            $danhSachPhanQuyen = PhanQuyenDay::paginate(5);
        }

        return view('dean.decentralization', [
            'danhSachLopHoc' => $danhSachLopHoc,
            'danhSachPhanQuyen' => $danhSachPhanQuyen,
            'giangVienDaChon' => $giangVienId,
            'danhSachGiangVien' => $danhSachGiangVien
        ]);
    }
    public function duLieuPhanQuyen(Request $request)
    {
        $namHoc = (int) $request->nam_hoc;
        $hocKy = (int) $request->hoc_ky;
        $lopHocs = LopHoc::where('nam_hoc', $namHoc)->get();
        $monHocs = MonHoc::where('hoc_ky', $hocKy)->get();
        $giangViens = GiangVien::with('nguoiDung')->get()->map(function ($gv) {
            return [
                'ma_giang_vien' => $gv->ma_giang_vien,
                'ho_ten' => $gv->nguoiDung->ho_ten ?? $gv->ma_giang_vien,
            ];
        });

        return response()->json([
            'lop_hocs' => $lopHocs,
            'mon_hocs' => $monHocs,
            'giang_viens' => $giangViens,
        ]);
    }
    public function themQuyenDayHoc(Request $request)
    {
        $request->validate([
            'ma_giang_vien' => 'required|string|exists:giangviens,ma_giang_vien',
            'ma_mon_hoc' => 'required|string|exists:mon_hocs,ma_mon_hoc',
            'ma_lop_hoc' => 'required|string|exists:lop_hocs,ma_lop_hoc',
        ]);

        $monHocDaCoGiangVien = PhanQuyenDay::where('ma_mon_hoc', $request->ma_mon_hoc)
            ->where('ma_lop_hoc', $request->ma_lop_hoc)
            ->where('ma_giang_vien', '!=', $request->ma_giang_vien)
            ->exists();

        if ($monHocDaCoGiangVien) {
            return redirect()->route('decentralization')->with('error', 'Môn học ở lớp này đã có giảng viên khác.');
        }

        $daTonTai = PhanQuyenDay::where('ma_giang_vien', $request->ma_giang_vien)
            ->where('ma_mon_hoc', $request->ma_mon_hoc)
            ->where('ma_lop_hoc', $request->ma_lop_hoc)
            ->exists();

        if ($daTonTai) {
            return redirect()->route('decentralization')->with('error', 'Quyền giảng dạy này đã tồn tại!');
        }
        PhanQuyenDay::create([
            'ma_giang_vien' => $request->ma_giang_vien,
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'ma_lop_hoc' => $request->ma_lop_hoc,
        ]);

        return redirect()->route('decentralization')->with('success', 'Thêm quyền giảng dạy thành công!');
    }

    public function xoaQuyenDayHoc($id)
    {
        $quyenDayHoc = PhanQuyenDay::find($id);

        if (!$quyenDayHoc) {
            return redirect()->back()->with('error', 'Không tìm thấy quyền dạy học.');
        }
        try {
            $lopHoc = LopHoc::where('ma_lop_hoc', $quyenDayHoc->ma_lop_hoc)->first();

            if ($lopHoc) {
                $lopHoc->ma_giang_vien = null;
                $lopHoc->ma_mon_hoc = null;
                $lopHoc->save();
            }
            $quyenDayHoc->delete();
            return redirect()->route('decentralization')->with('success', 'Xoá quyền dạy học thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể xoá quyền dạy học. Vui lòng kiểm tra ràng buộc dữ liệu.');
        }
    }
    public function suaPhanQuyen(Request $request, $id)
    {
        $phanQuyen = PhanQuyenDay::find($id);

        $phanQuyen->ma_giang_vien = $request->input('lecturer');
        $phanQuyen->ma_mon_hoc = $request->input('subject');
        $phanQuyen->ma_lop_hoc = $request->input('class');
        $phanQuyen->save();

        return redirect()->back()->with('success', 'Cập nhật quyền giảng dạy thành công.');
    }

    //Quản lý thêm người dùng
    public function hienThiThongTinLopHoc()
    {
        $danhSachLopHoc = LopHoc::where('trang_thai', 'hien')->get();
        return view('dean.add_user')->with('danhSachLopHoc', $danhSachLopHoc);
    }
    public function themNguoiDung(Request $request)
    {
        // Validate cơ bản
        $validated = $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
            'ho_ten' => 'required',
            'gioi_tinh' => 'required',
            'ngay_sinh' => 'required',
            'vai_tro' => 'required',
            'so_dien_thoai' => 'required',
            'mssv' => 'required_if:vai_tro,sinh_vien',
            'ma_lop' => 'required_if:vai_tro,sinh_vien',
            'hoc_ky' => 'required_if:vai_tro,sinh_vien',
            'nam_hoc' => 'required_if:vai_tro,sinh_vien|numeric|min:2000'
        ]);

        // Tạo mã người dùng
        $maNguoiDungMoi = 'ND' . strtoupper(substr(uniqid(), -4)) . rand(10, 99);
        // Tạo người dùng
        $nguoiDung = NguoiDung::create([
            'ma_nguoi_dung' => $maNguoiDungMoi,
            'email' => $validated['email'],
            'mat_khau' => bcrypt($validated['mat_khau']),
            'vai_tro' => $validated['vai_tro'],
            'ho_ten' => $validated['ho_ten'],
            'gioi_tinh' => $validated['gioi_tinh'],
            'ngay_sinh' => $validated['ngay_sinh'],
            'dia_chi' => $request->input('dia_chi'),
            'so_dien_thoai' => $request->input('so_dien_thoai'),
            'trang_thai_tai_khoan' => $request->input('trang_thai_tai_khoan', 'hoat_dong'),
        ]);

        if ($validated['vai_tro'] === 'giang_vien') {
            GiangVien::create([
                'ma_giang_vien' => 'GV' . now()->format('is') . rand(10, 99),
                'ma_nguoi_dung' => $nguoiDung->ma_nguoi_dung,
                'hoc_vi' => $request->input('hoc_vi'),
            ]);
        } elseif ($validated['vai_tro'] === 'sinh_vien') {
            $maSinhVienMoi = 'SV' . strtoupper(substr(uniqid(), -4)) . rand(10, 99);
            SinhVien::create([
                'ma_sinh_vien' => $maSinhVienMoi,
                'ma_nguoi_dung' => $nguoiDung->ma_nguoi_dung,
                'ma_lop_hoc' => $request->input('ma_lop'),
                'mssv' => $request->input('mssv'),
            ]);
            SinhVienLopHoc::create([
                'ma_sv_lh' => 'MSVLH' . strtoupper(substr(uniqid(), -2)) . rand(10, 99),
                'ma_sinh_vien' => $maSinhVienMoi,
                'ma_lop_hoc' => $validated['ma_lop'],
                'hoc_ky' => $validated['hoc_ky'],
                'nam_hoc' => $validated['nam_hoc'],
            ]);
        }

        return redirect()->route('add_user')->with('success', 'Thêm người dùng thành công.');
    }
    public function themNguoiDungExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'vai_tro' => 'required|in:sinh_vien,giang_vien',
        ]);
        $path = $request->file('file')->store('temp');

        $absolutePath = Storage::path($path);

        if (!file_exists($absolutePath)) {
            dd("File không tồn tại: " . $absolutePath);
        }

        $rows = SimpleExcelReader::create($absolutePath)->getRows();

        foreach ($rows as $row) {
            $data = [];

            foreach ($row as $key => $value) {
                $newKey = Str::of($key)
                    ->ascii()
                    ->lower()
                    ->replace([' ', '-'], '_')
                    ->__toString();
                $newKey = rtrim($newKey, '_');
                $data[$newKey] = $value;
            }

            if (NguoiDung::where('email', $data['email'])->exists()) {
                return redirect()->back()->with('error', 'Email đã bị trùng hoặc đã tồn tại. Vui lòng kiểm tra lại!');
            }
            if ($request->vai_tro === 'sinh_vien' && !empty($data['lop'])) {
                if (!LopHoc::where('ten_lop_hoc', $data['lop'])->exists()) {
                    return redirect()->back()->with('error', 'Lớp không tồn tại. Vui lòng kiểm tra lại!');
                }
            }

            $user = NguoiDung::create([
                'ma_nguoi_dung' => 'ND' . strtoupper(substr(uniqid(), -4)) . rand(10, 99),
                'ho_ten' => $data['ho_ten'],
                'email' => $data['email'],
                'mat_khau' => Hash::make($data['mat_khau']),
                'gioi_tinh' => $data['gioi_tinh'] ?? null,
                'ngay_sinh' => $data['ngay_sinh'] ?? null,
                'dia_chi' => $data['dia_chi'] ?? null,
                'so_dien_thoai' => $data['so_dien_thoai'],
                'vai_tro' => $request->vai_tro,
            ]);

            if ($request->vai_tro === 'sinh_vien') {
                $maLop = LopHoc::where('ten_lop_hoc', $data['lop'])->first();

                if ($maLop) {
                    $maSinhVienMoi = 'SV' . strtoupper(substr(uniqid(), -4)) . rand(10, 99);

                    SinhVien::create([
                        'ma_sinh_vien' => $maSinhVienMoi,
                        'ma_nguoi_dung' => $user->ma_nguoi_dung,
                        'mssv' => $data['ma_so_sinh_vien'] ?? null,
                    ]);
                    SinhVienLopHoc::create([
                        'ma_sv_lh' => 'MSVLH' . strtoupper(substr(uniqid(), -2)) . rand(10, 99),
                        'ma_sinh_vien' => $maSinhVienMoi,
                        'ma_lop_hoc' => $maLop->ma_lop_hoc,
                        'hoc_ky' => $data['hoc_ky'] ?? 1,
                        'nam_hoc' => $data['nam_hoc'] ?? date('Y'),
                    ]);
                }
            } else {
                GiangVien::create([
                    'ma_giang_vien' => 'GV' . strtoupper(substr(uniqid(), -4)) . rand(10, 99),
                    'ma_nguoi_dung' => $user->ma_nguoi_dung,
                    'hoc_vi' => $data['hoc_vi'] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Import thành công!');
    }

    //Quản lý lớp học
    public function hienThiLopHoc(Request $request)
    {
        $query = LopHoc::query();
        $query->where('trang_thai', 'hien');
        $fiveYearsAgo = Carbon::now()->subYears(5);
        $query->where('created_at', '>=', $fiveYearsAgo);
        if ($request->filled('nam_hoc')) {
            $query->where('nam_hoc', $request->nam_hoc);
        }
        if ($request->filled('ten_lop_hoc')) {
            $query->where('ten_lop_hoc', 'like', '%' . $request->ten_lop_hoc . '%');
        }
        $danhSachLopHoc = $query->orderBy('ten_lop_hoc', 'asc')->paginate(5);

        return view('dean.add_class')->with('danhSachLopHoc', $danhSachLopHoc);
    }
    public function themLopHoc(Request $request)
    {
        $namHienTai = now()->year;
        $dsNamChoPhep = array_merge(
            range($namHienTai - 3 + 1, $namHienTai),
            range($namHienTai + 1, $namHienTai + 3)
        );
        $validator = Validator::make($request->all(), [
            'ten_lop_hoc' => 'required|string|max:255',
            'nam_hoc' => [
                'required',
                'integer',
                Rule::in($dsNamChoPhep),
            ],
            'hoc_ky' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5, 6]),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm lớp học. Vui lòng kiểm tra lại thông tin.');
        }

        try {
            $namHoc = (int) $request->nam_hoc;
            $nienKhoa = (int) substr($namHoc, -2);
            $hocKy = (int) $request->hoc_ky;

            LopHoc::create([
                'ten_lop_hoc' => $request->ten_lop_hoc,
                'nam_hoc' => $namHoc,
                'nien_khoa' => $nienKhoa,
                'hoc_ky' => $hocKy,
            ]);

            return redirect()->route('add_class')->with('success', 'Thêm lớp học thành công!');

        } catch (\Exception $e) {
            return redirect()->route('add_class')->withInput()->with('error', 'Đã xảy ra lỗi khi thêm lớp học: ' . $e->getMessage());
        }
    }
    public function xoaLopHoc($id)
    {
        $lopHoc = LopHoc::find($id);
        $sinhVien = SinhVien::where('ma_lop_hoc', $id)->exists();
        if (!$lopHoc) {
            return redirect()->back()->with('error', 'Không tìm thấy lớp học.');
        }
        if ($sinhVien) {
            return redirect()->back()->with('error', 'Không thể xoá lớp học vì đang được sử dụng trong hệ thống.');
        }
        $lopHoc->trang_thai = 'an';
        $lopHoc->save();

        return redirect()->route('add_class')->with('success', 'Xoá lớp học thành công');
    }
    public function suaLopHoc(Request $request, $id)
    {
        $validated = $request->validate([
            'ten_lop_hoc' => 'required|string|max:255',
            'nam_hoc' => 'required|integer|min:1900|max:2100',
            'hoc_ky' => 'required|integer|min:1|max:10',
        ]);

        $lopHoc = LopHoc::find($id);
        $lopHoc->update($validated);

        return redirect()->back()->with('success', 'Cập nhật lớp học thành công!');
    }

    //Quản lý thống kê
    public function hienThiThongKe()
    {
        $diemTrungBinhTheoMon = DB::table('bang_diems as d')
            ->join('mon_hocs as mh', 'd.ma_mon_hoc', '=', 'mh.ma_mon_hoc')
            ->select('mh.ten_mon_hoc', DB::raw('AVG(d.diem_so) as diem_tb'))
            ->groupBy('mh.ten_mon_hoc')
            ->get();
        $thongKeCauHoi = DB::table('cau_hois as ch')
            ->join('giangviens as gv', 'ch.ma_giang_vien', '=', 'gv.ma_giang_vien')
            ->join('nguoidungs as nd', 'gv.ma_nguoi_dung', '=', 'nd.ma_nguoi_dung')
            ->select(
                'gv.ma_giang_vien',
                'nd.ho_ten',
                DB::raw('COUNT(ch.ma_cau_hoi) as so_cau_hoi')
            )
            ->groupBy('gv.ma_giang_vien', 'nd.ho_ten')
            ->orderByDesc('so_cau_hoi')
            ->get();

        return view('dean.department_statis', [
            'thongKeDiem' => $diemTrungBinhTheoMon,
            'thongKeCauHoi' => $thongKeCauHoi
        ]);
    }
}

