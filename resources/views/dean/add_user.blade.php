@extends('layout.dean')
@section('title')
    Thêm người dùng
@endsection
<style>
    .add-user-content {
        padding: 20px;
        margin: 20px;
    }

    /* Container chính của form Thêm Người Dùng */
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

    /* Form bên trong container .add-user */
    .add-user-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .add-user-form div {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        /* Ensures consistent spacing when gap is used on parent */
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
        /* ADDED 'select' here for unified styling */
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
        /* ADDED 'select' here for unified focus styling */
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .option-difficulty {
        /* This class is used for divs containing a label and a select,
           to ensure they use flex layout for proper alignment. */
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .option-difficulty section {
        flex-grow: 1;
        /* Ensures the section (containing the select) takes available space */
    }

    /* Specific styling for selects within .option-difficulty if needed,
       though the .add-user-form select rule above usually covers it well. */
    .option-difficulty select {
        width: 100%;
        /* Make select fill its parent section */
        /* The rest of the styling is already well-defined above */
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%204%205%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M2%200L0%202h4zm0%205L0%203h4z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
        padding-right: 30px;
        /* Add space for the custom arrow */
    }

    .add-user-submit-btn {
        background-color: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: 20px;
        align-self: flex-end;
        /* Aligns button to the right/end of the flex container */
        min-width: 180px;
    }

    .add-user-submit-btn:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    .add-user-submit-btn:active {
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
                {{-- Added mssvGroup --}}
                <div id="mssvGroup">
                    <label for="user_mssv">MSSV</label>
                    <input type="text" id="user_mssv" name="mssv"> {{-- Removed inline style --}}
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
                {{-- Changed lopHocGroup to use option-difficulty class --}}
                <div id="lopHocGroup" class="option-difficulty">
                    <label for="ma_lop">Lớp học</label>
                    <section>
                        <select id="ma_lop" name="ma_lop"> {{-- Removed inline style --}}
                            <option value="">-- Chọn lớp học --</option>
                            @foreach ($danhSachLopHoc as $lopHoc)
                                <option value="{{ $lopHoc->ma_lop_hoc }}">{{ $lopHoc->ten_lop_hoc }}</option>
                            @endforeach
                        </select>
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

                <button type="submit" id="add-user-submit-btn" class="add-user-submit-btn">Thêm Người Dùng</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            // Xử lý lỗi validation từ $errors bag (nếu bạn dùng $request->validate() trong controller)
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
            const maLopSelect = document.getElementById('ma_lop'); // Get the select element for lopHoc
            const mssvInput = document.getElementById('user_mssv'); // Get the input element for mssv

            function updateFields() {
                const value = vaiTroSelect.value;

                // Reset required attributes and display styles first
                hocViGroup.style.display = 'none';
                lopHocGroup.style.display = 'none';
                mssvGroup.style.display = 'none';

                // Remove required attributes by default
                document.getElementById('hoc_vi').removeAttribute('required'); // If you want hoc_vi to be required for giang_vien
                maLopSelect.removeAttribute('required');
                mssvInput.removeAttribute('required');

                // Set required attributes and display styles based on role
                if (value === 'giang_vien') {
                    hocViGroup.style.display = 'flex'; // Use flex for consistency with other inputs
                    // If 'hoc_vi' should be required for giang_vien:
                    // document.getElementById('hoc_vi').setAttribute('required', 'required');
                } else if (value === 'sinh_vien') {
                    lopHocGroup.style.display = 'flex'; // Use flex for consistent layout
                    mssvGroup.style.display = 'flex'; // Use flex for consistent layout
                    maLopSelect.setAttribute('required', 'required');
                    mssvInput.setAttribute('required', 'required');
                }
            }
            vaiTroSelect.addEventListener('change', updateFields);
            updateFields();
        });
    </script>
@endsection