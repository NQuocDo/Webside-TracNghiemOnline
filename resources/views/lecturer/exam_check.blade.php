@extends('layout.lecturer_layout')
@section('title')
    Trang chi tiết bài kiểm tra
@endsection
<style>
    .exam-content {
        max-width: 1200px;
        padding: 20px;
    }

    .exam-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #17a2b8;
        color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .exam-question-body {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .question-content {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .question-src {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        margin: 15px 0;
        border-radius: 5px;
    }

    .question-note {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .answer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .answer-list li {
        background-color: #f8f9fa;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.2s ease-in-out;
    }

    .answer-list li.correct {
        background-color: #d4edda;
        font-weight: bold;
        border: 1px solid #28a745;
    }

    .answer-list input[type="radio"] {
        margin-right: 8px;
    }

    .shuffle-form {
        margin-left: 20px;
    }

    .shuffle-form button {
        padding: 8px 12px;
        background-color: #ffc107;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }
</style>
@section('content')
    <div class="exam-content">
        <div class="exam-item-header">
            <div><strong>Chi tiết bài kiểm tra</strong></div>
            <div>Tên bài: {{ $baiKiemTra->ten_bai_kiem_tra }}</div>
            <div>Thời gian: {{ $baiKiemTra->deThi->thoi_gian_lam_bai }} phút</div>
            <form class="shuffle-form" action="{{ route('exam_check_shuffle', ['id' => $baiKiemTra->ma_bai_kiem_tra]) }}"
                method="POST">
                @csrf
                <button type="submit">🔁 Xáo trộn câu hỏi</button>
            </form>
        </div>

        <div class="exam-question-body">
            @foreach ($chiTietCauHois as $index => $chiTiet)
                @php
                    $cauHoi = $chiTiet->cauHoi; 
                @endphp

                @if ($cauHoi)
                    <div class="question-content">
                        <strong>Câu hỏi {{ $index + 1 }}:</strong>
                        <p>{!! nl2br(e($cauHoi->noi_dung)) !!}</p>

                        @if ($cauHoi->hinh_anh)
                            <img src="{{ asset('images/' . $cauHoi->hinh_anh) }}" alt="Câu hỏi" class="question-src">
                        @endif

                        @if ($cauHoi->ghi_chu)
                            <div class="question-note">{{ $cauHoi->ghi_chu }}</div>
                        @endif

                        <ul class="answer-list">
                            @foreach ($cauHoi->dapAns as $dapAn)
                                <li class="@if ($dapAn->ket_qua_dap_an) correct @endif">
                                    <input type="radio" disabled>
                                    {{ $dapAn->noi_dung }}
                                    @if ($dapAn->ket_qua_dap_an)
                                        <span style="color: #28a745">(Đúng)</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="question-content">
                        <strong>Câu hỏi {{ $index + 1 }}:</strong>
                        <p><em>Câu hỏi không tồn tại hoặc đã bị xóa.</em></p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
