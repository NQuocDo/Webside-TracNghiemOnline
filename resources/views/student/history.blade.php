@extends('layout.student_layout')
@section('title')
    Trang thông báo
@endsection
<style>
    .history-item {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-left: 5px solid #ffc107;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 15px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        display: grid;
        grid-template-columns: auto 1fr auto;
        grid-template-rows: auto auto;
        gap: 5px 15px;
        align-items: center;
        margin: 20px;
    }

    .history-item .fa-clipboard-check {
        grid-column: 1;
        grid-row: 1 / span 2;
        font-size: 28px;
        color: #ffc107;
        align-self: start;
    }

    .history-item a {
        grid-column: 2;
        grid-row: 1;
        font-size: 19px;
        font-weight: bold;
        text-decoration: none;
        color: #333;
    }

    .history-item p {
        grid-column: 2;
        grid-row: 2;
        font-size: 13px;
        color: #777;
        margin: 0;
    }

    .history-item .score-section {
        grid-column: 3;
        grid-row: 1 / span 2;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        font-size: 14px;
    }

    .history-item .correct-wrong {
        color: #555;
    }

    .history-item .final-score {
        font-size: 18px;
        font-weight: bold;
        color: #28a745;
        margin-top: 5px;
    }
</style>
@section('content')
    <div class="student-history">
        <div class="history-content">
            @if($bangDiems->isEmpty())
                <div class="history-item">Chưa có bài kiểm tra nào được cập nhật</div>
            @else
                @foreach ($bangDiems as $bangDiem)
                    <div class="history-item">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <a href="{{ route('history_exam_detail', ['ma_bai_kiem_tra' => $bangDiem->ma_bai_kiem_tra]) }}">
                            {{ $bangDiem->baiKiemTra->ten_bai_kiem_tra ?? 'Không rõ đề thi' }}
                        </a>

                        <p>
                            Giảng viên: {{ $bangDiem->baiKiemTra->deThi->giangVien->nguoiDung->ho_ten ?? 'Chưa rõ' }}
                        </p>

                        <div class="score-section">
                            <span class="correct-wrong">
                                {{ $bangDiem->so_cau_dung ?? 0 }}/{{ ($bangDiem->so_cau_dung + $bangDiem->so_cau_sai) ?? 0 }}
                            </span>
                            <span class="final-score">{{ $bangDiem->diem_so }} Điểm</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection