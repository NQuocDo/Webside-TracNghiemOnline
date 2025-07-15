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
use App\Models\ThongBao;
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
    public function hienThiDanhSachQuanLySinhVien(Request $request)
    {
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');
        $maLopHoc = $request->input('ma_lop_hoc');
        $loaiLopHoc = $request->input('loai_lop');
        $query = SinhVien::with(['nguoiDung', 'lopHienTai.lopHoc', 'sinhVienLopHocs.lopHoc'])
            ->where('trang_thai', 'hien')
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
        if (!empty($loaiLopHoc)) {
            $query->whereHas('lopHienTai.lopHoc', function ($q) use ($loaiLopHoc) {
                $q->where('loai_lop', $loaiLopHoc);
            });
        }
        if (!empty($maLopHoc)) {
            $query->whereHas('lopHienTai', function ($q) use ($maLopHoc) {
                $q->where('ma_lop_hoc', $maLopHoc);
            });
        }

        $danhSachSinhVien = $query->paginate(10)->appends([
            'tu_khoa_tim_kiem' => $tuKhoaTimKiem,
            'ma_lop_hoc' => $maLopHoc,
        ]);
        $danhSachLopHoc = LopHoc::where('trang_thai', 'hien')->get();
        $danhSachMonHoc = MonHoc::where('trang_thai', 'hien')->get();

        return view('dean.student_management', [
            'danhSachSinhVien' => $danhSachSinhVien,
            'tuKhoaTimKiem' => $tuKhoaTimKiem,
            'maLopHoc' => $maLopHoc,
            'danhSachLopHoc' => $danhSachLopHoc,
            'danhSachMonHoc' => $danhSachMonHoc
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
    public function layMonTheoLop(Request $request)
    {
        $maLopHoc = $request->ma_lop_hoc;
        // Lấy danh sách môn học từ phân quyền
        $monHocs = DB::table('phan_quyen_days')
            ->join('mon_hocs', 'phan_quyen_days.ma_mon_hoc', '=', 'mon_hocs.ma_mon_hoc')
            ->where('phan_quyen_days.ma_lop_hoc', $maLopHoc)
            ->select('mon_hocs.ma_mon_hoc', 'mon_hocs.ten_mon_hoc')
            ->distinct()
            ->get();

        return response()->json($monHocs);
    }
    public function themNhieuSinhVienVaoLop(Request $request)
    {
        $request->validate([
            'ma_sinh_viens' => 'required|array',
            'ma_lop_hoc' => 'required|string',
            'hinh_thuc' => 'required|in:chinh_thuc,hoc_ghep,nang_cao,chuyen_lop',
            'ma_mon_hoc' => 'required_if:hinh_thuc,hoc_ghep'
        ]);

        $daThem = 0;
        $boQua = 0;

        $lopHoc = LopHoc::where('ma_lop_hoc', $request->ma_lop_hoc)->first();
        $namHoc = 2000 + (int) $lopHoc->nien_khoa;
        $hinhThuc = $request->hinh_thuc;
        $maMonHoc = $request->ma_mon_hoc ?? null;

        // Lấy năm và học kỳ hiện tại
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $currentHocKy = $currentMonth <= 4 ? 1 : ($currentMonth <= 8 ? 2 : 3);

        foreach ($request->ma_sinh_viens as $maSV) {
            $daTonTai = SinhVienLopHoc::where('ma_sinh_vien', $maSV)
                ->where('ma_lop_hoc', $request->ma_lop_hoc)
                ->where('danh_sach_mon_hoc', 'like', "%$maMonHoc%")
                ->exists();

            if ($daTonTai) {
                $boQua++;
                continue;
            }

            // Nếu là chuyển lớp hoặc nâng cao, set các lớp chính thức hiện tại về 0
            if (in_array($hinhThuc, ['nang_cao', 'chuyen_lop'])) {
                SinhVienLopHoc::where('ma_sinh_vien', $maSV)
                    ->where('is_hien_tai', 1)
                    ->where(function ($query) {
                        $query->where('hinh_thuc', 'chinh_thuc')
                            ->orWhereNull('hinh_thuc');
                    })
                    ->update(['is_hien_tai' => 0]);
            }

            // Xác định học kỳ
            $hocKy = 4; // mặc định nếu không có
            if ($hinhThuc === 'hoc_ghep' && $maMonHoc) {
                $mon = MonHoc::where('ma_mon_hoc', $maMonHoc)->first();
                if ($mon && $mon->hoc_ky) {
                    $hocKy = $mon->hoc_ky;
                }
            }

            // Xác định is_hien_tai cho bản ghi sắp thêm
            $isHienTai = ($namHoc == $currentYear && $hocKy == $currentHocKy) ? 1 : 0;

            do {
                $maSvLh = 'MSVLH' . strtoupper(Str::random(5));
            } while (SinhVienLopHoc::where('ma_sv_lh', $maSvLh)->exists());

            // Thêm bản ghi
            SinhVienLopHoc::create([
                'ma_sv_lh' => $maSvLh,
                'ma_sinh_vien' => $maSV,
                'ma_lop_hoc' => $request->ma_lop_hoc,
                'danh_sach_mon_hoc' => $maMonHoc,
                'nam_hoc' => $namHoc,
                'hoc_ky' => $hocKy,
                'is_hien_tai' => $isHienTai,
                'hinh_thuc' => $hinhThuc,
            ]);

            $daThem++;

            // Nếu là lớp học ghép và được đánh dấu là hiện tại → reset các lớp học ghép khác về 0
            if ($hinhThuc === 'hoc_ghep' && $isHienTai === 1) {
                SinhVienLopHoc::where('ma_sinh_vien', $maSV)
                    ->where('hinh_thuc', 'hoc_ghep')
                    ->where('is_hien_tai', 1)
                    ->where(function ($query) use ($namHoc, $hocKy, $request) {
                        $query->where('ma_lop_hoc', '!=', $request->ma_lop_hoc)
                            ->orWhere('nam_hoc', '!=', $namHoc)
                            ->orWhere('hoc_ky', '!=', $hocKy);
                    })
                    ->update(['is_hien_tai' => 0]);
            }
        }

        return redirect()->back()->with('success', "Đã thêm $daThem sinh viên vào lớp mới, bỏ qua $boQua sinh viên đã tồn tại.");
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

        if ($user->vai_tro !== "can_bo") {
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
            ->orderByDesc('nien_khoa')
            ->get();
        $danhSachMonHoc = MonHoc::orderBy('ten_mon_hoc')->get();

        // Nếu đang lọc theo giảng viên
        $danhSachPhanQuyen = PhanQuyenDay::when($giangVienId, function ($query) use ($giangVienId) {
            return $query->where('ma_giang_vien', $giangVienId);
        })
            ->paginate(5)
            ->appends($request->only('giang_vien_id'));

        return view('dean.decentralization', [
            'danhSachLopHoc' => $danhSachLopHoc,
            'danhSachPhanQuyen' => $danhSachPhanQuyen,
            'giangVienDaChon' => $giangVienId,
            'danhSachGiangVien' => $danhSachGiangVien,
            'danhSachMonHoc' => $danhSachMonHoc
        ]);
    }
    public function duLieuPhanQuyen(Request $request)
    {
        $namHoc = $request->input('nien_khoa');
        $hocKy = $request->input('hoc_ky');
        $lopHocs = [];
        if ($namHoc) {
            $lopHocs = LopHoc::where('nien_khoa', $namHoc)->get(['ma_lop_hoc', 'ten_lop_hoc']);
        }
        $monHocs = [];
        if ($hocKy) {
            $monHocs = MonHoc::where('hoc_ky', $hocKy)->get(['ma_mon_hoc', 'ten_mon_hoc']);
        }
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
            return redirect()->back()->with('error', 'Quyền này đã hoạt động. Vui lòng kiểm tra lại!');
        }
    }
    public function suaPhanQuyen(Request $request, $id)
    {
        $phanQuyen = PhanQuyenDay::find($id);
        if (!$phanQuyen) {
            return redirect()->back()->with('error', 'Không tìm thấy phân quyền.');
        }
        $maGiangVienCu = $phanQuyen->ma_giang_vien;
        $maMonHoc = $phanQuyen->ma_mon_hoc;
        $maLopHoc = $phanQuyen->ma_lop_hoc;
        $daTaoCauHoi = CauHoi::where('ma_mon_hoc', $maMonHoc)
            ->where('ma_giang_vien', $maGiangVienCu)
            ->exists();
        $daTaoThongBao = ThongBao::where('ma_lop_hoc', $maLopHoc)
            ->where('nguoi_gui', $maGiangVienCu)
            ->exists();

        if ($daTaoCauHoi || $daTaoThongBao) {
            return redirect()->back()->with('error', 'Không thể sửa vì giảng viên đã có hoạt động liên quan.');
        }
        $phanQuyen->ma_giang_vien = $request->input('ma_giang_vien');
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
        $validated = $request->validate([
            'email' => 'required|email|unique:nguoidungs,email',
            'mat_khau' => 'required',
            'ho_ten' => 'required',
            'gioi_tinh' => 'required',
            'ngay_sinh' => 'required|date',
            'vai_tro' => 'required|in:sinh_vien,giang_vien,can_bo',
            'so_dien_thoai' => 'required',
            'mssv' => 'required_if:vai_tro,sinh_vien',
            'ma_lop' => 'required_if:vai_tro,sinh_vien',
            'hoc_ky' => 'required_if:vai_tro,sinh_vien|integer|min:1|max:10',
            'khoa_hoc' => 'required_if:vai_tro,sinh_vien|numeric|min:2000|max:' . (now()->year + 3),
        ]);
        $maNguoiDungMoi = 'ND' . strtoupper(substr(uniqid(), -4)) . rand(10, 99);
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
        }

        if ($validated['vai_tro'] === 'sinh_vien') {
            $maSinhVienMoi = 'SV' . strtoupper(substr(uniqid(), -4)) . rand(10, 99);
            $nienKhoa = $validated['khoa_hoc'];
            SinhVien::create([
                'ma_sinh_vien' => $maSinhVienMoi,
                'ma_nguoi_dung' => $nguoiDung->ma_nguoi_dung,
                'mssv' => $validated['mssv'],
            ]);
            SinhVienLopHoc::create([
                'ma_sv_lh' => 'MSVLH' . strtoupper(substr(uniqid(), -2)) . rand(10, 99),
                'ma_sinh_vien' => $maSinhVienMoi,
                'ma_lop_hoc' => $validated['ma_lop'],
                'hoc_ky' => 1,
                'nam_hoc' => $nienKhoa,
                'is_hien_tai' => 1,
            ]);
        }

        return redirect()->route('add_user')->with('success', 'Thêm người dùng thành công.');
    }

    public function themNguoiDungExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
            'vai_tro' => 'required|in:sinh_vien,giang_vien',
        ]);

        $path = $request->file('file_excel')->store('temp');
        $absolutePath = Storage::path($path);

        if (!file_exists($absolutePath)) {
            return redirect()->back()->with('error', 'File không tồn tại!');
        }

        $rows = SimpleExcelReader::create($absolutePath)->getRows();

        $keyMapping = [
            'sdt' => 'so_dien_thoai',
            'so_dt' => 'so_dien_thoai',
            'so_dthoai' => 'so_dien_thoai',
            'mssv' => 'ma_so_sinh_vien',
            'ma_sv' => 'ma_so_sinh_vien',
            'hvt' => 'ho_ten',
            'ho_va_ten' => 'ho_ten',
            'ngaysinh' => 'ngay_sinh',
            'hocvi' => 'hoc_vi',
            'lop' => 'lop',
            'hk' => 'hoc_ky',
            'namhoc' => 'nam_hoc',
        ];
        $successCount = 0;
        $failCount = 0;
        $errors = [];
        foreach ($rows as $index => $row) {
            try {
                $data = [];
                foreach ($row as $key => $value) {
                    $newKey = Str::of($key)
                        ->ascii()
                        ->lower()
                        ->replace([' ', '-', '.', ':'], '_')
                        ->__toString();
                    $newKey = rtrim($newKey, '_');

                    if (isset($keyMapping[$newKey])) {
                        $newKey = $keyMapping[$newKey];
                    }
                    $data[$newKey] = $value;
                }
                if (empty($data['email'])) {
                    throw new \Exception("Thiếu email.");
                }
                if (NguoiDung::where('email', $data['email'])->exists()) {
                    throw new \Exception("Email đã tồn tại: " . $data['email']);
                }
                if ($request->vai_tro === 'sinh_vien') {
                    if (empty($data['lop'])) {
                        throw new \Exception("Thiếu tên lớp.");
                    }

                    $lop = LopHoc::where('ten_lop_hoc', $data['lop'])->first();

                    if (!$lop) {
                        throw new \Exception("Lớp không tồn tại: " . $data['lop']);
                    }
                }
                $user = NguoiDung::create([
                    'ma_nguoi_dung' => 'ND' . strtoupper(substr(uniqid(), -4)) . rand(10, 99),
                    'ho_ten' => $data['ho_ten'] ?? 'Chưa rõ',
                    'email' => $data['email'],
                    'mat_khau' => Hash::make($data['mat_khau'] ?? '12345678'),
                    'gioi_tinh' => $data['gioi_tinh'] ?? null,
                    'ngay_sinh' => $data['ngay_sinh'] ?? null,
                    'dia_chi' => $data['dia_chi'] ?? null,
                    'so_dien_thoai' => $data['so_dien_thoai'] ?? null,
                    'vai_tro' => $request->vai_tro,
                ]);
                if ($request->vai_tro === 'sinh_vien') {
                    $maSinhVienMoi = 'SV' . strtoupper(substr(uniqid(), -4)) . rand(10, 99);

                    SinhVien::create([
                        'ma_sinh_vien' => $maSinhVienMoi,
                        'ma_nguoi_dung' => $user->ma_nguoi_dung,
                        'mssv' => $data['ma_so_sinh_vien'] ?? null,
                    ]);

                    SinhVienLopHoc::create([
                        'ma_sv_lh' => 'MSVLH' . strtoupper(substr(uniqid(), -2)) . rand(10, 99),
                        'ma_sinh_vien' => $maSinhVienMoi,
                        'ma_lop_hoc' => $lop->ma_lop_hoc,
                        'hoc_ky' => $data['hoc_ky'] ?? 1,
                        'nam_hoc' => $data['nam_hoc'] ?? now()->year,
                        'is_hien_tai' => 1
                    ]);
                } else {
                    GiangVien::create([
                        'ma_giang_vien' => 'GV' . strtoupper(substr(uniqid(), -4)) . rand(10, 99),
                        'ma_nguoi_dung' => $user->ma_nguoi_dung,
                        'hoc_vi' => $data['hoc_vi'] ?? null,
                    ]);
                }

                $successCount++;
            } catch (\Throwable $e) {
                $failCount++;
                $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        if ($failCount > 0) {
            return redirect()->back()->with('error', "Import xong: {$successCount} thành công, {$failCount} lỗi.")
                ->with('detail_errors', $errors);
        }
        return back()->with('success', 'Import thành công toàn bộ!');
    }



    //Quản lý lớp học
    public function layLopTheoKhoa($khoa)
    {
        $khoaHoc = (int) substr($khoa, offset: -2);
        $lopHocs = LopHoc::where('nien_khoa', $khoaHoc)
            ->where('loai_lop', 'chinh_thuc')->get();

        return response()->json($lopHocs);
    }
    public function hienThiLopHoc(Request $request)
    {
        $query = LopHoc::query();
        $query->where('trang_thai', 'hien');
        $fiveYearsAgo = Carbon::now()->subYears(5);
        $query->where('created_at', '>=', value: $fiveYearsAgo);
        $nienKhoa = (int) substr($request->nam_hoc, offset: -2);
        if ($request->filled('nam_hoc')) {
            $query->where('nien_khoa', $nienKhoa);
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
            'loai_lop' => [
                'required',
                Rule::in(['chinh_thuc', 'nang_cao']),
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

            LopHoc::create([
                'ten_lop_hoc' => $request->ten_lop_hoc,
                'nien_khoa' => $nienKhoa,
                'loai_lop' => $request->loai_lop,
            ]);

            return redirect()->route('add_class')->with('success', 'Thêm lớp học thành công!');

        } catch (\Exception $e) {
            return redirect()->route('add_class')->withInput()->with('error', 'Đã xảy ra lỗi khi thêm lớp học: ' . $e->getMessage());
        }
    }
    public function xoaLopHoc($id)
    {
        $lopHoc = LopHoc::find($id);
        $sinhVien = SinhVienLopHoc::where('ma_lop_hoc', $id)->exists();
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
            'loai_lop' => [
                'required',
                Rule::in(['chinh_thuc', 'nang_cao']),
            ],
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

