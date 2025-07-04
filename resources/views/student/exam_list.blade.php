@extends('layout.student_layout')
@section('title')
Trang Danh sách bài kiểm tra
@endsection
<style>
    .exam-list {
        padding: 20px;
        background-color: #f8f9fa;
        min-height: calc(100vh - 80px);
    }

    .exam-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .exam-item {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-left: 5px solid rgb(16, 80, 122);
        border-radius: 8px;
        padding: 20px 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        display: grid;
        grid-template-columns: auto 1fr auto;
        grid-template-rows: auto auto auto auto;
        gap: 8px 20px;
        align-items: start;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .exam-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(16, 80, 122, 0.05), transparent);
        transition: left 0.5s ease;
    }

    .exam-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        border-left-color: rgb(26, 115, 174);
    }

    .exam-item:hover::before {
        left: 100%;
    }

    .exam-item .fa-clipboard-question {
        grid-column: 1;
        grid-row: 1 / span 4;
        font-size: 32px;
        color: rgb(16, 80, 122);
        align-self: start;
        padding: 5px;
        background: rgba(16, 80, 122, 0.1);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .exam-item:hover .fa-clipboard-question {
        color: rgb(26, 115, 174);
        background: rgba(26, 115, 174, 0.15);
        transform: scale(1.1);
    }

    .exam-item .exam-title {
        grid-column: 2;
        grid-row: 1;
        font-size: 20px;
        font-weight: 600;
        text-decoration: none;
        color: #2c3e50;
        transition: color 0.3s ease;
        margin: 0;
        line-height: 1.4;
    }

    .exam-item .exam-title:hover {
        color: rgb(26, 115, 174);
    }

    .exam-item .exam-instructor {
        grid-column: 2;
        grid-row: 2;
        font-size: 14px;
        color: #6c757d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .exam-item .exam-instructor::before {
        content: '👨‍🏫';
        font-size: 16px;
    }

    .exam-item .exam-details {
        grid-column: 2;
        grid-row: 3;
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #868e96;
        margin: 0;
    }

    .exam-item .exam-details span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .exam-item .exam-details .fa-clock {
        color: rgb(16, 80, 122);
    }

    .exam-item .exam-details .fa-question-circle {
        color: rgb(16, 80, 122);
    }

    .exam-item .exam-actions {
        grid-column: 3;
        grid-row: 4;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 10px;
    }

    .exam-btn {
        padding: 10px 20px;
        border-radius: 6px;
        background: linear-gradient(135deg, rgb(26, 115, 174), rgb(16, 80, 122));
        color: #fff;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(26, 115, 174, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 100px;
    }

    .exam-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(26, 115, 174, 0.4);
        background: linear-gradient(135deg, rgb(16, 80, 122), rgb(26, 115, 174));
    }

    .exam-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(26, 115, 174, 0.3);
    }

    .exam-status {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .exam-status.available {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .exam-status.completed {
        background-color: #cce7ff;
        color: #004085;
        border: 1px solid #b8daff;
    }

    .exam-status.expired {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .exam-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 20px;
        background: linear-gradient(135deg, rgb(16, 80, 122), rgb(26, 115, 174));
        color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(16, 80, 122, 0.2);
    }

    .exam-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .exam-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    @media (max-width: 768px) {
        .exam-list {
            padding: 15px;
        }

        .exam-item {
            grid-template-columns: auto 1fr;
            grid-template-rows: auto auto auto auto;
            gap: 10px 15px;
            padding: 15px 20px;
        }

        .exam-item .fa-clipboard-question {
            grid-row: 1 / span 3;
            font-size: 28px;
            width: 45px;
            height: 45px;
        }

        .exam-item .exam-title {
            font-size: 18px;
        }

        .exam-item .exam-details {
            grid-row: 3;
            flex-direction: column;
            gap: 8px;
        }

        .exam-item .exam-actions {
            grid-column: 1 / span 2;
            grid-row: 4;
            justify-content: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }

        .exam-btn {
            min-width: 120px;
            padding: 12px 24px;
            font-size: 14px;
        }

        .exam-header h1 {
            font-size: 24px;
        }

        .exam-header p {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .exam-item .exam-actions {
            flex-direction: column;
            gap: 8px;
        }

        .exam-btn {
            width: 100%;
        }
    }
</style>

@section('content')
    <div class="exam-list">
        <div class="exam-content">

            <div class="exam-header">
                <h1>📋 Danh Sách Bài kiểm tra</h1>
                <p>Chọn bài thi để bắt đầu làm bài</p>
            </div>
            @csrf
            @if($danhSachBaiKiemTra->isEmpty())
                <p class="text-center">Không có bài kiểm tra</p>
            @else
                @foreach($danhSachBaiKiemTra as $bkt)
                    <div class="exam-item">
                        <i class="fa-solid fa-clipboard-question"></i>
                        <a href="#" class="exam-title">{{ $bkt->ten_bai_kiem_tra }}</a>
                        <p class="exam-instructor">Giảng viên: {{ $bkt->ten_giang_vien }}</p>
                        <p class="exam-subject">Môn: {{ $bkt->ten_mon_hoc }}</p>
                        <p class="exam-details">
                            <span><i class="fa-solid fa-clock"></i> {{ $bkt->thoi_gian_lam_bai }} phút</span>
                            <span><i class="fa-solid fa-question-circle"></i> {{ $bkt->so_luong_cau_hoi }} câu hỏi</span>
                        </p>
                        <div class="exam-actions">
                            <a href="{{ route('hienThiBaiKiemTraTheoId', ['id' => $bkt->ma_bai_kiem_tra]) }}" class="exam-btn">
                                Tham gia
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection