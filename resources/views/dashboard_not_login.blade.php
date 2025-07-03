<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Website Trắc Nghiệm Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/layout_dashboard_not_login.css') }}">
    <script src="{{ asset('js/layout.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>
    <div class="header" style="background-color: rgb(31, 43, 62);">
        <div class="header_overlay"></div>
        <div class="header_column_left">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Khoa Công Nghê Thông Tin">
            <p style="color:#fff">KHOA CÔNG NGHỆ THÔNG TIN</p>
        </div>
        <div class="header_column_right" class="sidebar_menu" id="sidebarMenu">
            <ul class="header_nav_list">
                <li><a href="https://cntt.caothang.edu.vn/lich-su-phat-trien.html">GIỚI THIỆU</a></li>
                <li><a href="">TÍNH NĂNG</a></li>
                <li><a href="">TIN TỨC</a></li>
            </ul>
        </div>
        <div class="menu_toggle" id="navMenu" onclick="anHienMenu()">☰
            <div class="header_mobile_input">
                <div class="header_mobile">
                    <ul class="header_nav_monbile_list">
                        <i class="fa-solid fa-xmark ms-3"></i>
                        <li><a href="https://cntt.caothang.edu.vn/lich-su-phat-trien.html">GIỚI THIỆU</a></li>
                        <li><a href="">TÍNH NĂNG</a></li>
                        <li><a href="">TIN TỨC</a></li>
                        <li><a href="">LIÊN HỆ</a></li>
                        <li><button class="btn_login fw-bold"><a href="{{ asset('/dangnhap') }}">Đăng nhập</a></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="body">
        <div class="dashboard_not_login_content">
            <div style="text-align: center; margin-top: 50px; padding: 20px;">
                <h1 style="font-size: 2.5em; color: #007bff; margin-bottom: 10px;">Chào mừng đến với Hệ thống Trắc
                    nghiệm Online</h1>
                <p style="font-size: 1.2em; color: #555;">Nền tảng kiểm tra, đánh giá và quản lý học tập hiệu quả dành
                    cho Khoa Công nghệ Thông tin.</p>
            </div>
            <div
                style="text-align: center; margin-top: 40px; background-color: #f8f9fa; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); max-width: 600px; margin-left: auto; margin-right: auto;">
                <p style="font-size: 1.3em; color: #333; font-weight: bold; margin-bottom: 20px;">Vui lòng đăng nhập để
                    truy cập đầy đủ các tính năng.</p>
                <p style="font-size: 1em; color: #666; margin-bottom: 30px;">Bạn cần đăng nhập để làm bài kiểm tra, xem
                    kết quả, quản lý lớp học và nhiều hơn nữa.</p>
                <a href="{{ asset('login') }}"
                    style="display: inline-block; padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1.1em; font-weight: bold; transition: background-color 0.3s ease;">Đăng
                    nhập ngay</a>
            </div>
            <div style="display: flex; justify-content: center; gap: 30px; margin-top: 50px; flex-wrap: wrap;">
                <div
                    style="background-color: #e6f7ff; padding: 25px; border-radius: 10px; max-width: 300px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <i class="fas fa-user-graduate" style="font-size: 2.5em; color: #007bff; margin-bottom: 15px;"></i>
                    <h3 style="color: #333; margin-bottom: 10px;">Dành cho Sinh viên</h3>
                    <p style="color: #666;">Làm bài kiểm tra online tiện lợi, xem kết quả và tiến độ học tập mọi lúc mọi
                        nơi.</p>
                </div>
                <div
                    style="background-color: #e6ffe6; padding: 25px; border-radius: 10px; max-width: 300px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <i class="fas fa-chalkboard-teacher"
                        style="font-size: 2.5em; color: #28a745; margin-bottom: 15px;"></i>
                    <h3 style="color: #333; margin-bottom: 10px;">Dành cho Giảng viên</h3>
                    <p style="color: #666;">Dễ dàng tạo và quản lý ngân hàng câu hỏi, thiết lập đề thi, và theo dõi hiệu
                        suất sinh viên.</p>
                </div>
                <div
                    style="background-color: #fff3e6; padding: 25px; border-radius: 10px; max-width: 300px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <i class="fas fa-sitemap" style="font-size: 2.5em; color: #ffc107; margin-bottom: 15px;"></i>
                    <h3 style="color: #333; margin-bottom: 10px;">Dành cho Trưởng khoa</h3>
                    <p style="color: #666;">Thống kê chuyên sâu, báo cáo toàn diện về chất lượng đào tạo và hoạt động
                        của khoa.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer" style="background-color:rgb(31, 43, 62);">
        <span style="color:#fff;">Đây là phiên bản dự án thử nghiệm dựa trên ý tưởng của nhóm chúng tôi</span>
        <p style="color:#fff;">Cảm ơn các thầy và các bạn đã trải nghiệm dự án</h5>
        <h5 style="font-weight: 600;color:red;">Author of the project: Nguyễn Quốc Đô</h4>

    </div>
</body>

</html>