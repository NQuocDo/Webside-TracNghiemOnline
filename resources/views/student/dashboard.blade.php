@extends('layout.student_layout')
@section('title')
    Trang chủ Sinh Viên
@endsection
<style>
    .section-title {
        font-weight: bold;
        font-size: 18px;
        margin: 20px 0 10px;
        color: #34495e;
    }

    .text-muted {
        color: #888;
        font-style: italic;
    }

    .dashboard-wrapper {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }

    .dashboard-main-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(44, 90, 160, 0.3);
    }

    .page-header h2 {
        margin: 0 0 10px 0;
        font-size: 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .page-header .subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .page-header .icon {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .item-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
        padding: 0;
    }

    .item {
        background: white;
        border-radius: 15px;
        padding: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        border: 1px solid #e9ecef;
        position: relative;
    }

    .item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .item .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
    }

    .item .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
        pointer-events: none;
    }

    .item .card-header h5 {
        margin: 0 0 8px 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: white;
    }

    .item .card-header .quiz-code {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        color: white;
    }

    .card-body {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .card-body .src-lecturer {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
        flex-shrink: 0;
    }

    .card-body .lecturer-name {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        color: #495057;
        flex: 1;
    }

    .card-footer {
        padding: 15px 20px 20px;
        background: transparent;
        border-top: 1px solid #e9ecef;
        text-align: right;
    }

    .card-footer .description-btn {
        background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: none;
    }

    .card-footer .description-btn:hover {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(44, 90, 160, 0.4);
    }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 15px;
        }

        .page-header {
            padding: 20px;
            margin-bottom: 20px;
        }

        .page-header h2 {
            font-size: 1.5rem;
        }

        .item-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .card-body {
            padding: 15px;
        }

        .card-footer {
            padding: 10px 15px 15px;
        }
    }

    @media (max-width: 480px) {
        .page-header h2 {
            flex-direction: column;
            gap: 10px;
        }

        .card-body {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .card-body .src-lecturer {
            width: 80px;
            height: 80px;
        }
    }

    .item {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    .item:nth-child(1) {
        animation-delay: 0.1s;
    }

    .item:nth-child(2) {
        animation-delay: 0.2s;
    }

    .item:nth-child(3) {
        animation-delay: 0.3s;
    }

    .item:nth-child(4) {
        animation-delay: 0.4s;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-main-content">
            <div class="page-header">
                <h2>
                    <span class="icon">📚</span>
                    Danh Sách Môn Học
                </h2>
                <p class="subtitle">Chỉ hiển thị các môn học bạn đang theo học tại lớp hiện tại</p>
            </div>

            {{-- MÔN HỌC ĐANG HỌC --}}
            <div class="section-title">📘 Môn học tại lớp hiện tại</div>
            <div class="item-content">
                @if($monDangHoc->isEmpty())
                    <p class="text-muted">Không có môn học nào trong lớp hiện tại mà giảng viên được phân quyền dạy.</p>
                @else
                    @foreach($monDangHoc as $mon)
                        <div class="item">
                            <div class="card-header">
                                <h5 class="quiz-title">{{ $mon->ten_mon_hoc }}</h5>
                                <span class="quiz-code">Học kỳ: {{ $mon->hoc_ky }}</span>
                            </div>
                            <div class="card-body">
                                <img src="{{ !empty($mon->hinh_anh) ? asset('images/' . $mon->hinh_anh) : asset('images/lecturer.jpg') }}"
                                    alt="Giảng viên" class="src-lecturer">
                                <h6 class="lecturer-name">GV: {{ $mon->ten_giang_vien }}</h6>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('student_exam_list', ['ma_mon_hoc' => $mon->ma_mon_hoc, 'ma_giang_vien' => $mon->ma_giang_vien]) }}"
                                    class="description-btn">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function getRandomGradientSophisticated() {
            const gradients = [
                'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
                'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
                'linear-gradient(135deg, #ff8a80 0%, #ea4c89 100%)',
                'linear-gradient(135deg, #8fd3f4 0%, #84fab0 100%)',
                'linear-gradient(135deg, #d299c2 0%, #fef9d7 100%)'
            ];

            return gradients[Math.floor(Math.random() * gradients.length)];
        }

        document.addEventListener('DOMContentLoaded', () => {
            const cardHeaders = document.querySelectorAll('.item .card-header');
            cardHeaders.forEach(header => {
                header.style.background = getRandomGradientSophisticated();
            });

            // Add click event to buttons
            const buttons = document.querySelectorAll('.description-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    // Add your navigation logic here
                    console.log('Viewing details for course...');
                });
            });
        });
        @if (session('success'))
                    < script >
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: @json(session('success')),
                        confirmButtonText: 'OK'
                    });
                localStorage.removeItem("selectedQuestions");
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
    </script>
@endsection