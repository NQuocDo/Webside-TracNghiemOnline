@extends('layout.lecturer_layout')
<style>
    .dashboard-content {
        padding: 20px;
        margin: 20px;
    }

    .dashboard-header {
        width: 100%;
        border-radius: 20px;
        background-color: #fff;
        border: 2px solid rgb(17, 193, 228, 1);
        padding: 30px;
        display: flex;
        align-items: center;
    }

    .header-left h4 {
        font-family: "Luckiest Guy", cursive, sans-serif;
    }

    .header-left span {
        color: rgb(233, 121, 10);
        font-family: "Luckiest Guy", cursive, sans-serif;
    }

    .header-right {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        position: relative;
        overflow: hidden;
    }

    .main-icon {
        font-size: 80px;
        color: #e9790a;
        transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
        transform: translateX(-5px) translateY(-5px);
        z-index: 2;

    }

    .main-icon:hover {
        transform: scale(1.1) rotate(10deg) translateX(-5px) translateY(-5px);
        color: #ff9800;
    }

    .small-icon {
        font-size: 20px;
        color: #ffd700;
        position: absolute;
        opacity: 0.8;
        transition: transform 0.4s ease-out, opacity 0.4s ease-out;
        z-index: 1;
    }

    .sparkle-1 {
        top: 10px;
        left: calc(50% + 30px);
        transform: rotate(-15deg);
    }

    .star-1 {
        bottom: 5px;
        left: calc(50% - 60px);
        transform: rotate(20deg);
    }

    .heart-1 {
        top: calc(50% + 20px);
        right: 15px;
        transform: rotate(5deg);
    }

    .header-right:hover .small-icon {
        transform: scale(1.2) translateY(-10px);

        opacity: 1;
    }

    .header-right:hover .sparkle-1 {
        transform: scale(1.3) translateX(5px) rotate(-30deg);
    }

    .header-right:hover .star-1 {
        transform: scale(1.3) translateY(5px) rotate(30deg);
    }

    .header-right:hover .heart-1 {
        transform: scale(1.3) translateX(-5px) rotate(-10deg);
    }

    .dashboard-body {
        border-radius: 20px;
        border: 0.5px solid gainsboro;
        padding: 10px;
        margin: 20px auto;
        box-shadow: 1px 1px 3px rgb(0,0,0,0.1);
    }

    .count-all {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
    }

    .count-card {
        border: 0.1px solid black;
        border-radius: 20px;
        margin: 10px;
        width: 40%;
        padding: 10px;
        max-width: 250px;
    }
    .count-card:hover {
        box-shadow: 3px 3px 5px rgb(0,0,0,0.3);
    }

    .count-card * {
        color: #1a2b3c;
    }

    .count-card .card-title {
        height: 70px;
        font-weight: 600;
    }

    .count-card i {
        font-size: 50px;
    }

    .count-card .card-value {
        float: right;
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
                    <p class="card-value">   {{ optional($monHoc)->count() > 0 ? $monHoc->count() : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng số câu hỏi</p>
                    <i class="fa-solid fa-question"></i>
                    <p class="card-value">  {{ count($cauHoi) > 0 ? count($cauHoi) : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng số sinh viên</p>
                    <i class="fas fa-user-graduate card-icon"></i>
                    <p class="card-value">  {{ count($sinhVien) > 0 ? count($sinhVien) : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng số bài kiểm tra đã tạo</p>
                    <i class="fas fa-clipboard-check card-icon"></i>
                    <p class="card-value">  {{ count($baiKiemTra) > 0 ? count($baiKiemTra) : 'Chưa có dữ liệu' }}</p>
                </div>
                <div class="count-card">
                    <p class="card-title">Tổng lượt làm bài kiểm tra</p>
                    <i class="fas fa-tasks card-icon"></i>
                    <p class="card-value">5600</p>
                </div>
            </div>
            <div class="dashboard-footer"></div>
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