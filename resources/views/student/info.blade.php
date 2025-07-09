@extends('layout.student_layout')
@section('title')
    Trang cá nhân
@endsection
<style>
    .student-account-content {
        display: grid;
        grid-template-columns: 300px 1fr;
        padding-left: 50px;
        gap: 50px;
    }

    .student-account-item {
        border-radius: 5px;
        border: 1px solid gainsboro;
        padding: 10px;
    }

    .item-left .item {
        margin: 10px;
        padding: 10px;
    }

    .item-left .item a {
        color: black;
        text-decoration: none;
    }

    .item-left .item:hover {
        background-color: rgb(240, 243, 252);
        border: 1px solid gainsboro;
    }

    .item-right .form-account {
        padding: 15px;
    }

    .form-account label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .form-account span.required {
        color: red;
    }

    .form-account input,
    .form-account select {
        padding: 10px;
        border-radius: 5px;
        width: 300px;
        border: 1px solid gainsboro;
        font-size: 14px;
    }

    .save-button {
        padding: 10px;
        border-radius: 5px;
        width: 300px;
        background-color: #59c0f7;
        border: none;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .student-account-content {
            display: flex;
            flex-direction: column;
            padding: 15px;
            gap: 20px;
        }

        .form-account input,
        .form-account select,
        .save-button {
            width: 100%;
        }

        .student-account-item {
            margin-right: 0 !important;
        }

        h4 {
            padding-left: 15px !important;
            font-size: 18px;
        }
    }

    @media (max-width: 480px) {
        .form-account label {
            font-size: 14px;
        }

        .form-account input,
        .form-account select {
            font-size: 13px;
            padding: 8px;
        }

        .save-button {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>

@section('content')
    <div class="student-account">
        <h4 style="padding-left: 25px;padding-bottom:15px;padding-top:15px;"> Cài đặt tài khoản</h4>
        <div class="student-account-content">
            <div class="student-account-item" style="height: 20vh;">
                <div class="item-left">
                    <div class="item"><a href="{{ asset('student/info') }}">Thông tin tài khoản</a></div>
                    <div class="item"><a href="{{ asset('student/changepassword') }}">Đổi mật khẩu</a></div>
                </div>
            </div>
            <form action="{{ route('student.info_update') }}" method="POST">
                @csrf
                <div class="student-account-item" style="margin-right:50px">
                    <div class="item-right">
                        <div class="form-account">
                            <label for="email">Email<span class="required"></span></label>
                            <input type="text" id="email" value="{{ $user->email }} " disabled>
                        </div>
                        <div class="form-account">
                            <label for="email">Lớp<span class="required"></span></label>
                            <input type="text" id="lop_hien_tai"
                                value="{{ optional($user->sinhVien->lopHienTai->lopHoc)->ten_lop_hoc ?? 'Chưa có lớp hiện tại' }}"
                                disabled>
                        </div>
                        <div class="form-account">
                            <label for="ho_ten">Họ tên<span class="required"></span></label>
                            <input type="text" name="ho_ten" id="ho_ten" value="{{ $user->ho_ten }}">
                        </div>
                        <div class="form-account">
                            <label for="so_dien_thoai">Số điện thoại<span class="required"></span></label>
                            <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="{{ $user->so_dien_thoai }}">
                        </div>
                        <div class="form-account">
                            <label for="dia_chi">Địac chỉ<span class="required"></span></label>
                            <input type="text" name="dia_chi" id="dia_chi" value="{{ $user->dia_chi }} ">
                        </div>
                        <div class="form-account">
                            <label for="gender_select">Giới tính <span class="required"></span></label>
                            <div class="custom-select-wrapper">
                                <select id="gender_select" name="gioi_tinh"
                                    style="padding: 10px; border-radius: 5px; width: 300px; border:1px solid gainsboro;">
                                    <option value="" disabled {{ $user->gioi_tinh == null ? 'selected' : '' }}>Chọn giới tính
                                    </option>
                                    <option value="Nam" {{ $user->gioi_tinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $user->gioi_tinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-account">
                            <label for="dob_input">Ngày sinh <span class="required">*</span></label>
                            <div class="input-with-icon-wrapper">
                                <input type="date" id="ngay_sinh" name="ngay_sinh" value="{{ $user->ngay_sinh }}">
                            </div>
                        </div>
                        <div class="form-account save-button-container">
                            <button type="submit" class="save-button"
                                style="padding: 10px; border-radius: 5px; width: 300px;background-color: #59c0f7;">Lưu</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Thất bại!',
                text: @json(session('error')),
                confirmButtonText: 'Đóng'
            });
        </script>
    @endif
@endsection