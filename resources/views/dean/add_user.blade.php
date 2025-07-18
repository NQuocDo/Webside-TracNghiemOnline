@extends('layout.dean')
@section('title')
    Thêm người dùng
@endsection
<style>
    .add-user-content {
        padding: 20px;
        margin: 20px;
    }

    .add-user {
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #e0e0e0;
        background-color: #f8f9fa;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        max-width: 600px;
        margin: 40px auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .add-user h2 {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .add-user-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .add-user-form div {
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .add-user-form label {
        width: 35%;
        min-width: 120px;
        color: #555;
        font-size: 16px;
        font-weight: 500;
        margin-right: 15px;
        text-align: right;
    }

    .add-user-form input[type="text"],
    .add-user-form input[type="password"],
    .add-user-form input[type="date"],
    .add-user-form input[type="file"],
    .add-user-form select {
        flex-grow: 1;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        color: #333;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .add-user-form input[type="text"]:focus,
    .add-user-form input[type="password"]:focus,
    .add-user-form input[type="date"]:focus,
    .add-user-form input[type="file"]:focus,
    .add-user-form select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .option-difficulty {
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .option-difficulty section {
        flex-grow: 1;
    }

    .option-difficulty select {
        width: 100%;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%204%205%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M2%200L0%202h4zm0%205L0%203h4z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
        padding-right: 30px;
    }

    .button-group {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .add-user-submit-btn,
    .add-user-submit-btn-import-excel {
        background-color: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        min-width: 180px;
    }

    .add-user-submit-btn:hover,
    .add-user-submit-btn-import-excel:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    .add-user-submit-btn:active,
    .add-user-submit-btn-import-excel:active {
        background-color: #1e7e34;
        transform: translateY(0);
    }
</style>
@section('content')
    <div class="add-user-content">
        <div class="add-user" id="add-user">
            <h2>Thêm Người Dùng</h2>
            <form action="{{ url('/dean/add-user/store') }}" method="POST" class="add-user-form" id="add-user-form"
                enctype="multipart/form-data">
                @csrf
                <div id="mssvGroup">
                    <label for="user_mssv">MSSV</label>
                    <input type="text" id="user_mssv" name="mssv">
                </div>
                <div>
                    <label for="user_email">Email</label>
                    <input type="text" id="user_email" name="email" required>
                </div>
                <div>
                    <label for="user_password">Mật khẩu</label>
                    <input type="password" id="user_password" name="mat_khau" required>
                </div>
                <div>
                    <label for="user_name">Họ Tên</label>
                    <input type="text" id="user_name" name="ho_ten" required>
                </div>
                <div id="hocViGroup">
                    <label for="hoc_vi">Học vị</label>
                    <input type="text" id="hoc_vi" name="hoc_vi" placeholder="Chỉ nhập nếu là giảng viên">
                </div>
                <div id="nienKhoacGroup">
                    @php
                        $now = now()->year;
                        $min = $now - 2;
                        $max = $now + 3;
                    @endphp
                    <label for="khoa_hoc">Niên khóa</label>
                    <select id="khoa_hoc" name="khoa_hoc" required>
                        <option value="">-- Chọn niên khóa --</option>
                        @for ($i = $min; $i <= $max; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div id="lopHocGroup">
                    <label for="ma_lop">Lớp học</label>
                    <select id="ma_lop" name="ma_lop" required>
                        <option value="">-- Chọn lớp học --</option>
                    </select>
                </div>
                <input type="hidden" name="hoc_ky" value="1">
                <div>
                    <label for="user_gender">Giới tính</label>
                    <select id="user_gender" name="gioi_tinh" required>
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>
                <div>
                    <label for="user_dob">Ngày sinh</label>
                    <input type="date" id="user_dob" name="ngay_sinh" required>
                </div>
                <div>
                    <label for="user_address">Địa chỉ</label>
                    <input type="text" id="user_address" name="dia_chi">
                </div>
                <div>
                    <label for="user_phone">Số điện thoại</label>
                    <input type="text" id="user_phone" name="so_dien_thoai">
                </div>
                <div>
                    <label for="user_account_status">Trạng thái tài khoản</label>
                    <select id="user_account_status" name="trang_thai_tai_khoan" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="hoat_dong">Hoạt động</option>
                        <option value="khong_hoat_dong">Không hoạt động</option>
                    </select>
                </div>
                <div>
                    <label for="user_role">Vai trò</label>
                    <select id="user_role" name="vai_tro" required>
                        <option value="">-- Chọn vai trò --</option>
                        <option value="sinh_vien">Sinh viên</option>
                        <option value="giang_vien">Giảng viên</option>
                    </select>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Thêm Người Dùng</button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#excelImportModal">
                        Thêm Người Dùng (Excel)
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="excelImportModal" tabindex="-1" aria-labelledby="excelImportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('add_user_import_excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="excelImportModalLabel">Import Người Dùng từ Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Chọn vai trò -->
                        <div class="mb-3">
                            <label for="vai_tro" class="form-label">Chọn Vai Trò</label>
                            <select class="form-select" name="vai_tro" id="vai_tro" required>
                                <option value="">-- Chọn Vai Trò --</option>
                                <option value="sinh_vien">Sinh viên</option>
                                <option value="giang_vien">Giảng viên</option>
                            </select>
                        </div>

                        <!-- Input file -->
                        <div class="mb-3">
                            <label for="file_excel" class="form-label">Chọn File Excel</label>
                            <input type="file" class="form-control" id="file_excel" name="file_excel" accept=".xlsx,.xls"
                                required>
                            <label class="font-semibold d-block mt-3">Định dạng file Excel cho sinh viên:</label>
                            <div class="bg-light p-2 rounded border text-sm">
                                <code>Họ tên</code> <code>Email</code> <code>Mã số sinh viên</code> <code>Mật khẩu</code>
                                <code>Giới tính</code>
                                <code>Ngày sinh</code> <code>Địa chỉ</code> <code>Số điện thoai</code>
                                <code>Lớp</code> <code>Học kỳ</code> <code>Năm học</code>
                            </div>
                            <label class="font-semibold d-block mt-3">Định dạng file Excel cho giảng viên:</label>
                            <div class="bg-light p-2 rounded border text-sm">
                                <code>Họ tên</code> <code>Email</code><code>Mật khẩu</code>
                                <code>Giới tính</code>
                                <code>Ngày sinh</code> <code>Địa chỉ</code> <code>Số điện thoai</code>
                                <code>Học vị</code>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Gửi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const vaiTroSelect = $('#user_role');
            const hocViGroup = $('#hocViGroup');
            const lopHocGroup = $('#lopHocGroup');
            const mssvGroup = $('#mssvGroup');
            const nienKhoacGroup = $('#nienKhoacGroup');

            const khoaHocSelect = $('#khoa_hoc');
            const lopHocSelect = $('#ma_lop');

            function updateFormFields(role) {
                // Ẩn tất cả nhóm trước
                hocViGroup.hide();
                lopHocGroup.hide();
                mssvGroup.hide();
                nienKhoacGroup.hide();

                // Xóa required mặc định
                khoaHocSelect.prop('required', false);
                lopHocSelect.prop('required', false);

                if (role === 'sinh_vien') {
                    lopHocGroup.show();
                    mssvGroup.show();
                    nienKhoacGroup.show();

                    khoaHocSelect.prop('required', true);
                    lopHocSelect.prop('required', true);
                } else if (role === 'giang_vien') {
                    hocViGroup.show();
                }
            }

            // Khi thay đổi vai trò
            vaiTroSelect.change(function () {
                const selectedRole = $(this).val();
                updateFormFields(selectedRole);
            });

            // Gọi khi trang load
            const selectedRole = '{{ old('vai_tro') ?? '' }}';
            if (selectedRole) {
                vaiTroSelect.val(selectedRole);
            }
            updateFormFields(vaiTroSelect.val());

            // Khi thay đổi niên khóa thì gọi API để load lớp học
            khoaHocSelect.change(function () {
                const khoa = $(this).val();
                lopHocSelect.html('<option value="">Đang tải lớp học...</option>');

                if (khoa) {
                    $.get(`/get-class-by-year/${khoa}`, function (data) {
                        let html = '<option value="">-- Chọn lớp học --</option>';
                        data.forEach(function (lop) {
                            html += `<option value="${lop.ma_lop_hoc}">${lop.ten_lop_hoc}</option>`;
                        });
                        lopHocSelect.html(html);
                    });
                } else {
                    lopHocSelect.html('<option value="">-- Chọn lớp học --</option>');
                }
            });

            // Hiển thị thông báo từ server
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi nhập liệu!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: true
                });
            @endif
        });
    </script>

@endsection