<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/dean.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Thông báo alertifyJS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    @yield('styles')
</head>

<body>
    <div class="contain">
        <div class="aside_overlay"></div>
        <aside class="sidebar">
            <div class="sidebar_top border-bottom">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo khoa công nghệ thông tin" width="70px"
                    height="70px">
                <p class="nameLogo my-4 fw-border ms-2">KHOA CÔNG NGHỆ THÔNG TIN</p>
            </div>
            <div class="sidebar_middle">
                <ul>
                    <li><a href="{{ route('dean.dashboard') }}"><i class="fa-solid fa-house"></i><span>Trang
                                chủ</span></a>
                    </li>
                    <li><a href="{{ route('student_management') }}"><i class="fa-solid fa-users"></i><span>Quản lý
                                Sinh viên</span></a>
                    </li>
                    <li><a href="{{ asset('dean/subject-management') }}"><i
                                class="fa-solid fa-graduation-cap"></i><span>Quản lý Môn học</span></a>
                    </li>
                    <li>
                        <input type="checkbox" name="" id="dropdown-btn" hidden>
                        <label for="dropdown-btn" class="dropdown-btn">
                            <i class="fa-solid fa-bars-progress"></i>
                            <span>Quản lý Giảng viên</span>
                        </label>
                        <ul class="sub-menu">
                            <li><a href="{{ route('lecturer_list') }}">
                                    <i class="fas fa-user"></i><i class="fas fa-list"></i>Danh sách Giảng viên
                                </a>
                            </li>
                            <li><a href="{{ route('decentralization') }}">
                                    <i class="fa-solid fa-timeline"></i>Phân quyền dạy học
                                </a>
                            </li>
                            <li><a href="{{ asset('dean/question-bank') }}">
                                    <i class="fa-solid fa-book-open"></i>Ngân hàng câu hỏi
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{ asset('dean/department-statis') }}"><i
                                class="fa-solid fa-chart-simple"></i><span>Thống kê kết quả</span></a></li>
                    <li><a href="{{ route('add_user')}}"><i class="fa-solid fa-plus"></i><span>Thêm người
                                dùng</span></a></li>
                    <li><a href="{{ route('add_class')}}"><i class="fa-solid fa-plus"></i><span>Thêm lớp học</span></a>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="header">
            <div class="top">
                <button class="menu_button" id="hiddenAside">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="left-section" style="display: flex; align-items: center; flex-grow: 1;">
                    <div class="profile">
                        <img src="{{ asset('images/man.jpg') }}" alt="" class="src">
                        <p style="margin: 15px;color:black;text-decoration:none">{{ $user->ho_ten }}</p>
                    </div>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i> <input type="text" placeholder="Tìm kiếm đề thi">
                    </div>
                </div>

                <div class="main_top_right">
                    <a href="#" class="main_top_error"> <i class="fa-solid fa-bug me-1"></i> Báo lỗi</a>
                    <input type="checkbox" name="" id="profile-btn" hidden>
                    <label for="profile-btn">
                        <img src="{{ asset('images/lecturer.jpg') }}" alt="sinh viên nam" class="src user-avatar-icon">
                    </label>
                    <div class="menu-profile" id="userDropdownMenu">
                        <button class="border-bottom pb-1 " onclick="/*window.location.href='{{ url('/') }}'*/">Hồ sơ
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
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/layout.js') }}"></script>
</body>

</html>
<script>
    //ẩn hiện SideBar
    document.addEventListener('DOMContentLoaded', () => {
        const button_hidden = document.getElementById('hiddenAside');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.aside_overlay');

        console.log(sidebar);
        console.log(overlay);

        if (button_hidden && sidebar && overlay) {
            button_hidden.addEventListener('click', () => {

                if (sidebar.style.display === "none") {
                    overlay.style.display = "block";
                    sidebar.style.display = "block";
                } else {

                    overlay.style.display = "none";
                    sidebar.style.display = "none";
                }
            });

            overlay.addEventListener('click', () => {
                if (sidebar.style.display === "block") {
                    overlay.style.display = "none";
                    sidebar.style.display = "none";
                }
            });

        } else {
            console.warn("Một hoặc nhiều phần tử (button_hidden, sidebar, overlay) không tìm thấy. Kiểm tra ID và class.");
        }
    });
</script>