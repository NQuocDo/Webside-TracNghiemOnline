<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/lecturer_layout.css') }}">
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
                    <li><a href="{{ route('lecturer.dashboard') }}"><i class="fa-solid fa-house"></i><span>Trang
                                chủ</span></a>
                    </li>
                    <li>
                        <input type="checkbox" name="" id="dropdown-btn" hidden>
                        <label for="dropdown-btn" class="dropdown-btn">
                            <i class="fa-solid fa-bars-progress"></i>
                            <span>Quản lý</span>
                        </label>
                        <ul class="sub-menu">
                            <li><a href="{{ route('student_list') }}">
                                    <i class="fa-solid fa-chalkboard-user"></i>Sinh viên
                                </a>
                            </li>
                            <li><a href="{{route('subject_list') }}">
                                    <i class="fa-solid fa-graduation-cap"></i>Môn học
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{ asset('lecturer/announce') }}"><i class="fa-solid fa-bell"></i><span>Thông
                                báo</span></a></li>
                    <li><a href="{{ asset('lecturer/contact') }}"><i class="fa-solid fa-flag"></i><span>Liên hệ sinh
                                viên</span></a></li>
                    <li><a href="{{ route('question') }}"><i class="fa-solid fa-circle-question"></i><span>Ngân
                                hàng câu hỏi</span></a></li>
                    <li><a href="{{ route('question_del') }}"><i class="fa-solid fa-clipboard-question"></i><span>
                                Câu hỏi hàng chờ</span></a></li>
                    <li><a href="{{ route('exam_list')}}"><i class="fa-solid fa-list"></i><span>Danh sách đề
                                thi</span></a></li>
                    <li><a href="{{ asset('lecturer/global') }}"><i class="fa-solid fa-globe"></i><span>Cộng
                                đồng</span></a></li>
                    <li><a href="{{ route('score_board') }}"><i class="fa-solid fa-check-circle"></i><span>Bảng
                                điểm</span></a></li>
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
                </div>

                <div class="main_top_right">
                    <input type="checkbox" name="" id="profile-btn" hidden>
                    <label for="profile-btn">
                        @if(empty($user->hinh_anh))
                             <img src="{{ asset('images/lecturer.jpg') }}" alt="sinh viên nam" class="src user-avatar-icon">
                        @else
                            <img src="{{ asset('images/' . $user->hinh_anh) }}" alt="Ảnh người dùng" width="70px"
                                height="70px">
                        @endif
                    </label>
                    <div class="menu-profile" id="userDropdownMenu">
                        <button class="border-bottom pb-1"
                            onclick="window.location.href='{{ route('lecturer_info') }}'">
                            Hồ sơ
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/layout.js') }}"></script>
    @yield('scripts')
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