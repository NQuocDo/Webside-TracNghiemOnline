@extends('layout.lecturer_layout')
<style>
    .lecturer-account-content {
        display: grid;
        grid-template-columns: 300px 1fr;
        padding-left: 50px;
        gap: 50px;
    }

    .lecturer-account-item {
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
</style>
@section('content')
    <div class="lecturer-account">
        <h4 style="padding-left: 25px;padding-bottom:15px;padding-top:15px;"> Cài đặt tài khoản</h4>
        <div class="lecturer-account-content">
            <div class="lecturer-account-item" style="height: 20vh;">
                <div class="item-left">
                    <div class="item"><a href="#">Thông tin tài khoản</a></div>
                    <div class="item"><a href="#">Đổi mật khẩu</a></div>
                </div>
            </div>
            <form action="{{ route('lecturer_info_update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="lecturer-account-item" style="margin-right:50px">
                    <div class="item-right">
                        <p>Ảnh đại diện</p>
                        @if($user->hinh_anh)
                            <img src="{{$user->hinh_anh}}" alt="" class="src" style="border-radius:50%">
                        @else
                            <img src="{{ asset('images/lecturer.jpg') }}" alt="" class="src" style="border-radius:50%">
                        @endif
                        <input type="file" class="img-account-lecturer" name="hinh_anh">

                        <div class="form-account">
                            <label for="email">Email <span class="required"></span></label>
                            <input type="email" id="email" value="{{$user->email}}" disabled>
                        </div>
                        <div class="form-account">
                            <label for="ho_ten">Họ tên <span class="required">*</span></label>
                            <input type="text" name="ho_ten" id="ho_ten" value="{{$user->ho_ten}}">
                        </div>
                        <div class="form-account">
                            <label for="so_dien_thoai">Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="{{ $user->so_dien_thoai }}">
                        </div>
                        <div class="form-account">
                            <label for="dia_chi">Địa chỉ <span class="required">*</span></label>
                            <input type="text" name="dia_chi" id="dia_chi" value="{{$user->dia_chi}}">
                        </div>
                        <div class="form-account">
                            <label for="gender_select">Giới tính <span class="required">*</span></label>
                            <div class="custom-select-wrapper">
                                <select id="gender_select" name="gioi_tinh"
                                    style="padding: 10px; border-radius: 5px; width: 300px; border:1px solid gainsboro;">
                                    <option value="" disabled {{ !isset($user->gioi_tinh) || $user->gioi_tinh == null ? 'selected' : '' }}>Chọn giới tính</option>
                                    <option value="Nam" {{ $user->gioi_tinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $user->gioi_tinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-account">
                            <label for="dob_input">Ngày sinh <span class="required">*</span></label>
                            <div class="input-with-icon-wrapper">
                                <input type="date" name="ngay_sinh" id="dob_input" value="{{$user->ngay_sinh}}">
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