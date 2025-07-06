@extends('layout.student_layout')
@section('title')
    Trang Đổi mật khẩu
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
    }

    .form-account span {
        color: red;
    }

    .item-right .form-account input {
        padding: 10px;
        border-radius: 5px;
        width: 300px;
        border: 1px solid gainsboro;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrapper input {
        width: 100%;
    }

    .eye-icon {
        position: absolute;
        right: 10px;
        cursor: pointer;
        color: #555;
    }

    .change-password-btn {
        padding: 10px;
        border-radius: 5px;
        width: 300px;
        background-color: #59c0f7;
        margin-left: 15px;
        border: none;
        color: white;
        font-weight: bold;
    }

    .alert {
        margin: 15px;
        padding: 10px;
    }

    /* ---------------------- RESPONSIVE ---------------------- */
    @media (max-width: 768px) {
        .student-account-content {
            display: flex;
            flex-direction: column;
            padding: 15px;
            gap: 20px;
        }

        .item-right .form-account input {
            width: 100%;
        }

        .change-password-btn {
            width: 100%;
            margin-left: 0;
        }

        .student-account-item {
            margin-right: 0 !important;
        }
    }

    @media (max-width: 480px) {
        .form-account label {
            font-size: 14px;
        }

        .eye-icon i {
            font-size: 14px;
        }

        .change-password-btn {
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
            <div class="student-account-item" style="margin-right:50px">
                <div class="item-right">
                    <form action="{{ route('student_changepassword') }}" method="POST">
                        @csrf
                        <div class="form-account">
                            <label for="current-password">Mật khẩu hiện tại</label>
                            <div class="input-wrapper">
                                <input type="password" name="mat_khau_cu" id="current-password"
                                    placeholder="Nhập mật khẩu hiện tại" value="{{ old('mat_khau_cu') }}">
                                <span class="eye-icon" onclick="togglePasswordVisibility('current-password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-account">
                            <label for="new-password">Mật khẩu mới</label>
                            <div class="input-wrapper">
                                <input type="password" name="mat_khau_moi" id="new-password"
                                    placeholder="Nhập mật khẩu mới">
                                <span class="eye-icon" onclick="togglePasswordVisibility('new-password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-account">
                            <label for="confirm-new-password">Xác nhận mật khẩu mới</label>
                            <div class="input-wrapper">
                                <input type="password" id="confirm-new-password" name="xac_nhan_mat_khau"
                                    placeholder="Nhập lại mật khẩu mới để xác nhận">
                                <span class="eye-icon" onclick="togglePasswordVisibility('confirm-new-password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="change-password-btn"
                            style="padding: 10px; border-radius: 5px; width: 300px;background-color: #59c0f7;margin-left:15px;">Thay
                            đổi Mật khẩu</button>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul style="list-style: none;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePasswordVisibility(id, iconElement) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                iconElement.innerHTML = `
                   <i class="fa-solid fa-eye-slash"></i>
                                `;
            } else {
                input.type = "password";
                iconElement.innerHTML = `
                                    <i class="fa-solid fa-eye"></i>
                                `;
            }
        }
    </script>
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