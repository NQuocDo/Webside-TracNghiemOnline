<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\DeanController;


//Trang chủ chưa đăng nhập
Route::get('/', function () {
    return view('dashboard_not_login');
})->name('dashboard_not_login');
//Trang đăng nhập
Route::get('/login', function () {
    return view(view: 'login');
})->name('login');


//Middleware chức năng đăng nhập
Route::controller(App\Http\Controllers\Auth\AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('/login', 'login')->name('auth.login');
        Route::post('/logout', 'logout')->name('logout');
    });

//Kiểm tra đúng vai_tro là sinh_vien thì vào được những uri này
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:sinh_vien'])->group(function () {
        //Trang chủ
        Route::get('/student/dashboard', [StudentController::class, 'layDanhSachMonHoc'])->name('student.dashboard');

        //Thông tin cá nhân sinh viên
        Route::get('/student/info', [StudentController::class, 'hienThiThongTinSinhVien'])->name('student.info');
        Route::post('/student/info', [StudentController::class, 'luuThongTinSinhVien'])->name('student.info_update');
        Route::get('/student/changepassword', function () {
            return view('student.changepassword');
        });
        Route::post('/student/changepassword', [StudentController::class, 'doiMatKhauSinhVien'])->name('student_changepassword');

        //Lịch sử làm bài
        Route::get('/student/history', [StudentController::class, 'hienThiBangDiemSinhVien'])->name('history');
        Route::get('/student/history/{ma_bai_kiem_tra}', [StudentController::class, 'hienThiLichSuLamBai'])
            ->name('history_exam_detail');

        //Danh sách bài kiểm tra + làm bài 
        Route::get('/student/exam', function () {
            return view('student.exam');
        })->name('exam');
        Route::post('/student/exam', [StudentController::class, 'tinhDiemKiemTra'])->name('nop_bai');

        Route::get('/student/exam-list', [StudentController::class, 'hienThiDanhSachBaiKiemTra'])->name('student_exam_list');
        Route::get('/student/exam-list/{id}', [StudentController::class, 'hienThiBaiKiemTraTheoId'])->name('hienThiBaiKiemTraTheoId');

        //Nhận thông báo từ giảng viên
        Route::get('/student/announce', [StudentController::class, 'danhSachThongBaoSinhVien'])->name('student_announce');

        //Liên hệ
        Route::get('/student/contact', function () {
            return view('student.contact');
        });
        Route::post('/student/contact', [StudentController::class, 'guiLienHe'])->name('send_contact');
    });
});


//Kiểm tra đúng vai_tro là giang_vien thì vào được những uri này
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:giang_vien'])->group(function () {
        Route::get('/lecturer/dashboard', [LecturerController::class, 'hienThiTenGiangVien'])->name('lecturer.dashboard');

        //Quản lý câu hỏi{
        Route::get('/lecturer/question', [LecturerController::class, 'hienThiCauHoiVaDapAn'])->name('question');
        Route::post('/lecturer/question/{id}/update-scope', [LecturerController::class, 'capNhatPhamVi'])->name('capNhatPhamVi');//thay đổi phạm vi
        Route::put('/lecturer/question/{id}/update-status', [LecturerController::class, 'capNhatTrangThaiCauHoi'])->name('capNhatTrangThaiCauHoi');//xoá câu hỏi tạm thời
        Route::get('/lecturer/question/{id}/edit', [LecturerController::class, 'hienThiCauHoi'])->name('hienThiCauHoi');//hiển thị câu hỏi ở Trang sửa
        Route::put('/lecturer/question/{id}', [LecturerController::class, 'capNhatCauHoi'])->name('capNhatCauHoi');//sửa câu hỏi
        Route::get('/lecturer/question/addquestion', [LecturerController::class, 'hienThiTrangThemCauHoi'])->name('addquestion');//hiển thị trang thêm câu hỏi
        Route::post('/lecturer/question/addquestion/store', [LecturerController::class, 'themCauHoi'])->name('question.store');//thêm câu hỏi
        Route::get('/lecturer/question-del', [LecturerController::class, 'hienThiCauHoiVaDapAnBiXoa'])->name('question_del');//hiển thị câu hỏi và đáp án bị ẩn
        Route::delete('/lecturer/question/del/{id}', [LecturerController::class, 'xoaCauHoi'])->name('question_del_id');//xoá câu hỏi vĩnh viễn
        Route::put('/lecturer/question_del/{id}/update-status', [LecturerController::class, 'capNhatTrangThaiCauHoi'])->name('capNhatTrangThaiCauHoi');//khôi phục câu hỏi đã xoá
        Route::get('/lecturer/global', [LecturerController::class, 'hienThiCauHoiVaDapAnTrangCongDong'])->name('question_global');//hiển thị câu hỏi lên trang cộng đồng

        //Quản lý đề thi{
        Route::get('/lecturer/exam', [LecturerController::class, 'taoDeThi'])->name('create_exam');
        Route::post('/lecturer/exam/store', [LecturerController::class, 'luuDeThi'])->name('exam_store');
        Route::get('/lecturer/exam-list', [LecturerController::class, 'hienThiDeThi'])->name('exam_list');
        Route::delete('/lecturer/exam-list/del/{id}', [LecturerController::class, 'xoaDeThi'])->name('exam_list_del');
        Route::get('/lecturer/exam-list/{id}', [LecturerController::class, 'chiTietDeThi'])->name('exam_list_detail');
        Route::post('/lecturer/exam-create/store', [LecturerController::class, 'taoBaiKiemTra'])->name('exam_create_store');
        Route::delete('/lecturer/exam-create/del/{id}', [LecturerController::class, 'xoaBaiKiemTra'])->name('exam_create_del');
        Route::put('/lecturer/exam-create/status/{id}', [LecturerController::class, 'thayDoiTrangThaiBaiKiemTra'])->name('exam_create_status');

        //Quản lý thông tin cá nhân của giảng viên
        Route::get('/lecturer/info', [LecturerController::class, 'hienThiThongTinGiangVien'])->name('lecturer_info');
        Route::post('/lecturer/info', [LecturerController::class, 'luuThongTinGiangVien'])->name('lecturer_info_update');
        Route::get('/lecturer/changepassword', function () {
            return view('lecturer.lecturer_changepassword');
        });

        //{Quản lý sinh viên dang dạy
        Route::get('/lecturer/student-list', [LecturerController::class, 'hienThiDanhSachSinhVien'])->name('student_list');//hiển thị danh sách sinh viên
        Route::post('/lecturer/student-list/update', [LecturerController::class, 'doiMatKhauSinhVien'])->name('student_list_update');//đổi mật khẩu sinh viên
        Route::put('/lecturer/student-list/{id}/status', [LecturerController::class, 'thayDoiTrangThaiSinhVien'])->name('student_management_status');//khoá và mở tài khoản sinh viên

        //{Quản lý môn học
        Route::get('/lecturer/subject-list', [LecturerController::class, 'hienThiMonGiangVienDay'])->name('subject_list');//hiển thị danh sách môn học giảng viên đang dạy
        //}

        //Quản lý liên hệ
        Route::get('/lecturer/contact', [LecturerController::class, 'hienThiLienHe'])->name('lecturer_contact');
        Route::delete('/lecturer/contact/{id}', [LecturerController::class, 'xoaLienHe'])->name('lecturer_contact_del');


        //{Quản lý thông báo
        Route::get('/lecturer/announce', [LecturerController::class, 'layDsLopHoc'])->name('announce');//chuyển trang thông báo
        Route::post('/lecturer/announce', [LecturerController::class, 'guiThongBao'])->name('announce');//gửi thông báo
        Route::get('/lecturer/announce-list', [LecturerController::class, 'hienThiDanhSachThongBao'])->name('announce_list');//hiển thị danh sách thông báo
        Route::delete('/lecturer/announce-list/{id}', [LecturerController::class, 'xoaThongBao'])->name('announce_list_del');

        Route::get('/lecturer/scoreboard', [LecturerController::class, 'hienThiBangDiem'])->name('score_board');
        Route::delete('/score/{id}', [LecturerController::class, 'xoaDiemSinhVien'])->name('scoreboard_del');
    });
});


//Kiểm tra đúng vai_tro là truong_khoa thì vào được những uri này
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:truong_khoa'])->group(function () {

        Route::get('/dean/dashboard', [DeanController::class, 'hienThiTenTruongKhoa'])->name('dean.dashboard');

        //Quản lý sinh viên{
        Route::get('/dean/student-management', [DeanController::class, 'hienThiDanhSachSinhVien'])->name('student_management');//hiển thị danh sách sinh viên
        Route::delete('dean/student-management/{id}/delete', [DeanController::class, 'xoaSinhVien'])->name('student_management_delete');//Xoá sinh viên
        //}

        //Quản lý môn học{
        Route::get('/dean/subject-management', [DeanController::class, 'hienThiMonHoc'])->name('subject_management');//hiển thị danh sách môn học
        Route::post('/dean/subject-management/store', [DeanController::class, 'themMonHoc'])->name('subject_management.store');//thêm môn học
        Route::delete('/dean/subject-management/del/{id}', [DeanController::class, 'xoaMonHoc'])->name('subject_management_del');//xoá môn học vĩnh viễn
        //}

        //Quản lý giảng viên{
        Route::get('/dean/lecturer-list', [DeanController::class, 'hienThiDanhSachGiangVien'])->name('lecturer_list');
        Route::put('/dean/lecturer-list/{id}/status', [DeanController::class, 'thayDoiTrangThaiGiangVien'])->name('lecturer_list_status');//khoá và mở tài khoản sinh viên
        Route::delete('/dean/lecturer-list/del/{id}', [DeanController::class, 'xoaGiangVien'])->name('lecturer_list_del');//xoá môn học vĩnh viễn
        Route::post('/dean/lecturer-list/change-password', [DeanController::class, 'doiMatKhauGiangVien'])->name('lecturer_list_changepassword');

        //}

        //Quản lý quyền dạy học{
        Route::get('/dean/decentralization', [DeanController::class, 'hienthiQuyenGiangDay'])->name('decentralization');
        Route::post('/dean/decentralization/store', [DeanController::class, 'themQuyenDayHoc'])->name('decentralization.store');//thêm quyền dạy học
        Route::delete('/dean/decentralization/del/{id}', [DeanController::class, 'xoaQuyenDayHoc'])->name('decentralization_del');
        //}

        //Quản lý ngân hàng câu hỏi{
        Route::get('/dean/question-bank', [DeanController::class, 'hienThiDanhSachCauHoiTheoBoLoc'])->name('question_bank');//xem danh sách câu hỏi giảng viên
        //}

        Route::get('/dean/department-statis', [DeanController::class, 'hienThiThongKe'])->name('department_statis');

        //Quản lý thêm người dùng
        Route::get('/dean/add-user', [DeanController::class, 'hienThiThongTinLopHoc'])->name('add_user');
        Route::post('/dean/add-user/store', [DeanController::class, 'themNguoiDung'])->name('add_user.store');
        //}

        Route::get('/dean/add-class', [DeanController::class, 'hienThiLopHoc'])->name('add_class');
        Route::post('/dean/add-class/store', [DeanController::class, 'themLopHoc'])->name('add_class_store');
        Route::delete('/dean/add-class/del/{id}', [DeanController::class, 'xoaLopHoc'])->name('add_class_del');
    });
});