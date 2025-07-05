@extends('layout.lecturer_layout')
<style>
    .editquestion-content {
        padding: 20px;
        margin: 0;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .form-editquestion {
        background: white;
        margin: 15px 0;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #ffc107;
        position: relative;
        overflow: hidden;
    }

    .form-editquestion::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ffc107, #e0a800);
    }

    .form-editquestion div {
        margin-bottom: 20px;
    }

    .form-editquestion label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-editquestion input[type="text"],
    .form-editquestion input[type="file"],
    .form-editquestion select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e8ed;
        border-radius: 6px;
        background: white;
        color: #2c3e50;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .form-editquestion input[type="file"] {
        padding: 10px;
        border-style: dashed;
        background: #f8f9fa;
        border-color: #ffc107;
    }

    .form-editquestion input[type="file"]:hover {
        background: #f1f3f4;
        border-color: #e0a800;
    }

    .form-editquestion input[type="text"]:focus,
    .form-editquestion select:focus {
        border-color: #ffc107;
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        background: #fafbfc;
    }

    .answer-inputs-container {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        border: 2px solid #e1e8ed;
        position: relative;
    }

    .answer-inputs-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #28a745, #20c997);
    }

    .answer-option-group {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        padding: 12px;
        background: white;
        border-radius: 6px;
        border: 2px solid #e1e8ed;
        transition: all 0.3s ease;
    }

    .answer-option-group:hover {
        border-color: #4a90e2;
        box-shadow: 0 2px 8px rgba(74, 144, 226, 0.1);
    }

    .answer-option-group input[type="text"] {
        flex: 1;
        margin-right: 15px;
        border: 1px solid #e1e8ed;
        padding: 10px 12px;
        border-radius: 4px;
        font-weight: 500;
    }

    .answer-option-group input[type="text"]:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
    }

    .answer-option-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #28a745;
        margin-right: 8px;
    }

    .editquestion-btn {
        display: block;
        width: 250px;
        margin: 30px auto;
        padding: 15px 30px;
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }

    .editquestion-btn:hover {
        background: linear-gradient(135deg, #e0a800, #d39e00);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
    }

    .editquestion-btn:active {
        transform: translateY(0);
    }

    .question h4 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 25px;
        text-align: center;
        color: #2c3e50;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .form-editquestion {
            padding: 20px 15px;
        }

        .answer-option-group {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .answer-option-group input[type="text"] {
            width: 100%;
            margin-right: 0;
            margin-bottom: 0;
        }

        .editquestion-btn {
            width: 100%;
            margin: 20px 0;
        }
    }
</style>
@section('content')
    <div class="editquestion-content">
        <div class="question">
            <h4>Sửa Câu Hỏi</h4>
            @php
                $currentCorrectAnswerIds = $cauHoi->dapAns->where('ket_qua_dap_an', 1)->pluck('ma_dap_an')->toArray();
            @endphp
            <form action="{{ route('capNhatCauHoi', $cauHoi->ma_cau_hoi) }}" method="POST" class="form-editquestion"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div>
                    <label for="questionContent"> Câu hỏi:</label>
                    <input type="text" id="questionContent" name="noi_dung_cau_hoi" class="text"
                        value="{{ old('noi_dung_cau_hoi', $cauHoi->noi_dung) }}">
                </div>
                <div>
                    <label for="question-img"> Hình ảnh (nếu có):</label>
                    <input type="file" id="imageInput" accept="image/*" name="hinh_anh">
                    <img id="xemTruocHinhAnh_1" src="{{ $cauHoi->hinh_anh ? asset('images/' . $cauHoi->hinh_anh) : '#' }}"
                        alt="Ảnh xem trước"
                        style="max-width: 150px; max-height: 150px; margin-top: 10px; {{ $cauHoi->hinh_anh ? 'display:block;' : 'display:none;' }}">
                </div>
                <div>
                    <label for="questionNote"> Ghi chú (nếu có):</label>
                    <input type="text" id="questionNote" name="ghi_chu" class="text"
                        value="{{ old('ghi_chu', $cauHoi->ghi_chu) }}">
                </div>
                <div>
                    <label for="do_kho">Độ khó</label>
                    <select id="do_kho" name="do_kho">
                        <option value="Dễ" {{ old('do_kho', $cauHoi->do_kho) == 'Dễ' ? 'selected' : '' }}>Dễ</option>
                        <option value="Trung bình" {{ old('do_kho', $cauHoi->do_kho) == 'Trung bình' ? 'selected' : '' }}>
                            Trung bình</option>
                        <option value="Khó" {{ old('do_kho', $cauHoi->do_kho) == 'Khó' ? 'selected' : '' }}>Khó</option>
                    </select>
                </div>

                <div class="answer-inputs-container">
                    @foreach ($cauHoi->dapAns as $index => $dapAn)
                        <div class="answer-option-group">
                            <input type="hidden" name="dap_ans[{{ $index }}][ma_dap_an]" value="{{ $dapAn->ma_dap_an }}">
                            <input type="checkbox" id="dapAnDung_{{ $dapAn->ma_dap_an }}" name="ma_dap_an_dung[]"
                                value="{{ $dapAn->ma_dap_an }}" {{ in_array((string) $dapAn->ma_dap_an, old('ma_dap_an_dung', $currentCorrectAnswerIds)) ? 'checked' : '' }}>


                            <label for="dapAnDung_{{ $dapAn->ma_dap_an }}" class="sr-only">Đáp án đúng</label>

                            <input type="text" class="answer-input" placeholder="Nội dung đáp án {{ $index + 1 }}"
                                name="dap_ans[{{ $index }}][noi_dung]"
                                value="{{ old("dap_ans.$index.noi_dung", $dapAn->noi_dung) }}">
                        </div>
                    @endforeach
                </div>
                @if ($errors->any())
                    <div style="color: red; padding: 10px; border: 1px solid red; margin-top: 10px;">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <button type="submit" class="editquestion-btn"> Sửa câu hỏi </button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/question_management_lecturer.js') }}"></script>
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