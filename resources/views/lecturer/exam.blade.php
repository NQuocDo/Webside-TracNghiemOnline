@extends('layout.lecturer_layout')
<style>
    /* CSS cải tiến cho trang tạo đề thi */

    .exam-content {
        height: 100%;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .exam-item-main {
        margin: 20px auto;
        max-width: 1200px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .exam-question-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-bottom: none;
        margin-bottom: 0;
    }

    .exam-question-header span {
        font-weight: 600;
        margin-right: 15px;
        font-size: 16px;
    }

    .exam-question-header input {
        padding: 12px 15px;
        border: none;
        border-radius: 25px;
        margin: 0 15px 0 5px;
        font-size: 14px;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
        min-width: 200px;
    }

    .exam-question-header input:focus {
        outline: none;
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        transform: scale(1.02);
    }

    .exam-question-header input::placeholder {
        color: #888;
    }

    .exam-question-body {
        padding: 30px;
    }

    .question-number {
        background: #fff;
        border: 2px solid #e1e8ed;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .question-number:hover {
        border-color: #667eea;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .question-count {
        color: #667eea;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        display: block;
        position: relative;
    }

    .question-count::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }

    .question-content {
        font-size: 16px;
        line-height: 1.6;
        color: #2c3e50;
        margin: 20px 0;
        font-weight: 500;
    }

    .question-src {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin: 15px 0;
        transition: transform 0.3s ease;
    }

    .question-src:hover {
        transform: scale(1.05);
    }

    .question-src:not([src*="."]) {
        display: none;
    }

    .question-note {
        color: #7f8c8d !important;
        font-size: 14px !important;
        font-style: italic;
        margin: 10px 0 20px 0;
        padding: 10px;
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        border-radius: 0 8px 8px 0;
    }

    .answer-list {
        list-style: none;
        margin: 25px 0;
        padding: 0;
    }

    .answer-list li {
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: 12px;
        margin: 12px 0;
        padding: 15px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        position: relative;
    }

    .answer-list li:hover {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-color: #667eea;
        transform: translateX(5px);
        box-shadow: 0 3px 15px rgba(102, 126, 234, 0.15);
    }

    .answer-list li input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 15px;
        cursor: pointer;
        accent-color: #667eea;
        transform: scale(1.2);
    }

    .answer-list li label {
        cursor: pointer;
        font-size: 15px;
        color: #2c3e50;
        font-weight: 500;
        flex: 1;
    }

    .edit-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #ff9a56 0%, #ffad56 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(255, 154, 86, 0.3);
    }

    .edit-btn:hover {
        background: linear-gradient(135deg, #ff8c42 0%, #ff9642 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 154, 86, 0.4);
    }

    .edit-btn i {
        margin-right: 5px;
    }

    .exam-question-footer {
        background: #f8f9fa;
        padding: 25px 30px;
        text-align: center;
        border-top: 1px solid #e1e8ed;
    }

    .save-exam-btn {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(86, 171, 47, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .save-exam-btn:hover {
        background: linear-gradient(135deg, #4a9b27 0%, #96d9b8 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(86, 171, 47, 0.4);
    }

    .save-exam-btn:active {
        transform: translateY(-1px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .exam-item-main {
            margin: 10px;
            border-radius: 10px;
        }

        .exam-question-header {
            padding: 20px 15px;
            flex-direction: column;
            gap: 15px;
        }

        .exam-question-header input {
            min-width: 100%;
            margin: 5px 0;
        }

        .exam-question-body {
            padding: 20px 15px;
        }

        .question-number {
            padding: 20px 15px;
        }

        .edit-btn {
            position: static;
            margin-top: 15px;
            width: 100%;
        }

        .answer-list li {
            padding: 12px 15px;
        }
    }

    /* Animation cho trang load */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .question-number {
        animation: fadeInUp 0.6s ease forwards;
    }

    .question-number:nth-child(1) {
        animation-delay: 0.1s;
    }

    .question-number:nth-child(2) {
        animation-delay: 0.2s;
    }

    .question-number:nth-child(3) {
        animation-delay: 0.3s;
    }

    /* Custom scrollbar */
    .exam-content::-webkit-scrollbar {
        width: 8px;
    }

    .exam-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .exam-content::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .exam-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }
</style>
@section('content')
    <div class="exam-content">
        <div class="exam-item-main">
            <form action="{{ route('exam_store')}}" method="POST">
                @csrf
                <input type="hidden" name="ma_mon_hoc" value="{{ $maMonHoc }}">
                <div class="exam-question-header">
                    <span>Tên đề thi:</span>
                    <input type="text" value="" name="ten_de_thi" placeholder="Nhập tên đề thi" class="exam-info" required>
                    <span>Thời gian làm bài</span>
                    <input type="text" value="" name="thoi_gian_lam_bai" placeholder="Nhập thời gian" class="exam-time"
                        required>
                </div>
                <div class="exam-question-body">
                    @foreach ($cauHoiDaChon as $index => $cauHoi)
                        <div class="question-number">
                            <strong class="question-count">Câu hỏi {{ $index + 1 }}:</strong>
                            <input type="hidden" name="ma_cau_hoi[]" value="{{ $cauHoi->ma_cau_hoi }}">

                            <div class="question-content">{{ $cauHoi->noi_dung }}</div>

                            @if ($cauHoi->hinh_anh)
                                <img src="{{ asset('storage/cauhoi_images/' . $cauHoi->hinh_anh) }}" alt="Hình câu hỏi"
                                    class="question-src" width="10%">
                            @endif

                            @if ($cauHoi->ghi_chu)
                                <div class="question-note" style="color:gray;font-size:15px">
                                    {{ $cauHoi->ghi_chu }}
                                </div>
                            @endif

                            <ul class="answer-list">
                                @foreach ($cauHoi->dapAns as $i => $dapAn)
                                    <li>
                                        <label for="answer_{{ $cauHoi->ma_cau_hoi }}_{{ $i }}">
                                            {{ $dapAn->noi_dung }}
                                            @if ($dapAn->ket_qua_dap_an == 1)
                                                <i class="fas fa-check-circle text-success ms-1" title="Đáp án đúng"></i>
                                            @endif
                                        </label>
                                    </li>
                                @endforeach
                            </ul>

                            <a href="{{ route('hienThiCauHoi', ['id' => $cauHoi->ma_cau_hoi, 'return_url' => url()->current()]) }}"
                                class="btn btn-primary btn-sm" title="Sửa câu hỏi">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="exam-question-footer">
                    <button type="submit" class="save-exam-btn">Lưu đề thi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            });
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
@endsection