@extends('layout.lecturer_layout')
<style>
    .editquestion-content {
        margin: 20px;
    }

    .form-editquestion {
        margin: 50px 0px;
        padding-bottom: 20px;
        border-bottom: 1px solid black;
    }


    .form-editquestion label {
        width: 20%;
        margin-bottom: 15px;
    }

    .form-editquestion #questionContent {
        padding: 10px;
        width: 50%;
        border-radius: 5px;
    }

    .form-editquestion #questionNote {
        padding: 10px;
        width: 50%;
        border-radius: 5px;
    }

    .answer-inputs-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .answer-inputs-container .answer-input {
        min-width: 150px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .editquestion-btn {
        padding: 10px 20px;
        border-radius: 5px;
        background-color: #59c0f7;
        cursor: pointer;
    }

    .editquestion-btn:hover {
        border-radius: 10px;
        box-shadow: 3px 3px 5px rgb(0, 0, 0, 0.3);
    }
</style>
@section('content')
    <div class="editquestion-content">
        <div class="question">
            <h4>Sửa Câu Hỏi</h4>
            @php
                // Lấy danh sách các ID đáp án đúng hiện tại từ cơ sở dữ liệu
                $currentCorrectAnswerIds = $cauHoi->dapAns->where('ket_qua_dap_an', 1)->pluck('ma_dap_an')->toArray();
                // KHÔNG cần biến $selectedCorrectAnswerIds ở đây nữa, chúng ta sẽ sử dụng old() trực tiếp
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
                    @if ($cauHoi->hinh_anh)
                        <img id="previewImage" src="{{ asset('storage/' . $cauHoi->hinh_anh) }}" alt="Ảnh hiện tại"
                            style="max-width: 150px; max-height: 150px; margin-top: 10px; display: block;">
                    @else
                        <img id="previewImage" src="#" alt="Ảnh xem trước"
                            style="max-width: 150px; max-height: 150px; margin-top: 10px; display: none;">
                    @endif
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