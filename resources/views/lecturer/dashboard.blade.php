@extends('layout.lecturer_layout')
@section('title')
Trang chủ Giảng viên
@endsection  
<style>
    .dashboard-header {
        width: 100%;
        border-radius: 12px;
        background-color: #fff;
        border: 1px solid #e9ecef;
        padding: 30px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .header-left {
        width: 50%;
    }

    .header-left h4 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .header-left span {
        color: #3498db;
        font-weight: 700;
    }

    .header-left p {
        font-size: 15px;
        opacity: 0.5;
        margin: 0;
    }

    .header-right {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        position: relative;
    }

    .main-icon {
        font-size: 80px;
        color: #e9790a;
        transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .main-icon:hover {
        transform: scale(1.1) rotate(10deg);
        color: #ff9800;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .header-left {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-left h4 {
            font-size: 1.5rem;
        }

        .main-icon {
            font-size: 60px;
        }
    }

    .dashboard-body {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .count-all {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        padding: 10px;
    }

    .count-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 25px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 140px;
    }

    .count-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-color: #3498db;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 15px 0;
        line-height: 1.4;
    }

    .card-icon {
        font-size: 2.5rem;
        color: #3498db;
        opacity: 0.8;
        margin-bottom: 10px;
    }

    .card-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        text-align: right;
    }

    .count-card:nth-child(1) .card-icon {
        color: #3498db;
    }

    .count-card:nth-child(2) .card-icon {
        color: #2ecc71;
    }

    .count-card:nth-child(3) .card-icon {
        color: #f39c12;
    }

    .count-card:nth-child(4) .card-icon {
        color: #e74c3c;
    }

    .count-card:nth-child(5) .card-icon {
        color: #9b59b6;
    }

    .count-card:nth-child(1):hover {
        background-color: #ebf3fd;
    }

    .count-card:nth-child(2):hover {
        background-color: #eafaf1;
    }

    .count-card:nth-child(3):hover {
        background-color: #fef9e7;
    }

    .count-card:nth-child(4):hover {
        background-color: #fdeaea;
    }

    .count-card:nth-child(5):hover {
        background-color: #f4ecf7;
    }

    @media (max-width: 768px) {
        .count-all {
            grid-template-columns: 1fr;
        }

        .count-card {
            padding: 20px;
        }

        .card-value {
            font-size: 1.8rem;
        }
    }
</style>
@section('content')
    <div class="dashboard-content">
        <div class="dashboard-header">
            <div class="header-left" style="width:50%">
                <h4>Chào giáo viên <span>{{ $user->ho_ten }}</span></h4>
                <p style=" font-size:15px;opacity:0.5;">Chúc giáo viên một ngày làm việc tuyệt vời</p>
            </div>
            <div class="header-right">
                <i class="fa-regular fa-face-grin-wink main-icon"></i>
                <i class="fa-solid fa-sparkles small-icon sparkle-1"></i>
                <i class="fa-solid fa-star small-icon star-1"></i>
                <i class="fa-solid fa-heart small-icon heart-1"></i>
            </div>
        </div>
        <div class="dashboard-body">
            <div class="count-all">
                <div class="count-card">
                    <p class="card-title">Tổng số môn đang giảng dạy</p>
                    <i class="fas fa-book-open card-icon"></i>
                    <p class="card-value"> {{ optional($monHoc)->count() > 0 ? $monHoc->count() : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng số câu hỏi</p>
                    <i class="fa-solid fa-question"></i>
                    <p class="card-value"> {{ count($cauHoi) > 0 ? count($cauHoi) : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng số sinh viên</p>
                    <i class="fas fa-user-graduate card-icon"></i>
                    <p class="card-value"> {{ count($sinhVien) > 0 ? count($sinhVien) : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng số bài kiểm tra đã tạo</p>
                    <i class="fas fa-clipboard-check card-icon"></i>
                    <p class="card-value"> {{ count($baiKiemTra) > 0 ? count($baiKiemTra) : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng lượt làm bài kiểm tra</p>
                    <i class="fas fa-tasks card-icon"></i>
                    <p class="card-value">{{ count($lichSuLamBai) > 0 ? count($lichSuLamBai) : 'Chưa có dữ liệu' }}</p>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const cards = document.querySelectorAll('.count-card');

                cards.forEach(card => {
                    const hue = Math.floor(Math.random() * 360);
                    const saturation = 70;
                    const lightness = 95;

                    card.style.backgroundColor = `hsl(${hue}, ${saturation}%, ${lightness}%)`;
                });
            });
        </script>
@endsection