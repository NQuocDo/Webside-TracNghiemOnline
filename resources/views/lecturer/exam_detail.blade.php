@extends('layout.lecturer_layout')

<style>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .exam-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: #007bff;
        color: white;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .exam-question-body {
        padding: 30px;
        margin-top: 90px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .question-content {
        flex: 1 1 calc(50% - 20px);
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .question-content strong {
        font-size: 16px;
        color: #343a40;
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

    .answer-list li:hover {
        background-color: #e9ecef;
    }

    .answer-list li.correct {
        background-color: #d4edda;
        font-weight: bold;
        border: 1px solid #28a745;
    }

    @media (max-width: 768px) {
        .question-content {
            flex: 1 1 100%;
        }
    }
</style>

@section('content')
<div class="exam-content">
    <div class="exam-item-header">
        <div><strong>Chi tiết đề thi</strong></div>
        <div>Tên đề: {{ $deThi->ten_de_thi }}</div>
        <div>Thời gian: {{ $deThi->thoi_gian_lam_bai }} phút</div>
    </div>

    <div class="exam-question-body">
        @foreach ($deThi->cauHoi as $index => $cauHoi)
            <div class="question-content">
                <strong>Câu hỏi {{ $index + 1 }}:</strong>
                <p>{{ $cauHoi->noi_dung }}</p>

                @if ($cauHoi->hinh_anh)
                    <img src="{{ asset('images/' . $cauHoi->hinh_anh) }}" alt="Câu hỏi" class="question-src">
                @endif

                @if ($cauHoi->ghi_chu)
                    <div class="question-note">{{ $cauHoi->ghi_chu }}</div>
                @endif

                <ul class="answer-list">
                    @foreach ($cauHoi->dapAns as $dapAn)
                        <li class="@if($dapAn->ket_qua_dap_an===1) correct @endif">
                            <input type="radio" disabled>
                            {{ $dapAn->noi_dung }}
                            @if($dapAn->la_dap_an_dung)
                                <span style="color: #28a745">(Đúng)</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endsection
