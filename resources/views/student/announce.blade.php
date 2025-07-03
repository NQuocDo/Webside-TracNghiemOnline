@extends('layout.student_layout')
@section('title')
    Trang thông báo
@endsection
<style>
    .student-announce {
        padding: 20px;
        max-width: 900px;
        margin: 0 auto;
    }

    .announce-content {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e1e8ed;
    }

    .announce-item {
        display: flex;
        padding: 20px;
        gap: 15px;
        border-left: 4px solid #1976d2;
    }

    .announce-icon {
        width: 50px;
        height: 50px;
        background: #1976d2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .announce-icon i {
        color: white;
        font-size: 20px;
    }

    .announce-text {
        flex: 1;
    }

    .title-announce {
        font-size: 18px;
        font-weight: 600;
        color: #1976d2;
        margin: 0 0 10px 0;
    }

    .content-announce {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 4px;
        padding: 12px 15px;
        margin-bottom: 15px;
        font-size: 15px;
        color: #856404;
        font-weight: 500;
    }

    .announce-info {
        margin-top: 15px;
    }

    .announce-info p {
        margin: 8px 0;
        font-size: 14px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .announce-info i {
        width: 16px;
        color: #1976d2;
        font-size: 13px;
    }

    .subject {
        color: #2e7d32 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .student-announce {
            padding: 15px;
        }

        .announce-item {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .announce-icon {
            align-self: center;
        }

        .announce-info p {
            justify-content: center;
        }
    }
</style>
@section('content')
    <div class="student-announce">
        <div class="announce-content">
            <div class="announce-item">
                <div class="announce-icon">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                @if($thongBaos->isEmpty())
                    <p style="padding-left:15px;">Không có thông báo nào.</p>
                @else
                    @foreach($thongBaos as $tb)
                        <div class="announce-text">
                            <h3 class="title-announce">{{ $tb->tieu_de }}</h3>
                            <div class="content-announce">{{ $tb->noi_dung }}</div>
                            <div class="announce-info">
                                <p><i class="fas fa-user"></i> Giảng viên: {{ $tb->ten_giang_vien }}</p>
                                <p class="subject"><i class="fas fa-book"></i> Môn: {{ $tb->ten_mon_hoc }}</p>
                                <p><i class="fas fa-clock"></i> Thời gian: {{ $tb->ngay_gui }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection