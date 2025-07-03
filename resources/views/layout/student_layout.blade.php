<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/student_layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @stack('styles')

</head>

<body>
    <div class="contain">
        <div class="aside_overlay"></div>
        <aside class="sidebar">
            <div class="sidebar_top">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo khoa công nghệ thông tin" width="70px"
                    height="70px">
                <p class="nameLogo my-4 fw-border ms-2">KHOA CÔNG NGHỆ THÔNG TIN</p>
            </div>
            <div class="sidebar_middle">
                <ul>
                    <li><a href="{{ asset('/student/dashboard') }}"><i class="fa-solid fa-house"></i><span>Trang
                                chủ</span></a>
                    </li>
                    <li><a href="{{ route('student_announce') }}"><i class="fa-solid fa-bell"></i><span>Thông
                                báo</span></a></li>
                    <li><a href="{{ asset('/student/exam-list')}}"><i class="fa-solid fa-clipboard-list"></i><span>Danh
                                sách bài kiểm tra</span></a></li>
                    <li><a href="{{ route('history') }}"><i class="fa-solid fa-clock-rotate-left"></i><span>Lịch
                                sử kiểm tra</span></a></li>
                </ul>
            </div>
        </aside>
        <div class="header">
            <div class="top">
                <button class="menu_button" id="hiddenAside">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="profile">
                    <img src="{{ asset('images/man.jpg') }}" alt="" class="src">
                    <p style="margin: 15px; color: black; text-decoration: none;">
                        {{ Auth::user()->ho_ten }}
                    </p>
                </div>
                <div class="search-box">
                    <form action="{{ route('student_exam_list') }}" method="GET">
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Tìm kiếm bài kiểm tra...">
                    </form>
                </div>
                <div class="main_top_right">
                    <div class="main_top_error"> <i class="fa-solid fa-bug"></i><a
                            href="{{ asset('/student/contact') }}" class="error-herf"
                            style="text-decoration: none;color: red;"> Báo lỗi</a></div>
                    <input type="checkbox" name="" id="profile-btn" hidden>
                    <label for="profile-btn">
                        <img src="{{ asset('images/studentboy.jpg') }}" alt="sinh viên nam"
                            class="src user-avatar-icon">
                    </label>
                    <div class="menu-profile" id="userDropdownMenu">
                        <button class="border-bottom pb-1 "
                            onclick="window.location.href='{{ route('student.info') }}'">Hồ sơ
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket me-2 pt-2" style="color:darkorange;"></i>
                            Đăng xuất
                        </button>
                    </div>
                </div>
            </div>
            <div class="main">
                @yield('content')
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/layout.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
@yield('scripts')

</html>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const button_hidden = document.getElementById('hiddenAside');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.aside_overlay');

        if (button_hidden && sidebar && overlay) {
            button_hidden.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        } else {
            console.warn("Một hoặc nhiều phần tử (button_hidden, sidebar, overlay) không tìm thấy.");
        }
    });
</script>