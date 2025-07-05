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
use App\Models\LopHoc;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;



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
            foreach ($giangVien->lopHocs as $lop) {
                $sinhVien = $sinhVien->merge($lop->sinhViens);
            }
            return view('lecturer.dashboard')
                ->with('user', $user)
                ->with('monHoc', $monHoc)
                ->with('cauHoi', $cauHoi)
                ->with('baiKiemTra', $baiKiemTra)
                ->with('sinhVien', $sinhVien);
        }
        return redirect('/login');
    }
    //hiển thị thông tin cá nhân giảng viên
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
    //hiển thị các môn giảng viên dang dạy
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
        $danhSachMonHoc = $giangVien->monHocs()->paginate(5);

        return view('lecturer.subject_list')->with('danhSachMonHoc', $danhSachMonHoc);
    }
    //hiển thi câu hỏi và đáp án giảng viên đã tạo
    public function hienThiCauHoiVaDapAn(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->vai_tro !== "giang_vien") {
            return redirect()->back()->with("error", "Bạn không có quyền truy cập trang này.");
        }

        // Lấy giảng viên theo người dùng
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

        $thongTinCauHoi = CauHoi::with(['dapAns', 'monHoc'])
            ->whereIn('ma_mon_hoc', $maMonHocs)
            ->where('trang_thai', 'hien');
        $tuKhoaTimKiem = $request->input('tu_khoa_tim_kiem');
        if ($tuKhoaTimKiem) {
            $thongTinCauHoi->where('noi_dung', 'like', '%' . $tuKhoaTimKiem . '%');
        }

        // Lọc theo môn học cụ thể
        $locTheoMocHoc = $request->input('mon_hoc_id');
        if ($locTheoMocHoc) {
            $thongTinCauHoi->where('ma_mon_hoc', $locTheoMocHoc);
        }

        $danhSachCauHoi = $thongTinCauHoi->paginate(10);

        return view('lecturer.question', [
            'danhSachCauHoi' => $danhSachCauHoi,
            'danhSachMonHoc' => $danhSachMonHoc,
            'locTheoMocHoc' => $locTheoMocHoc,
            'tuKhoaTimKiem' => $tuKhoaTimKiem,
            'user' => $user,
        ]);
    }
    //sửa phạm vi công khai hoặc riêng tư
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
    //xoá câu hỏi tạm thời
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

    //hiển thị câu hỏi ở trang sửa
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
    //sửa câu hỏi 
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

    // hiển thị trang thêm câu hỏi
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
            ->get();

        return view('lecturer.addquestion')->with('danhSachMonHoc', $danhSachMonHoc);
    }
    //thêm câu hỏi
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

    //hiển thị câu hỏi đã bị xoá tạm thời
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
    //xoá câu hỏi vĩnh viễn 
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
    //hiển thị câu hỏi có trạng thái công khai
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
    //hiển thị danh sách thông báo của giảng viên
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

        $danhSachThongBao = ThongBao::where('nguoi_gui', $giangVienId)->paginate(10);

        return view('lecturer.announce_list')->with('danhSachThongBao', $danhSachThongBao);
    }
    //hiển thị danh sách lớp trên trang thông báo
    public function layDsLopHoc()
    {
        $nguoiDung = Auth::user();
        $giangVien = GiangVien::where('ma_nguoi_dung', $nguoiDung->ma_nguoi_dung)->first();
        $danhSachLopHoc = $giangVien->lopHocs;
        return view('lecturer.announce')->with('danhSachLopHoc', $danhSachLopHoc);
    }
    //gửi thông báo đến sinh viên
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
        $thongBao->ma_lop_hoc = $request->input('class');
        $thongBao->nguoi_gui = $maGiangVien;
        $thongBao->save();

        return redirect()->route('announce')->with('success', 'Gửi thông báo thành công');

    }
    //xoá thông báo khỏi danh sách thông báo
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
    //hiển thị danh sách sinh viên mà giảng viên dạy
    public function hienThiDanhSachSinhVien(Request $request)
    {
        $user = Auth::user();

        $giangVien = GiangVien::where('ma_nguoi_dung', $user->ma_nguoi_dung)->first();
        $keyword = $request->query('keyword');
        $lopHoc = $giangVien->lopHocs;
        $danhSachSinhVien = collect();
        foreach ($lopHoc as $lop) {
            $sinhViens = $lop->sinhViens;
            if ($keyword) {
                $sinhViens = $sinhViens->filter(function ($sv) use ($keyword) {
                    return stripos($sv->nguoiDung->ho_ten, $keyword) !== false;
                });
            }
            $danhSachSinhVien = $danhSachSinhVien->merge($sinhViens);
        }
        $danhSachSinhVien = $danhSachSinhVien->unique('ma_sinh_vien')->values();

        return view('lecturer.student_list')->with('danhSachSinhVien', $danhSachSinhVien)->with('keyword', $keyword);
    }
    //đổi mật khẩu sinh viên
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
    //khoá sinh viên
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
    //tạo dề thi với 2 loại ngẫu nhiên và chọn đáp án
    public function taoDeThi(Request $request)
    {
        
        if ($request->filled('ma_mon_hoc') && $request->filled('so_luong')) {
            $maMonHoc = $request->input('ma_mon_hoc');
            $soLuong = (int) $request->input('so_luong');

            // Lấy ngẫu nhiên các câu hỏi có trạng thái 'hien' và môn học đúng
            $cauHoiDaChon = CauHoi::with('dapAns')
                ->where('ma_mon_hoc', $maMonHoc)
                ->where('trang_thai', 'hien')
                ->inRandomOrder()
                ->take($soLuong)
                ->get();
        } elseif ($request->has('ma_cau_hoi')) {
            $danhSachIdCauHoi = $request->input('ma_cau_hoi');
            $cauHoiDaChon = CauHoi::with('dapAns')
                ->whereIn('ma_cau_hoi', $danhSachIdCauHoi)
                ->get();

            $maMonHoc = optional($cauHoiDaChon->first())->ma_mon_hoc;
        } else {
            return redirect()->back()->with('error', 'Vui lòng chọn câu hỏi hoặc nhập số lượng và môn học.');
        }

        return view('lecturer.exam')->with('cauHoiDaChon', $cauHoiDaChon)
            ->with('maMonHoc', $maMonHoc);
    }
    //lưu đề thi vào cở sở dữ liệu và lưu câu hỏi và dề thi vào bảng chi tiết đề thi và câu hoi
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
        foreach ($maCauHois as $maCauHoi) {
            $maCTCH = 'CT' . substr(uniqid(), -4) . rand(1, 9);
            DB::table('chi_tiet_de_thi_va_cau_hois')->insert([
                'ma_chi_tiet_dtch' => $maCTCH,
                'ma_de_thi' => $dethi->ma_de_thi,
                'ma_cau_hoi' => $maCauHoi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('question')->with('success', 'Tạo đề thi thành công!');
    }
    //hiển thị danh sách đề thi
    public function hienThiDeThi(Request $request)
    {
        $giangVien = auth()->user()->giangVien;
        $ma_giang_vien = $giangVien->ma_giang_vien;
        $maMonHocLoc = $request->input('ma_mon_hoc');
        $danhSachMonHoc = $giangVien->monHocs;
        $query = DeThi::with(['baiKiemTras', 'monHoc'])
            ->where('ma_giang_vien', $ma_giang_vien)
            ->where('trang_thai', 'hien');

        if ($maMonHocLoc) {
            $query->where('ma_mon_hoc', $maMonHocLoc);
        }
        $danhSachDeThi = $query->get();

        return view('lecturer.exam_list', [
            'danhSachDeThi' => $danhSachDeThi,
            'danhSachMonHoc' => $danhSachMonHoc,
            'maMonHocLoc' => $maMonHocLoc
        ]);
    }
    //xoá đề thi
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


    //hiển thi câu hỏi đáp án ở trang review đề thi
    public function chiTietDeThi($id)
    {

        $deThi = DeThi::with('cauHoi.dapAns')->find($id);
        if (!$deThi) {
            return redirect()->route('exam_list')->with('error', 'Không tìm thấy đề thi');
        }

        return view('lecturer.exam_detail')->with('deThi', $deThi);
    }

    //tạo bài kiểm tra 
    public function taoBaiKiemTra(Request $request)
    {
        $request->validate([
            'ma_de_thi' => 'required|exists:de_this,ma_de_thi',
            'ten_bai_kiem_tra' => 'required|string|max:255',
        ]);

        $deThi = DeThi::findOrFail($request->ma_de_thi);

        BaiKiemTra::create([
            'ma_de_thi' => $deThi->ma_de_thi,
            'ten_bai_kiem_tra' => $request->ten_bai_kiem_tra,
            'trang_thai' => 'khoa',
        ]);

        return redirect()->back()->with('success', 'Tạo bài kiểm tra thành công!');
    }
    //xoá bài kiểm tra
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
            $baiKiemTra->delete();
            return redirect()->back()->with('success', 'Xóa bài kiểm tra thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi xóa: ' . $e->getMessage());
        }
    }
    //thay đôi trạng thái đóng mở của bài kiểm tra
    public function thayDoiTrangThaiBaiKiemTra($id)
    {
        $baiKiemTraId = BaiKiemTra::find($id);

        $baiKiemTraId->trang_thai = $baiKiemTraId->trang_thai === 'mo' ? 'khoa' : 'mo';
        $baiKiemTraId->thoi_gian_khoa = now();
        $baiKiemTraId->save();

        return redirect()->back()->with('success', 'Trạng thái bài kiểm tra đã được cập nhật.');
    }
    //hiển thị liên hệ
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
    //xoá liên hệ
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
    //hiển thị bảng điểm
    public function hienThiBangDiem(Request $request)
    {
        $maNguoiDung = Auth::user()->ma_nguoi_dung;
        $maGiangVien = GiangVien::where('ma_nguoi_dung', $maNguoiDung)->value('ma_giang_vien');

        $maLop = $request->input('lop');
        $maMon = $request->input('mon');

        $bangDiemTruyVan = DB::table('sinhviens as sv')
            ->join('nguoidungs as nd', 'sv.ma_nguoi_dung', '=', 'nd.ma_nguoi_dung')
            ->join('lop_hocs as lh', 'sv.ma_lop_hoc', '=', 'lh.ma_lop_hoc')
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
        $bangDiem = $bangDiemTruyVan->paginate(10);

        $danhSachLop = DB::table('phan_quyen_days as pq')
            ->join('lop_hocs as lh', 'pq.ma_lop_hoc', '=', 'lh.ma_lop_hoc')
            ->where('pq.ma_giang_vien', $maGiangVien)
            ->select('lh.ma_lop_hoc', 'lh.ten_lop_hoc')
            ->distinct()
            ->get();

        $danhSachMon = DB::table('phan_quyen_days as pq')
            ->join('mon_hocs as mh', 'pq.ma_mon_hoc', '=', 'mh.ma_mon_hoc')
            ->where('pq.ma_giang_vien', $maGiangVien)
            ->select('mh.ma_mon_hoc', 'mh.ten_mon_hoc')
            ->distinct()
            ->get();

        return view('lecturer.score_board')
            ->with('bangDiem', $bangDiem)
            ->with('danhSachLop', $danhSachLop)
            ->with('danhSachMon', $danhSachMon)
            ->with('maLop', $maLop)
            ->with('maMon', $maMon);
    }
    //xoá điểm sinh viên
    public function xoaDiemSinhVien($id)
    {
        DB::table('bang_diems')->where('ma_bang_diem', $id)->delete();

        return redirect()->back()->with('success', 'Xóa điểm thành công!');
    }

}

