<?php

namespace App\Http\Controllers;
use App\Models\BaiKiemTra;
use App\Models\CauHoi;
use App\Models\ChiTietDeThiVaCauHoi;
use App\Models\DeThi;
use App\Models\LienHe;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\NguoiDung;
use App\Models\DapAn;
use App\Models\ThongBao;
use App\Models\GiangVien;
use App\Models\MonHoc;
use App\Models\BangDiem;
use App\Models\LichSuLamBai;
use App\Models\ChuongMonHoc;
use App\Models\LopHoc;
use App\Models\ChiTietBaiKiemTraVaCauHoi;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;



class LecturerController extends Controller
{
    //hiển thị tên giảng viên
    public function hienThiTenGiangVien()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $giangVien = auth()->user()->giangVien;
            $monHoc = $giangVien->monHocs;
            $cauHoi = $giangVien->cauHois;
            $baiKiemTra = DeThi::with('baiKiemTras')->where('ma_giang_vien', $giangVien->ma_giang_vien)->get();
            $sinhVien = collect();
            $maBaiKiemTras = $baiKiemTra
                ->pluck('baiKiemTras')
                ->flatten()
                ->pluck('ma_bai_kiem_tra')
                ->unique();
            $lichSuLamBai = LichSuLamBai::whereIn('ma_bai_kiem_tra', $maBaiKiemTras)->get()->unique('ma_lich_su_lam_bai');
            foreach ($giangVien->lopHocs as $lop) {
                $sinhVien = $sinhVien->merge($lop->sinhViens);
            }
            return view('lecturer.dashboard')
                ->with('user', $user)
                ->with('monHoc', $monHoc)
                ->with('cauHoi', $cauHoi)
                ->with('baiKiemTra', $baiKiemTra)
                ->with('sinhVien', $sinhVien)
                ->with('lichSuLamBai', $lichSuLamBai);
        }
        return redirect('/login');
    }


    //Quản lý thông tin giảng viên
    public function hienThiThongTinGiangVien()
    {
        if (Auth::check()) {

            $user = Auth::user();

            return view('lecturer.lecturer_info')->with('user', $user);
        }
        return redirect('/login');
    }
    public function luuThongTinGiangVien(Request $request)
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
    public function doiMatKhauGiangVien(Request $request)
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

    //Quản lý môn học
    public function hienThiMonGiangVienDay()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $user = Auth::user();

        if ($user->vai_tro !== 'giang_vien') {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập trang này.');
        }
        $giangVien = GiangVien::where('ma_nguoi_dung', $user->ma_nguoi_dung)->first();

        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên.');
        }
        $maGV = $giangVien->ma_giang_vien;
        $danhSachMonHoc = MonHoc::whereHas('phanQuyenDays', function ($query) use ($maGV) {
            $query->where('ma_giang_vien', $maGV);
        })
            ->with([
                'phanQuyenDays' => function ($q) use ($maGV) {
                    $q->where('ma_giang_vien', $maGV)->with('lopHoc');
                }
            ])
            ->paginate(5);

        return view('lecturer.subject_list', [
            'danhSachMonHoc' => $danhSachMonHoc
        ]);
    }
    public function layMonHocTheoHocKy(Request $request)
    {
        $hocKy = $request->query('hoc_ky');

        $monHoc = MonHoc::where('hoc_ky', $hocKy)->where('trang_thai', 'hien')->get(['ma_mon_hoc', 'ten_mon_hoc']);

        return response()->json($monHoc);
    }
    public function hienThiDanhSachChuong(Request $request)
    {
        $monHocId = $request->input('mon_hoc_id');
        $keyword = $request->input('tu_khoa_tim_kiem');
        $giangVienId = Auth::user()->giangVien->ma_giang_vien ?? null;

        $danhSachMonHoc = MonHoc::whereIn('ma_mon_hoc', function ($query) use ($giangVienId) {
            $query->select('ma_mon_hoc')
                ->from('phan_quyen_days')
                ->where('ma_giang_vien', $giangVienId)
                ->distinct();
        })
            ->where('trang_thai', 'hien')
            ->get();
        $query = ChuongMonHoc::with('monHoc')
            ->whereIn('ma_mon_hoc', $danhSachMonHoc->pluck('ma_mon_hoc'))
            ->orderBy('ma_mon_hoc')
            ->orderBy('so_thu_tu');

        if ($monHocId) {
            $query->where('ma_mon_hoc', $monHocId);
        }

        if ($keyword) {
            $query->where('ten_chuong', 'like', '%' . $keyword . '%');
        }

        $danhSachChuong = $query->paginate(10);

        return view('lecturer.chapter_management', [
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


    //Quản lý câu hỏi và đáp án
    public function hienThiCauHoiVaDapAn(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $giangVien = GiangVien::where('ma_nguoi_dung', $user->ma_nguoi_dung)->first();

        if (!$giangVien) {
            return view('lecturer.question', [
                'danhSachCauHoi' => collect(),
                'danhSachMonHoc' => collect(),
                'selectedMonHocId' => null,
                'user' => $user
            ]);
        }

        $danhSachMonHoc = $giangVien->monHocs ?? collect();
        if (is_null($giangVien->monHocs)) {
            \Log::error('monHocs trả về null từ model GiangVien.');
        }

        $maMonHocs = $danhSachMonHoc->pluck('ma_mon_hoc')->toArray();

        // Query câu hỏi với điều kiện thêm: phải là do chính giảng viên tạo
        $thongTinCauHoi = CauHoi::with(['dapAns', 'monHoc', 'chuongMonHoc'])
            ->whereIn('ma_mon_hoc', $maMonHocs)
            ->where('ma_giang_vien', $giangVien->ma_giang_vien) // 🔴 Chỉ lấy câu hỏi của chính giảng viên tạo ra
            ->where('trang_thai', 'hien');

        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');
        if ($tuKhoaTimKiem) {
            $thongTinCauHoi->where('noi_dung', 'like', '%' . $tuKhoaTimKiem . '%');
        }

        $locTheoMocHoc = $request->input('mon_hoc_id');
        if ($locTheoMocHoc) {
            $thongTinCauHoi->where('ma_mon_hoc', $locTheoMocHoc);
        }

        $danhSachCauHoi = $thongTinCauHoi
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends(request()->query());

        return view('lecturer.question', [
            'danhSachCauHoi' => $danhSachCauHoi,
            'danhSachMonHoc' => $danhSachMonHoc,
            'locTheoMocHoc' => $locTheoMocHoc,
            'tuKhoaTimKiem' => $tuKhoaTimKiem,
            'user' => $user,
        ]);
    }

    public function capNhatPhamVi(Request $request, $id)
    {
        $user = Auth::user();
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);

        }

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");

        if (!$giangVienId) {
            return response()->json(["success" => false, "message" => "Không tìm thấy giảng viên này"], 404);
        }

        $cauHoi = CauHoi::where('ma_cau_hoi', $id)
            ->where('ma_giang_vien', $giangVienId)->first();

        if (!$cauHoi) {
            return response()->json(['success' => false, 'message' => 'Câu hỏi không tồn tại'], );
        }

        $phamViMoi = $request->input('pham_vi');

        $cauHoi->pham_vi = $phamViMoi;
        $cauHoi->save();

        return response()->json(['success' => true, "message" => "Thay đổi phạm vi thành công"]);

    }
    public function capNhatTrangThaiCauHoi(Request $request, $id)
    {
        $user = Auth::user();
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
        }


        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");

        if (!$giangVienId) {
            return response()->json(["success" => false, "message" => "Không tìm thấy giảng viên này"], 404);
        }

        $cauHoi = CauHoi::where('ma_cau_hoi', $id)
            ->where('ma_giang_vien', $giangVienId)
            ->first();

        if (!$cauHoi) {
            return response()->json(['success' => false, 'message' => 'Câu hỏi không tồn tại'], );
        }

        $trangThaiMoi = $request->input('trang_thai_moi');
        $cauHoi->trang_thai = $trangThaiMoi;
        $cauHoi->save();

        return response()->json([
            'success' => true,
            'message' => "Cập nhật trạng thái câu hỏi thành công.",
            'new_status' => $trangThaiMoi
        ]);
    }
    public function hienThiCauHoi(Request $request, $id)
    {
        $user = Auth::user();
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
        }

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");
        if (!$giangVienId) {
            return response()->json(["success" => false, "message" => "Không tìm thấy giảng viên này"], 404);
        }

        $cauHoi = CauHoi::with('dapAns')
            ->where('ma_cau_hoi', $id)
            ->where('ma_giang_vien', $giangVienId)
            ->first();

        if (!$cauHoi) {
            return response()->json(['success' => false, 'message' => 'Câu hỏi không tồn tại'], );
        }

        return view('lecturer.editquestion')
            ->with('user', $user)
            ->with('cauHoi', $cauHoi);
    }
    public function capNhatCauHoi(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
        }

        $user = Auth::user();

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");

        if (!$giangVienId) {
            return response()->json(["success" => false, "message" => "Không tìm thấy giảng viên này"], 404);
        }


        $cauHoi = CauHoi::with('dapAns')
            ->where('ma_cau_hoi', $id)
            ->where('ma_giang_vien', $giangVienId)
            ->first();

        if (!$cauHoi) {
            return redirect()->back()->with('error', 'Câu hỏi không tồn tại hoặc bạn không có quyền chỉnh sửa.');
        }

        $validated = $request->validate([
            'noi_dung_cau_hoi' => 'required|string|min:1|max:1000',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ghi_chu' => 'nullable|string|max:500',
            'do_kho' => ['required', Rule::in(['Dễ', 'Trung bình', 'Khó'])],
            'dap_ans' => 'required|array|min:2',
            'dap_ans.*.ma_dap_an' => 'nullable|string',
            'dap_ans.*.noi_dung' => 'required|string|min:1|max:500',
            'ma_dap_an_dung' => 'nullable|array',
            'ma_dap_an_dung.*' => 'string',
        ]);

        DB::beginTransaction();
        try {
            // Cập nhật thông tin câu hỏi
            $cauHoi->noi_dung = $validated['noi_dung_cau_hoi'];
            $cauHoi->ghi_chu = $validated['ghi_chu'] ?? null;
            $cauHoi->do_kho = $validated['do_kho'];

            if ($request->hasFile('hinh_anh')) {
                $path = $request->file('hinh_anh')->store('cauhoi_images', 'public');
                $cauHoi->hinh_anh = $path;
            }

            $cauHoi->save();

            // Cập nhật đáp án
            $existingAnswerIds = $cauHoi->dapAns->pluck('ma_dap_an')->toArray();
            $submittedAnswerIds = [];
            $correctAnswerIds = $validated['ma_dap_an_dung'] ?? [];
            foreach ($validated['dap_ans'] as $answerData) {
                $maDapAn = $answerData['ma_dap_an'] ?? null;
                $noiDung = $answerData['noi_dung'];
                $isCorrect = in_array((string) $maDapAn, $correctAnswerIds);

                if ($maDapAn && is_numeric($maDapAn) && in_array($maDapAn, $existingAnswerIds)) {
                    // Cập nhật đáp án cũ
                    $dapAn = DapAn::find($maDapAn);
                    if ($dapAn) {
                        $dapAn->noi_dung = $noiDung;
                        $dapAn->ket_qua_dap_an = $isCorrect ? 1 : 0;
                        $dapAn->save();
                        $submittedAnswerIds[] = $dapAn->ma_dap_an;
                    }
                } else {
                    // Thêm đáp án mới
                    $dapAn = DapAn::create([
                        'ma_cau_hoi' => $cauHoi->ma_cau_hoi,
                        'noi_dung' => $noiDung,
                        'ket_qua_dap_an' => $isCorrect ? 1 : 0,
                    ]);
                    $submittedAnswerIds[] = $dapAn->ma_dap_an;
                }
            }

            // Xoá đáp án không còn trong form
            $answersToDelete = array_diff($existingAnswerIds, $submittedAnswerIds);
            if (!empty($answersToDelete)) {
                DapAn::whereIn('ma_dap_an', $answersToDelete)
                    ->where('ma_cau_hoi', $cauHoi->ma_cau_hoi)
                    ->delete();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật câu hỏi thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Lỗi cập nhật câu hỏi: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Lỗi xảy ra khi cập nhật câu hỏi.');
        }
    }
    public function hienThiTrangThemCauHoi(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập để tiếp tục.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");

        if (!$giangVienId) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên.');
        }

        $danhSachMonHoc = DB::table("phan_quyen_days")
            ->join('mon_hocs', 'phan_quyen_days.ma_mon_hoc', '=', 'mon_hocs.ma_mon_hoc')
            ->where("phan_quyen_days.ma_giang_vien", $giangVienId)
            ->select('mon_hocs.*')
            ->get()->unique();


        return view('lecturer.addquestion')->with('danhSachMonHoc', $danhSachMonHoc);
    }
    private function parseQuestions($text)
    {
        $lines = explode("\n", $text);
        $questions = [];
        $current = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^Câu\s+\d+[:\.]?\s*(.*)/i', $line, $matches)) {
                if (!empty($current)) {
                    $questions[] = $current;
                    $current = [];
                }
                $current['question'] = $matches[1];
                $current['answers'] = [];
            } elseif (preg_match('/^([A-D])[\.\:]\s*(.*)/', $line, $matches)) {
                $current['answers'][$matches[1]] = $matches[2];
            } elseif (preg_match('/^Đáp án đúng[:\s]+([A-D])/', $line, $matches)) {
                $current['correct'] = $matches[1];
            }
        }
        if (!empty($current)) {
            $questions[] = $current;
        }
        return $questions;
    }
    public function layChuongTheoMon(Request $request)
    {
        $maMonHoc = $request->query('ma_mon_hoc');

        $chuongList = ChuongMonHoc::where('ma_mon_hoc', $maMonHoc)->get();

        return response()->json($chuongList);
    }
    public function themCauHoi(Request $request)
    {
        $user = Auth::user();
        $giangVienId = DB::table('giangviens')
            ->where('ma_nguoi_dung', $user->ma_nguoi_dung)
            ->value('ma_giang_vien');

        if (!$giangVienId) {
            return redirect()->back()->withErrors('Không tìm thấy giảng viên.');
        }

        $monHocDuocDay = DB::table('phan_quyen_days')
            ->where('ma_giang_vien', $giangVienId)
            ->pluck('ma_mon_hoc')
            ->toArray();

        DB::beginTransaction();
        try {
            // Nhập từ file PDF
            if ($request->hasFile('pdf_file')) {
                $request->validate([
                    'pdf_file' => 'mimes:pdf|max:2048',
                    'subject_pdf' => 'required|in:' . implode(',', $monHocDuocDay),
                    'difficulty_pdf' => 'required|string'
                ]);
                $parser = new Parser();
                $pdf = $parser->parseFile($request->file('pdf_file')->getRealPath());
                $text = $pdf->getText();
                $questions = $this->parseQuestions($text);

                foreach ($questions as $q) {
                    do {
                        $maCauHoi = 'CH' . strtoupper(substr(Str::uuid()->toString(), -4)) . rand(10, 99);
                    } while (CauHoi::where('ma_cau_hoi', $maCauHoi)->exists());

                    $cauHoi = new CauHoi([
                        'ma_cau_hoi' => $maCauHoi,
                        'noi_dung' => $q['question'],
                        'do_kho' => $request->input('difficulty_pdf'),
                        'ma_mon_hoc' => $request->input('subject_pdf'),
                        'ma_chuong' => $request->input('chapter_pdf'),
                        'ma_giang_vien' => $giangVienId
                    ]);
                    if (!$cauHoi->save()) {
                        throw new \Exception("Không thể lưu câu hỏi: {$maCauHoi}");
                    }
                    foreach ($q['answers'] as $label => $text) {
                        DapAn::create([
                            'ma_cau_hoi' => $cauHoi->ma_cau_hoi,
                            'noi_dung' => $text,
                            'ket_qua_dap_an' => ($label == $q['correct']) ? 1 : 0,
                        ]);
                    }
                }
            }

            //  Nhập từ form
            $count = 0;
            while ($request->has("question_content_" . ($count + 1))) {
                $count++;
            }

            for ($i = 1; $i <= $count; $i++) {
                $noiDung = $request->input("question_content_$i");
                if (!$noiDung || trim($noiDung) === '') {
                    continue;
                }

                $request->validate([
                    "difficulty_$i" => 'required|string',
                    "subject_$i" => 'required|string|in:' . implode(',', $monHocDuocDay),
                    "answers_{$i}" => 'required|array|min:1',
                ], [
                    "subject_{$i}.in" => "Môn học không thuộc quyền giảng dạy của bạn."
                ]);

                do {
                    $maCauHoi = 'CH' . strtoupper(substr(Str::uuid()->toString(), -4)) . rand(10, 99);
                } while (CauHoi::where('ma_cau_hoi', $maCauHoi)->exists());

                $cauHoi = new CauHoi([
                    'ma_cau_hoi' => $maCauHoi,
                    'noi_dung' => $noiDung,
                    'ghi_chu' => $request->input("note_$i") ?? null,
                    'do_kho' => $request->input("difficulty_$i"),
                    'ma_mon_hoc' => $request->input("subject_$i"),
                    'ma_chuong' => $request->input("chapter_$i"),
                    'ma_giang_vien' => $giangVienId
                ]);

                if ($request->hasFile("question_image_$i")) {
                    $file = $request->file("question_image_$i");
                    $fileName = uniqid('img_') . '.' . $file->getClientOriginalExtension();

                    $destination = public_path('images');
                    if (!file_exists($destination)) {
                        mkdir($destination, 0755, true);
                    }

                    $file->move($destination, $fileName);
                    $cauHoi->hinh_anh = $fileName;
                }

                if (!$cauHoi->save()) {
                    throw new \Exception("Không thể lưu câu hỏi: {$maCauHoi}");
                }

                $answers = $request->input("answers_{$i}");
                foreach ($answers as $answer) {
                    if (!isset($answer['text']) || trim($answer['text']) === '') {
                        continue;
                    }
                    DapAn::create([
                        'ma_cau_hoi' => $maCauHoi,
                        'noi_dung' => $answer['text'],
                        'ket_qua_dap_an' => isset($answer['is_correct']) ? 1 : 0,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Lỗi thêm câu hỏi: ' . $e->getMessage());
        }

        return redirect()->route('addquestion')->with('success', 'Thêm câu hỏi thành công!');
    }
    public function hienThiCauHoiVaDapAnBiXoa(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $giangVienId = GiangVien::with('monHocs')->where('ma_nguoi_dung', $user->ma_nguoi_dung)->first();

        if (!$giangVienId) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên.');
        }

        $danhSachMonHoc = $giangVienId->monHocs;

        $thongTinCauHoi = CauHoi::with(['dapAns', 'monHoc'])
            ->where('ma_giang_vien', $giangVienId->ma_giang_vien)
            ->where('trang_thai', 'an'); // ChỈ lấy câu hỏi bị ẩn
        // Tìm kiếm theo từ khóa
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');
        if ($tuKhoaTimKiem) {
            $thongTinCauHoi->where('noi_dung', 'like', '%' . $tuKhoaTimKiem . '%');
        }

        // Lọc theo môn học
        $locTheoMocHoc = $request->input('mon_hoc_id');
        if ($locTheoMocHoc) {
            $thongTinCauHoi->where('ma_mon_hoc', $locTheoMocHoc);
        }

        $danhSachCauHoi = $thongTinCauHoi->paginate(10);

        return view('lecturer.question_del')
            ->with('danhSachCauHoi', $danhSachCauHoi)
            ->with('danhSachMonHoc', $danhSachMonHoc)
            ->with('locTheoMocHoc', $locTheoMocHoc)
            ->with('tuKhoaTimKiem', $tuKhoaTimKiem)
            ->with('user', $user);
    }
    public function xoaCauHoi($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");

        if (!$giangVienId) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên.');
        }

        $cauHoi = CauHoi::where('ma_giang_vien', $giangVienId)
            ->where('ma_cau_hoi', $id)
            ->first();

        if (!$cauHoi) {
            return redirect()->back()->with('error', 'Câu hỏi không tồn tại.');
        }

        if ($cauHoi->hinh_anh && Storage::disk('public')->exists('cauhoi_images/' . $cauHoi->hinh_anh)) {
            Storage::disk('public')->delete('cauhoi_images/' . $cauHoi->hinh_anh);
        }

        DapAn::where('ma_cau_hoi', $cauHoi->ma_cau_hoi)->delete();

        $cauHoi->delete();

        DapAn::where('ma_cau_hoi', $cauHoi->ma_cau_hoi)->delete();
        return redirect()->route('question_del')->with('success', 'Xoá câu hỏi thành công.');
    }
    public function hienThiCauHoiVaDapAnTrangCongDong(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $danhSachMonHoc = DB::table('mon_hocs')->get();

        // Bắt đầu query builder với điều kiện cơ bản
        $query = CauHoi::with(['dapAns', 'monHoc.giangViens'])
            ->where('pham_vi', 'cong_khai')
            ->where('trang_thai', 'hien');

        // Tìm kiếm theo từ khóa
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');
        if ($tuKhoaTimKiem) {
            $query->where('noi_dung', 'like', '%' . $tuKhoaTimKiem . '%');
        }

        // Lọc theo môn học
        $locTheoMocHoc = $request->input('mon_hoc_id');
        if ($locTheoMocHoc) {
            $query->where('ma_mon_hoc', $locTheoMocHoc);
        }

        // Lấy kết quả phân trang
        $danhSachCauHoi = $query->paginate(10);

        return view('lecturer.global', [
            'danhSachCauHoi' => $danhSachCauHoi,
            'danhSachMonHoc' => $danhSachMonHoc,
            'locTheoMocHoc' => $locTheoMocHoc,
            'tuKhoaTimKiem' => $tuKhoaTimKiem,
            'user' => $user,
        ]);
    }


    //Quản lý thông báo
    public function hienThiDanhSachThongBao(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");

        $danhSachThongBao = ThongBao::with('lopHoc')->where('nguoi_gui', $giangVienId)->paginate(10);

        return view('lecturer.announce_list')->with('danhSachThongBao', $danhSachThongBao);
    }
    public function layDsLopHoc()
    {
        $nguoiDung = Auth::user();
        $giangVien = GiangVien::where('ma_nguoi_dung', $nguoiDung->ma_nguoi_dung)->first();
        $danhSachLopHoc = $giangVien->lopHocs->unique('ma_lop_hoc')->values();

        return view('lecturer.announce')->with('danhSachLopHoc', $danhSachLopHoc);
    }
    public function guiThongBao(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->first();

        $maGiangVien = $giangVienId->ma_giang_vien;
        $thongBao = new ThongBao();
        $thongBao->ma_thong_bao = 'MTB' . now()->format('is') . rand(10, 99);
        $thongBao->tieu_de = $request->input('title-announce');
        $thongBao->noi_dung = $request->input('title-content');
        $thongBao->ma_lop_hoc = $request->input('ma_lop_hoc');
        $thongBao->nguoi_gui = $maGiangVien;
        $thongBao->save();

        return redirect()->route('announce')->with('success', 'Gửi thông báo thành công');

    }
    public function xoaThongbao($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        $giangVienId = DB::table("giangviens")
            ->where("ma_nguoi_dung", $user->ma_nguoi_dung)
            ->value("ma_giang_vien");

        if (!$giangVienId) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên.');
        }

        $thongBao = ThongBao::where('nguoi_gui', $giangVienId)
            ->where('ma_thong_bao', $id)
            ->first();
        $thongBao->delete();

        return redirect()->route('announce_list')->with('success', 'Xoá câu hỏi thành công');
    }


    //Quản lý sinh vien
    public function hienThiDanhSachSinhVien(Request $request)
    {
        $user = Auth::user();
        $keyword = $request->query('keyword');

        // Lấy mã giảng viên hiện tại
        $giangVien = GiangVien::where('ma_nguoi_dung', $user->ma_nguoi_dung)->firstOrFail();

        // Lấy danh sách mã lớp được phân quyền giảng dạy
        $maLopHocs = DB::table('phan_quyen_days')
            ->where('ma_giang_vien', $giangVien->ma_giang_vien)
            ->pluck('ma_lop_hoc')
            ->unique();

        // Truy vấn sinh viên qua bảng trung gian sinh_vien_lop_hoc
        $query = SinhVien::with('nguoiDung')
            ->join('sinh_vien_lop_hoc as svlh', 'sinhviens.ma_sinh_vien', '=', 'svlh.ma_sinh_vien')
            ->whereIn('svlh.ma_lop_hoc', $maLopHocs)
            ->where('sinhviens.trang_thai', '!=', 'an')
            ->distinct('sinhviens.ma_sinh_vien'); // Tránh trùng sinh viên nếu học nhiều lớp

        // Tìm kiếm theo tên
        if ($keyword) {
            $query->whereHas('nguoiDung', function ($q) use ($keyword) {
                $q->where('ho_ten', 'like', '%' . $keyword . '%');
            });
        }

        // Phân trang
        $danhSachSinhVien = $query->paginate(10);

        return view('lecturer.student_list')
            ->with('danhSachSinhVien', $danhSachSinhVien)
            ->with('keyword', $keyword);
    }

    public function doiMatKhauSinhVien(Request $request)
    {
        $request->validate([
            'ma_sinh_vien' => 'required|exists:sinhviens,ma_sinh_vien',
            'mat_khau_moi' => 'required|min:6',
            'xac_nhan_mat_khau' => 'required|same:mat_khau_moi',
        ]);

        $sinhVien = SinhVien::with('nguoiDung')
            ->where('ma_sinh_vien', $request->ma_sinh_vien)
            ->first();

        if (!$sinhVien || !$sinhVien->nguoiDung) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản sinh viên.');
        }

        $nguoiDung = $sinhVien->nguoiDung;
        $nguoiDung->mat_khau = bcrypt($request->mat_khau_moi);
        $nguoiDung->save();

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công.');
    }


    //Quản lý đề thi và bài kiểm tra
    public function taoDeThi(Request $request)
    {
        $danhSachIdCauHoi = $request->input('ma_cau_hoi');

        if (is_array($danhSachIdCauHoi) && count($danhSachIdCauHoi) > 0) {
            $cauHoiDaChon = CauHoi::with('dapAns')
                ->whereIn('ma_cau_hoi', $danhSachIdCauHoi)
                ->get();
            if ($cauHoiDaChon->isEmpty()) {
                return redirect()->back()->with('error', 'Vui lòng chọn câu hỏi trước khi tạo!');
            }
            $maMonHoc = optional($cauHoiDaChon->first())->ma_mon_hoc;
        }

        // Trường hợp 2: Tạo đề tự động
        elseif ($request->filled('ma_mon_hoc') && $request->filled('so_luong')) {
            $maMonHoc = $request->input('ma_mon_hoc');
            $soLuong = (int) $request->input('so_luong');

            // Lấy tất cả câu hỏi theo môn và trạng thái
            $tatCaCauHoi = CauHoi::where('ma_mon_hoc', $maMonHoc)
                ->where('trang_thai', 'hien')
                ->get();

            $tongCauHoi = $tatCaCauHoi->count();
            if ($soLuong > $tongCauHoi) {
                return redirect()->back()->with('error', 'Số lượng câu hỏi tạo quá giới hạn số lượng có. Vui lòng kiểm tra và tạo lại đề thi mới!');
            }

            // Gom nhóm câu hỏi theo chương
            $cauHoiTheoChuong = $tatCaCauHoi->groupBy('chuong');
            $soChuongCoCauHoi = $cauHoiTheoChuong->count();

            if ($soChuongCoCauHoi == 0) {
                return redirect()->back()->with('error', 'Không có chương nào có câu hỏi.');
            }

            // Tính số lượng câu hỏi mỗi chương
            $phanBo = [];
            $soLuongMoiChuong = intdiv($soLuong, $soChuongCoCauHoi);
            $du = $soLuong % $soChuongCoCauHoi;

            foreach ($cauHoiTheoChuong as $chuong => $dsCauHoi) {
                $soCauChuong = $soLuongMoiChuong;

                // Phân phối phần dư cho một số chương đầu
                if ($du > 0) {
                    $soCauChuong += 1;
                    $du--;
                }

                // Lấy ngẫu nhiên số lượng câu hỏi phù hợp
                $soCauLay = min($soCauChuong, $dsCauHoi->count());

                $phanBo[$chuong] = $dsCauHoi->shuffle()->take($soCauLay);
            }

            // Gộp toàn bộ các câu hỏi lại
            $cauHoiDaChon = new \Illuminate\Database\Eloquent\Collection();

            foreach ($phanBo as $ds) {
                $cauHoiDaChon = $cauHoiDaChon->merge($ds);
            }

            // Load đáp án
            $cauHoiDaChon->load('dapAns');
        } else {
            return redirect()->back()->with('error', 'Vui lòng chọn câu hỏi đã có hoặc nhập số lượng và môn học.');
        }

        return view('lecturer.exam')->with([
            'cauHoiDaChon' => $cauHoiDaChon,
            'maMonHoc' => $maMonHoc,
        ]);
    }
    public function luuDeThi(Request $request)
    {
        $exists = DeThi::where('ten_de_thi', $request->input('ten_de_thi'))
            ->where('ma_mon_hoc', $request->input('ma_mon_hoc'))
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Đề thi đã được tạo! Vui lòng kiểm tra lại');
        }
        $maCauHois = $request->input('ma_cau_hoi');
        $giangVien = GiangVien::where('ma_nguoi_dung', auth()->user()->ma_nguoi_dung)
            ->with('monHocs')
            ->first();

        $ma_giang_vien = $giangVien->ma_giang_vien;

        $maDeThi = 'MDT' . substr(uniqid(), -5) . rand(10, 99);
        $dethi = DeThi::create([
            'ma_de_thi' => $maDeThi,
            'ten_de_thi' => $request->input('ten_de_thi'),
            'thoi_gian_lam_bai' => $request->input('thoi_gian_lam_bai'),
            'so_luong_cau_hoi' => count($maCauHois),
            'ma_mon_hoc' => $request->input('ma_mon_hoc'),
            'ma_giang_vien' => $ma_giang_vien,
        ]);

        if (!$dethi) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra! Vui lòng kiểm tra lại');
        }
        foreach ($maCauHois as $index => $maCauHoi) {
            $maCTCH = 'CT' . substr(uniqid(), -4) . rand(1, 9);
            DB::table('chi_tiet_de_thi_va_cau_hois')->insert([
                'ma_chi_tiet_dtch' => $maCTCH,
                'ma_de_thi' => $dethi->ma_de_thi,
                'ma_cau_hoi' => $maCauHoi,
                'thu_tu' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('question')->with('success', 'Tạo đề thi thành công!');
    }
    public function hienThiDeThi(Request $request)
    {
        $giangVien = auth()->user()->giangVien;
        $ma_giang_vien = $giangVien->ma_giang_vien;
        $maMonHocLoc = $request->input('ma_mon_hoc');

        $danhSachMonHoc = $giangVien->monHocs;
        $query = DeThi::with(['baiKiemTras.lopHoc', 'monHoc'])
            ->where('ma_giang_vien', $ma_giang_vien)
            ->where('trang_thai', 'hien');

        if ($maMonHocLoc) {
            $query->where('ma_mon_hoc', $maMonHocLoc);
        }

        // Lấy danh sách lớp học mà giảng viên được phân dạy
        $lopHocs = DB::table('lop_hocs')
            ->select('ma_lop_hoc', 'ten_lop_hoc')
            ->whereIn('ma_lop_hoc', function ($query) use ($giangVien) {
                $query->select('ma_lop_hoc')
                    ->from('phan_quyen_days')
                    ->where('ma_giang_vien', $giangVien->ma_giang_vien);
            })
            ->get();

        $danhSachDeThi = $query->get();

        return view('lecturer.exam_list', [
            'danhSachDeThi' => $danhSachDeThi,
            'danhSachMonHoc' => $danhSachMonHoc,
            'maMonHocLoc' => $maMonHocLoc,
            'lopHocs' => $lopHocs
        ]);
    }

    public function xoaDeThi($id)
    {
        $deThi = DeThi::with('baiKiemTras.lichSuLamBais')->find($id);

        if (!$deThi) {
            return redirect()->back()->with('error', 'Không tìm thấy đề thi cần xoá.');
        }

        $deThi->cauHoi()->detach();
        foreach ($deThi->baiKiemTras as $bkt) {
            $bkt->update(['trang_thai_hien_thi' => 'an']);
        }
        $deThi->update(['trang_thai' => 'an']);

        return redirect()->route('exam_list')->with('success', 'Xoá đề thi thành công.');
    }
    public function chiTietDeThi($id)
    {

        $deThi = DeThi::with('cauHoi.dapAns')->find($id);
        if (!$deThi) {
            return redirect()->route('exam_list')->with('error', 'Không tìm thấy đề thi');
        }

        return view('lecturer.exam_detail')->with('deThi', $deThi);
    }
    public function taoBaiKiemTra(Request $request)
    {
        $request->validate([
            'ma_de_thi' => 'required|exists:de_this,ma_de_thi',
            'ten_bai_kiem_tra' => 'required|string|max:255',
            'ma_lop_hoc' => 'required|exists:lop_hocs,ma_lop_hoc',
        ]);

        $maNguoiDung = Auth::user()->ma_nguoi_dung;
        $giangVien = GiangVien::where('ma_nguoi_dung', $maNguoiDung)->firstOrFail();
        $deThi = DeThi::findOrFail($request->ma_de_thi);
        if ($deThi->ma_giang_vien !== $giangVien->ma_giang_vien) {
            abort(403, 'Bạn không có quyền tạo bài kiểm tra cho lớp này.');
        }
        $maBaiKiemTra = 'BKT' . substr(uniqid(), -5) . rand(10, 99);

        $baiKiemTra = BaiKiemTra::create([
            'ma_bai_kiem_tra' => $maBaiKiemTra,
            'ma_de_thi' => $deThi->ma_de_thi,
            'ten_bai_kiem_tra' => $request->ten_bai_kiem_tra,
            'ma_giang_vien' => $giangVien->ma_giang_vien,
            'trang_thai' => 'khoa',
            'ma_lop_hoc' => $request->ma_lop_hoc,
        ]);

        $chiTietDeThi = ChiTietDeThiVaCauHoi::where('ma_de_thi', $deThi->ma_de_thi)
            ->orderBy('thu_tu')
            ->get();

        foreach ($chiTietDeThi as $index => $ctdt) {
            DB::table(table: 'chi_tiet_bai_kiem_tra_va_cau_hois')->insert([
                'ma_chi_tiet_bktch' => 'BKTCH' . substr(uniqid(), -4) . rand(10, 99),
                'ma_bai_kiem_tra' => $baiKiemTra->ma_bai_kiem_tra,
                'ma_cau_hoi' => $ctdt->ma_cau_hoi,
                'thu_tu' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Tạo bài kiểm tra thành công!');
    }

    public function xoaBaiKiemTra($id)
    {
        $baiKiemTra = BaiKiemTra::find($id);
        if (!$baiKiemTra) {
            return redirect()->back()->with('error', 'Đã cõ lỗi xảy ra!');
        }

        $coBangDiem = BangDiem::where('ma_bai_kiem_tra', $id)->exists();
        if ($coBangDiem) {
            return redirect()->back()->with('error', 'Không thể xóa vì bài kiểm tra đã có sinh viên làm bài (bảng điểm tồn tại).');
        }

        $coLichSu = LichSuLamBai::where('ma_bai_kiem_tra', $id)->exists();
        if ($coLichSu) {
            return redirect()->back()->with('error', 'Không thể xóa vì đã có lịch sử làm bài kiểm tra.');
        }

        try {
            DB::table('chi_tiet_bai_kiem_tra_va_cau_hois')
                ->where('ma_bai_kiem_tra', $id)
                ->delete();
            $baiKiemTra->delete();

            return redirect()->back()->with('success', 'Xóa bài kiểm tra thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi xóa: ' . $e->getMessage());
        }
    }
    public function thayDoiTrangThaiBaiKiemTra($id)
    {
        $baiKiemTraId = BaiKiemTra::find($id);

        $baiKiemTraId->trang_thai = $baiKiemTraId->trang_thai === 'mo' ? 'khoa' : 'mo';
        $baiKiemTraId->thoi_gian_khoa = now();
        $baiKiemTraId->save();

        return redirect()->back()->with('success', 'Trạng thái bài kiểm tra đã được cập nhật.');
    }
    public function hienThiTrangChiTietBaiKiemTra($id)
    {
        $baiKiemTra = BaiKiemTra::findOrFail($id);
        $chiTietCauHois = ChiTietBaiKiemTraVaCauHoi::with('cauHoi.dapAns')
            ->where('ma_bai_kiem_tra', $id)
            ->orderBy('thu_tu')
            ->get();
        return view('lecturer.exam_check', compact('baiKiemTra', 'chiTietCauHois'));
    }
    public function shuffleCauHoi($id)
    {
        $baiKiemTra = BaiKiemTra::findOrFail($id);
        $chiTietBKT = ChiTietBaiKiemTraVaCauHoi::where('ma_bai_kiem_tra', $id)
            ->orderBy('thu_tu')
            ->get();

        $shuffled = $chiTietBKT->shuffle()->values();

        foreach ($shuffled as $index => $ct) {
            $ct->update(['thu_tu' => $index + 1]);
        }
        $chiTietCauHois = ChiTietBaiKiemTraVaCauHoi::with('cauHoi.dapAns')
            ->where('ma_bai_kiem_tra', $id)
            ->orderBy('thu_tu')
            ->get();

        return view('lecturer.exam_check', compact('baiKiemTra', 'chiTietCauHois'));
    }

    public function exportDeThiPDF($id)
    {

        $deThi = DeThi::with(['cauHoi.dapAns'])->findOrFail($id);
        $pdf = Pdf::loadView('lecturer.pdf.de_thi_pdf', compact('deThi'));
        $tenFile = 'DeThi_' . $deThi->ma_de_thi . '.pdf';
        return $pdf->download($tenFile);
    }
    public function exportBaiKiemTraPDF($id)
    {
        $baiKiemTra = BaiKiemTra::with(['chiTietCauHoi.chiTietDeThi.cauHoi.dapAns', 'deThi'])->findOrFail($id);

        $pdf = Pdf::loadView('lecturer.pdf.bai_kiem_tra_pdf', compact('baiKiemTra'));
        return $pdf->download('bai_kiem_tra_' . $baiKiemTra->ma_bai_kiem_tra . '.pdf');
    }

    //Quản lý liên hệ
    public function hienThiLienHe(Request $request)
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;
        $maGiangVien = GiangVien::where('ma_nguoi_dung', $maNguoiDung)->first();
        $keyword = $request->query('keyword');
        $trangThai = $request->query('trang_thai');

        $query = LienHe::where('ma_giang_vien', $maGiangVien->ma_giang_vien);
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('tieu_de', 'like', '%' . $keyword . '%')
                    ->orWhere('noi_dung', 'like', '%' . $keyword . '%');
            });
        }

        if (!empty($trangThai)) {
            $query->where('trang_thai', $trangThai);
        } else {
            $query->where('trang_thai', 'hien');
        }

        $danhSachLienHe = $query->paginate(10)->appends([
            'keyword' => $keyword,
            'trang_thai' => $trangThai,
        ]);

        return view('lecturer.contact')
            ->with('danhSachLienHe', $danhSachLienHe)
            ->with('keyword', $keyword)
            ->with('trangThai', $trangThai);
    }
    public function xoaLienHe($ma_lien_he)
    {
        $lienHe = LienHe::where('ma_lien_he', $ma_lien_he)->first();

        if (!$lienHe) {
            return back()->with('error', 'Liên hệ không tồn tại.');
        }

        $lienHe->trang_thai = 'an';
        $lienHe->save();

        return back()->with('success', 'Đã ẩn liên hệ này.');
    }


    //Quản lý bảng điểm
    public function hienThiBangDiem(Request $request)
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;
        $maGiangVien = GiangVien::where('ma_nguoi_dung', $maNguoiDung)->value('ma_giang_vien');

        $maLop = $request->input('lop');
        $maMon = $request->input('mon');
        $maBaiKiemTra = $request->input('bai_kiem_tra');

        $bangDiemTruyVan = DB::table('sinhviens as sv')
            ->join('nguoidungs as nd', 'sv.ma_nguoi_dung', '=', 'nd.ma_nguoi_dung')
            ->join('sinh_vien_lop_hoc as svlh', 'sv.ma_sinh_vien', '=', 'svlh.ma_sinh_vien')
            ->join('lop_hocs as lh', 'svlh.ma_lop_hoc', '=', 'lh.ma_lop_hoc')
            ->join('phan_quyen_days as pqd', 'lh.ma_lop_hoc', '=', 'pqd.ma_lop_hoc')
            ->join('mon_hocs as mh', 'pqd.ma_mon_hoc', '=', 'mh.ma_mon_hoc')
            ->join('de_this as dt', function ($join) use ($maGiangVien) {
                $join->on('dt.ma_mon_hoc', '=', 'mh.ma_mon_hoc')
                    ->where('dt.ma_giang_vien', $maGiangVien);
            })
            ->join('bai_kiem_tras as bkt', 'bkt.ma_de_thi', '=', 'dt.ma_de_thi')
            ->leftJoin('bang_diems as bd', function ($join) {
                $join->on('bd.ma_sinh_vien', '=', 'sv.ma_sinh_vien')
                    ->on('bd.ma_bai_kiem_tra', '=', 'bkt.ma_bai_kiem_tra');
            })
            ->where('bkt.trang_thai', '=', 'mo')
            ->select(
                'sv.ma_sinh_vien',
                'nd.ho_ten as ten_sinh_vien',
                'lh.ten_lop_hoc',
                'mh.ten_mon_hoc',
                'bkt.ten_bai_kiem_tra',
                'bd.ma_bang_diem',
                'bd.diem_so as diem_so'
            )
            ->distinct();

        if ($maLop) {
            $bangDiemTruyVan->where('lh.ma_lop_hoc', $maLop);
        }

        if ($maMon) {
            $bangDiemTruyVan->where('mh.ma_mon_hoc', $maMon);
        }

        if ($maBaiKiemTra) {
            $bangDiemTruyVan->where('bkt.ma_bai_kiem_tra', $maBaiKiemTra);
        }

        $bangDiem = $bangDiemTruyVan->paginate(10);

        // Danh sách lớp từ phân quyền
        $danhSachLop = DB::table('phan_quyen_days as pq')
            ->join('lop_hocs as lh', 'pq.ma_lop_hoc', '=', 'lh.ma_lop_hoc')
            ->where('pq.ma_giang_vien', $maGiangVien)
            ->select('lh.ma_lop_hoc', 'lh.ten_lop_hoc')
            ->distinct()
            ->get();

        // Danh sách môn
        $danhSachMon = DB::table('phan_quyen_days as pq')
            ->join('mon_hocs as mh', 'pq.ma_mon_hoc', '=', 'mh.ma_mon_hoc')
            ->where('pq.ma_giang_vien', $maGiangVien)
            ->select('mh.ma_mon_hoc', 'mh.ten_mon_hoc')
            ->distinct()
            ->get();

        // Danh sách bài kiểm tra
        $danhSachBaiKiemTra = DB::table('de_this as dt')
            ->join('bai_kiem_tras as bkt', 'bkt.ma_de_thi', '=', 'dt.ma_de_thi')
            ->where('dt.ma_giang_vien', $maGiangVien)
            ->select('bkt.ma_bai_kiem_tra', 'bkt.ten_bai_kiem_tra')
            ->distinct()
            ->get();

        return view('lecturer.score_board')
            ->with('bangDiem', $bangDiem)
            ->with('danhSachLop', $danhSachLop)
            ->with('danhSachMon', $danhSachMon)
            ->with('danhSachBaiKiemTra', $danhSachBaiKiemTra)
            ->with('maLop', $maLop)
            ->with('maMon', $maMon);
    }

    public function xoaDiemSinhVien($id)
    {
        $bangDiem = DB::table('bang_diems')->where('ma_bang_diem', $id)->first();

        if ($bangDiem) {
            DB::table('lich_su_lam_bais')
                ->where('ma_bai_kiem_tra', $bangDiem->ma_bai_kiem_tra)
                ->delete();
            DB::table('bang_diems')->where('ma_bang_diem', $id)->delete();

            return redirect()->back()->with('success', 'Xóa điểm và lịch sử làm bài thành công!');
        }

        return redirect()->back()->with('error', 'Không tìm thấy bản ghi điểm để xoá.');
    }

}

