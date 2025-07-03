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
            <div class="lecturer-account-item" style="margin-right:50px">
                <div class="item-right">

                    <form>
                        <div class="form-account">
                            <label for="current-password">Mật khẩu hiện tại</label>
                            <div class="input-wrapper">
                                <input type="password" id="current-password" placeholder="Nhập mật khẩu hiện tại">
                                <span class="eye-icon" onclick="togglePasswordVisibility('current-password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-account">
                            <label for="new-password">Mật khẩu mới</label>
                            <div class="input-wrapper">
                                <input type="password" id="new-password" placeholder="Nhập mật khẩu mới">
                                <span class="eye-icon" onclick="togglePasswordVisibility('new-password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-account">
                            <label for="confirm-new-password">Xác nhận mật khẩu mới</label>
                            <div class="input-wrapper">
                                <input type="password" id="confirm-new-password"
                                    placeholder="Nhập lại mật khẩu mới để xác nhận">
                                <span class="eye-icon" onclick="togglePasswordVisibility('confirm-new-password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="change-password-btn" style="padding: 10px; border-radius: 5px; width: 300px;background-color: #59c0f7;margin-left:15px;">Thay đổi Mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
@endsection