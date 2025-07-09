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
                    <label for="user_email">Email</label> <input type="text" id="user_email" name="email" required>
                </div>
                <div>
                    <label for="user_password">Mật khẩu</label> <input type="password" id="user_password" name="mat_khau"
                        required>
                </div>
                <div>
                    <label for="user_name">Họ Tên</label> <input type="text" id="user_name" name="ho_ten" required>
                </div>
                <div id="hocViGroup">
                    <label for="hoc_vi">Học vị</label>
                    <input type="text" id="hoc_vi" name="hoc_vi" placeholder="Chỉ nhập nếu là giảng viên">
                </div>
                <div id="lopHocGroup" class="option-difficulty">
                    <label for="ma_lop">Lớp học</label>
                    <section>
                        <select id="ma_lop" name="ma_lop">
                            <option value="">-- Chọn lớp học --</option>
                            @foreach ($danhSachLopHoc as $lopHoc)
                                <option value="{{ $lopHoc->ma_lop_hoc }}">{{ $lopHoc->ten_lop_hoc }}</option>
                            @endforeach
                        </select>
                    </section>
                </div>
                <div id="hocKyGroup" class="option-difficulty">
                    <label for="hoc_ky">Học kỳ</label>
                    <section>
                        <select id="hoc_ky" name="hoc_ky" required>
                            <option value="">-- Chọn học kỳ --</option>
                            <option value="1">Học kỳ 1</option>
                            <option value="2">Học kỳ 2</option>
                            <option value="3">Học kỳ 3</option>
                            <option value="4">Học kỳ 4</option>
                            <option value="5">Học kỳ 5</option>
                        </select>
                    </section>
                </div>
                <div id="namHocGroup" class="option-difficulty">
                    <label for="nam_hoc">Năm học</label>
                    <section>
                        <input type="number" name="nam_hoc" id="nam_hoc" placeholder="VD: 2025" required>
                    </section>
                </div>
                <div class="option-difficulty">
                    <label for="user_gender">Giới tính</label>
                    <section>
                        <select id="user_gender" name="gioi_tinh" required>
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </section>
                </div>
                <div>
                    <label for="user_dob">Ngày sinh</label> <input type="date" id="user_dob" name="ngay_sinh" required>
                </div>
                <div>
                    <label for="user_address">Địa chỉ</label> <input type="text" id="user_address" name="dia_chi">
                </div>
                <div>
                    <label for="user_phone">Số điện thoại</label> <input type="text" id="user_phone" name="so_dien_thoai">
                </div>
                <div class="option-difficulty">
                    <label for="user_account_status">Trạng thái tài khoản</label>
                    <section>
                        <select id="user_account_status" name="trang_thai_tai_khoan" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="hoat_dong">Hoạt động</option>
                            <option value="khong_hoat_dong">Không hoạt động</option>
                        </select>
                    </section>
                </div>
                <div class="option-difficulty">
                    <label for="user_role">Vai trò</label>
                    <section>
                        <select id="user_role" name="vai_tro" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="sinh_vien">Sinh viên</option>
                            <option value="giang_vien">Giảng viên</option>
                        </select>
                    </section>
                </div>

                <div class="button-group">
                    <button type="submit" id="add-user-submit-btn" class="add-user-submit-btn">Thêm Người Dùng</button>
                    <button type="button" id="add-user-submit-btn-import-excel"
                        class="add-user-submit-btn-import-excel">Thêm Người Dùng Excel</button>
                </div>
            </form>
        </div>
        <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importExcelLabel">Import Người Dùng bằng Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('add_user_import_excel') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="vai_tro" class="form-label">Loại người dùng:</label>
                                <select class="form-select" id="vai_tro" name="vai_tro" required>
                                    <option value="" disabled selected>-- Chọn loại người dùng --</option>
                                    <option value="sinh_vien">Sinh viên</option>
                                    <option value="giang_vien">Giảng viên</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="excelFile" class="form-label">Chọn file Excel:</label>
                                <input type="file" class="form-control" id="excelFile" name="file" accept=".xlsx,.xls,.csv"
                                    required>
                            </div>
                            <div class="form-text text-muted mt-1">
                                <div>
                                    <strong>Gợi ý:</strong> File Excel nên có các cột: <br>
                                    <code>Họ tên</code>, <code>Email</code>, <code>Mật khẩu</code>, <code>Giới tính</code>,
                                    <code>Ngày sinh</code>, <code>Địa chỉ</code>, <code>Số điện thoại</code>,
                                    <code>Học vị</code><br>
                                    <em>(Vai trò: Giảng viên)</em>
                                </div>
                                <div>
                                    <code>Họ tên</code>,<code>Mã số sinh viên</code>, <code>Email</code>,
                                    <code>Mật khẩu</code>, <code>Giới tính</code>,
                                    <code>Ngày sinh</code>, <code>Địa chỉ</code>, <code>Số điện thoại</code>,
                                    <code>Lớp</code><br>
                                    <em>(Vai trò: Sinh viên)</em>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const importBtn = document.getElementById("add-user-submit-btn-import-excel");
            if (importBtn) {
                importBtn.addEventListener("click", function () {
                    const modal = new bootstrap.Modal(document.getElementById('importExcelModal'));
                    modal.show();
                });
            }
            // Kiểm tra thông báo thành công từ session flash
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false, // Tự động đóng sau một khoảng thời gian
                    timer: 2000 // Tự động đóng sau 2 giây
                });
            @endif

            // Kiểm tra thông báo lỗi từ session flash
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true // Giữ thông báo lỗi cho người dùng đọc
                });
            @endif
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi nhập liệu!',
                    html: `
                                                                                            <ul>
                                                                                                @foreach ($errors->all() as $error)
                                                                                                    <li>{{ $error }}</li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                            `,
                    showConfirmButton: true
                });
            @endif

            const vaiTroSelect = document.getElementById('user_role');
            const hocViGroup = document.getElementById('hocViGroup');
            const lopHocGroup = document.getElementById('lopHocGroup');
            const mssvGroup = document.getElementById('mssvGroup');
            const hocKyGroup = document.getElementById('hocKyGroup');
            const namHocGroup = document.getElementById('namHocGroup');

            const maLopSelect = document.getElementById('ma_lop');
            const mssvInput = document.getElementById('user_mssv');
            const hocKySelect = document.getElementById('hoc_ky');
            const namHocInput = document.getElementById('nam_hoc');

            function updateFields() {
                const value = vaiTroSelect.value;

                // Reset display
                hocViGroup.style.display = 'none';
                lopHocGroup.style.display = 'none';
                mssvGroup.style.display = 'none';
                hocKyGroup.style.display = 'none';
                namHocGroup.style.display = 'none';

                // Reset required
                document.getElementById('hoc_vi').removeAttribute('required');
                maLopSelect.removeAttribute('required');
                mssvInput.removeAttribute('required');
                hocKySelect.removeAttribute('required');
                namHocInput.removeAttribute('required');

                if (value === 'giang_vien') {
                    hocViGroup.style.display = 'flex';
                    // document.getElementById('hoc_vi').setAttribute('required', 'required');
                } else if (value === 'sinh_vien') {
                    lopHocGroup.style.display = 'flex';
                    mssvGroup.style.display = 'flex';
                    hocKyGroup.style.display = 'flex';
                    namHocGroup.style.display = 'flex';

                    maLopSelect.setAttribute('required', 'required');
                    mssvInput.setAttribute('required', 'required');
                    hocKySelect.setAttribute('required', 'required');
                    namHocInput.setAttribute('required', 'required');
                }
            }

            vaiTroSelect.addEventListener('change', updateFields);
            updateFields(); // Gọi ban đầu khi trang load

        });
    </script>
@endsection