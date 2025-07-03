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

            return view('dean.dashboard')
                ->with('user', $user)
                ->with('sinhVien', $sinhVien)
                ->with('monHoc', $monHoc)
                ->with('giangVien', $giangVien)
                ->with('baiKiemTra', $baiKiemTra);
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
    public function hienThiDanhSachSinhVien(Request $request)
    {
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');

        $danhSachSinhVien = SinhVien::with([
            'nguoiDung' => function ($query) {
                $query->where('vai_tro', 'sinh_vien');
            }
        ])
            ->whereHas('nguoiDung', function ($query) use ($tuKhoaTimKiem) {
                $query->where('vai_tro', 'sinh_vien');

                if (!empty($tuKhoaTimKiem)) {
                    $query->where('ho_ten', 'like', '%' . $tuKhoaTimKiem . '%');
                }
            })
            ->latest() // sắp xếp theo thời gian mới nhất (nếu có cột created_at)
            ->paginate(10);

        return view('dean.student_management', [
            'danhSachSinhVien' => $danhSachSinhVien,
            'tuKhoaTimKiem' => $tuKhoaTimKiem
        ]);
    }

    public function xoaSinhVien($id)
    {
        $sinhVien = SinhVien::
            where('ma_sinh_vien', $id)
            ->first();
        $sinhVien->delete();

        return redirect()->route('student_management')->with('success', 'Xoá sinh viên thành công');
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

        $query = MonHoc::query();

        // Nếu có từ khoá tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('ten_mon_hoc', 'like', "%{$keyword}%");
        }

        $danhSachMonHoc = $query->paginate(5)->appends($request->all());

        return view('dean.subject_management')->with('danhSachMonHoc', $danhSachMonHoc);
    }

    public function themMonHoc(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "truong_khoa") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }
        // $request->validate([
        //     'name_subject' => 'required|string|max:255',
        //     'credit_subject' => 'required|integer|min:1',
        //     'semester_subject' => 'required|integer|min:1',
        //     'description_subject' => 'nullable|string',
        //     'criteria_subject' => 'nullable|string|exists:mon_hocs,ma_mon_hoc',
        //     'difficulty_subject' => 'required|string|max:50',
        // ]);
        // dd($request->all());
        $monHoc = MonHoc::create([
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

        try {
            DB::beginTransaction();

            $monHoc = MonHoc::where('ma_mon_hoc', $id)->first();
            $monHoc->phanQuyenDays()->delete();
            $monHoc->delete();
            DB::commit();

            return redirect()->route('subject_management')->with('success', 'Xoá môn học thành công');
        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác nếu có lỗi
            return redirect()->back()->with('error', 'Không thể xóa môn học: ' . $e->getMessage());
        }
    }

    //Quản lý giảng viên
    public function hienThiDanhSachGiangVien(Request $request)
    {
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');

        $danhSachGiangVien = GiangVien::with(['nguoiDung', 'monHocs'])
            ->whereHas('nguoiDung', function ($query) use ($tuKhoaTimKiem) {
                $query->where('vai_tro', 'giang_vien');

                if ($tuKhoaTimKiem) {
                    $query->where('ho_ten', 'like', '%' . $tuKhoaTimKiem . '%');
                }
            })
            ->paginate(5);

        return view('dean.lecturer_list')
            ->with('danhSachGiangVien', $danhSachGiangVien)
            ->with('tuKhoaTimKiem', $tuKhoaTimKiem);
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

    public function xoaGiangVien($id)
    {
        $giangVien = GiangVien::
            where('ma_giang_vien', $id)
            ->first();
        $giangVien->delete();

        return redirect()->route('lecturer_list')->with('success', 'Xoá giảng viên thành công');
    }

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
        $danhSachMonHoc = MonHoc::all();

        return view('dean.question_bank', compact('danhSachCauHoi', 'danhSachGiangVien', 'danhSachMonHoc'));
    }

    //Quản lý quyền dạy học
    public function hienthiQuyenGiangDay(Request $request)
    {
        $giangVienId = $request->input('giang_vien_id');
        $danhSachGiangVien = GiangVien::with('nguoiDung')->get();
        $danhSachMonHoc = MonHoc::all();
        $danhSachPhanQuyen = PhanQuyenDay::all();
        $danhSachLopHoc = LopHoc::all();

        if ($giangVienId) {
            $danhSachPhanQuyen = PhanQuyenDay::where('ma_giang_vien', $giangVienId)->get();
        } else {
            $danhSachPhanQuyen = PhanQuyenDay::all();
        }

        return view('dean.decentralization')
            ->with('danhSachGiangVien', $danhSachGiangVien)
            ->with('danhSachMonHoc', $danhSachMonHoc)
            ->with('danhSachPhanQuyen', $danhSachPhanQuyen)
            ->with('giangVienDaChon', $giangVienId)
            ->with('danhSachLopHoc', $danhSachLopHoc);
    }
    public function themQuyenDayHoc(Request $request)
    {
        $request->validate([
            'lecturer' => 'required|string|exists:giangviens,ma_giang_vien',
            'subject' => 'required|string|exists:mon_hocs,ma_mon_hoc',
            'class' => 'required|string|exists:lop_hocs,ma_lop_hoc',
        ]);

        // Tạo quyền mới trong bảng trung gian
        PhanQuyenDay::create([
            'ma_giang_vien' => $request->input('lecturer'),
            'ma_mon_hoc' => $request->input('subject'),
            'ma_lop_hoc' => $request->input('class')
        ]);

        return redirect()->route('decentralization')->with('success', 'Thêm quyền dạy học thành công');
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
    //}

    public function hienThiThongTinLopHoc()
    {
        $danhSachLopHoc = LopHoc::all();
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
        ]);

        // Tạo mã người dùng
        $maNguoiDungMoi = 'ND' . now()->format('is') . rand(10, 99);

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

        // Thêm dữ liệu sinh viên hoặc giảng viên nếu có
        if ($validated['vai_tro'] === 'giang_vien') {
            GiangVien::create([
                'ma_giang_vien' => 'GV' . now()->format('is') . rand(10, 99),
                'ma_nguoi_dung' => $nguoiDung->ma_nguoi_dung,
                'hoc_vi' => $request->input('hoc_vi'),
            ]);
        } elseif ($validated['vai_tro'] === 'sinh_vien') {
            SinhVien::create([
                'ma_sinh_vien' => 'SV' . now()->format('is') . rand(10, 99),
                'ma_nguoi_dung' => $nguoiDung->ma_nguoi_dung,
                'ma_lop_hoc' => $request->input('ma_lop'),
                'mssv' => $request->input('mssv'),
            ]);
        }

        return redirect()->route('add_user')->with('success', 'Thêm người dùng thành công.');
    }


    public function hienThiLopHoc()
    {
        $danhSachLopHoc = LopHoc::orderBy('ten_lop_hoc', 'asc')->get();

        return view('dean.add_class')->with('danhSachLopHoc', $danhSachLopHoc);
    }
    public function themLopHoc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_lop_hoc' => 'required|string|max:255',
            'nganh' => 'required|string|max:255',
            'nam_hoc' => 'required|integer|min:1900|max:2100',
            'hoc_ky' => 'required|integer|min:1|max:10',
            'mo_ta' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm lớp học. Vui lòng kiểm tra lại thông tin.');
        }

        try {
            $lopHoc = LopHoc::create([
                'ten_lop_hoc' => $request->ten_lop_hoc,
                'nganh' => $request->nganh,
                'nam_hoc' => $request->nam_hoc,
                'hoc_ky' => $request->hoc_ky,
                'mo_ta' => $request->mo_ta,
            ]);

            return redirect()->route('add_class')->with('success', 'Thêm lớp học     thành công!');

        } catch (\Exception $e) {
            return redirect()->route('add_class')->withInput()->with('error', 'Đã xảy ra lỗi khi thêm lớp học: ' . $e->getMessage());
        }
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
        $lopHoc->delete();

        return redirect()->route('add_class')->with('success', 'Xoá lớp học thành công');
    }

}

