@extends('layout.lecturer_layout')
<style>
    .addquestion-content {
        padding: 20px;
        margin: 20px;
    }

    .form-addquestion {
        margin: 50px 0px;
        padding-bottom: 20px;
        border-bottom: 1px solid black;
    }

    #count-question-btn {
        margin-left: 10px;
        padding: 8px 15px;
        color: #fff;
        background-color: #59c0f7;
        border: 1px solid black;
        border-radius: 6px;
        color: black;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        transition: background-color 0.3s ease;
    }

    #count-question-btn:hover {
        border-radius: 10px;
        box-shadow: 1px 1px 3px rgb(0, 0, 0, 0.15);
    }

    .form-addquestion label {
        width: 20%;
        margin-bottom: 15px;
    }

    .form-addquestion select {
        width: 20%;
        padding: 8px;
    }

    .form-addquestion #questionContent {
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
        flex-grow: 1;
        min-width: 150px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .addquestion-btn {
        padding: 10px 20px;
        border-radius: 5px;
        background-color: #59c0f7;
        cursor: pointer;
    }

    .addquestion-btn:hover {
        border-radius: 10px;
        box-shadow: 3px 3px 5px rgb(0, 0, 0, 0.3);
    }
</style>
@section('content')
    <div class="addquestion-content">
        <div class="count-question" style="margin-bottom:30px;">
            <label for="question-count" style="margin-right: 5px;">Số lượng câu hỏi:</label>
            <select id="question-count" class="form-select" style="width: auto; display: inline-block;">
                <option value="1">1 câu hỏi</option>
                <option value="2">2 câu hỏi</option>
                <option value="3">3 câu hỏi</option>
                <option value="4">4 câu hỏi</option>
            </select>
            <button type="button" id="count-question-btn">Thêm</button>
        </div>
        <div class="question">
            <form action="{{ route('question.store') }}" method="POST" enctype="multipart/form-data"
                class="form-addquestion">
                @csrf
                <div class="form-addquestion" style="margin-bottom: 30px; border: 1px solid #ccc; padding: 20px;">
                    <div>
                        <label>Câu hỏi 1:</label>
                        <input type="text" name="question_content_1" class="text" placeholder="Nhập câu hỏi"
                            style="width:50%;padding:10px;">
                    </div>

                    <div>
                        <label>Hình ảnh (nếu có):</label>
                        <input type="file" name="question_image_1" accept="image/*">
                    </div>

                    <div>
                        <label>Ghi chú (nếu có):</label>
                        <input type="text" name="note_1" class="text" placeholder="Nhập ghi chú"
                            style="width:50%;padding:10px;">
                    </div>

                    <div>
                        <label>Độ khó:</label>
                        <select name="difficulty_1" required>
                            <option value="">Chọn độ khó</option>
                            <option value="Dễ">Dễ</option>
                            <option value="Trung bình">Trung bình</option>
                            <option value="Khó">Khó</option>
                        </select>
                    </div>

                    <div>
                        <label>Môn học:</label>
                        <select name="subject_1" required>
                            <option value="">Chọn môn</option>
                            @foreach ($danhSachMonHoc as $monHoc)
                                <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="answer-inputs-container">
                        @for ($i = 0; $i < 4; $i++)
                            <div>
                                <input type="text" class="answer-input" name="answers_1[{{ $i }}][text]"
                                    placeholder="Đáp án {{ chr(65 + $i) }}">
                                <input type="checkbox" name="answers_1[{{ $i }}][is_correct]" value="1">
                            </div>
                        @endfor
                    </div>
                </div>
                <div id="form-container"></div>
                <button type="submit" id="submitAllForms" class="addquestion-btn"> Thêm câu hỏi </button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("count-question-btn");
            const select = document.getElementById("question-count");
            const container = document.getElementById("form-container");
            const danhSachMonHoc = @json($danhSachMonHoc ?? []);

            let currentIndex = 1;

            // Đếm form hiện có
            while (document.querySelector(`[name="question_content_${currentIndex}"]`)) {
                currentIndex++;
            }

            btn.addEventListener("click", function () {
                const count = parseInt(select.value);

                let options = '';
                danhSachMonHoc.forEach(mon => {
                    options += `<option value="${mon.ma_mon_hoc}">${mon.ten_mon_hoc}</option>`;
                });

                for (let i = 0; i < count; i++) {
                    const index = currentIndex++;
                    const formHTML = `
                                                    <div class="form-addquestion" style="margin-bottom: 30px; border: 1px solid #ccc; padding: 20px;">
                                                        <div>
                                                            <label>Câu hỏi ${index}:</label>
                                                            <input type="text" name="question_content_${index}" class="text" placeholder="Nhập câu hỏi" style="width:50%;padding:10px;">
                                                        </div>
                                                        <div>
                                                            <label>Hình ảnh (nếu có):</label>
                                                            <input type="file" name="question_image_${index}" accept="image/*">
                                                        </div>
                                                        <div>
                                                            <label>Ghi chú (nếu có):</label>
                                                            <input type="text" name="note_${index}" class="text" placeholder="Nhập ghi chú" style="width:50%;padding:10px;">
                                                        </div>
                                                        <div>
                                                            <label>Độ khó:</label>
                                                            <select name="difficulty_${index}">
                                                                <option value="">Chọn độ khó</option>
                                                                <option value="Dễ">Dễ</option>
                                                                <option value="Trung bình">Trung bình</option>
                                                                <option value="Khó">Khó</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label>Môn học:</label>
                                                            <select name="subject_${index}">
                                                                <option value="">Chọn môn</option>
                                                                ${options}
                                                            </select>
                                                        </div>
                                                        <div class="answer-inputs-container">
                                                            ${[0, 1, 2, 3].map(j => `
                                                                <div class="answer-option-group">
                                                                    <input type="text" name="answers_${index}[${j}][text]" class="answer-input" placeholder="Đáp án ${String.fromCharCode(65 + j)}">
                                                                     <input type="checkbox" name="answers_${index}[${j}][is_correct]" value="1">
                                                                </div>
                                                            `).join('')}
                                                        </div>
                                                    </div>`;
                    container.insertAdjacentHTML("beforeend", formHTML);
                }
            });
        });
    </script>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: {!! json_encode(session('success')) !!},
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Thất bại!',
                    text: {!! json_encode(session('error')) !!},
                    confirmButtonText: 'Đóng'
                });
            @endif
            });
    </script>
@endsection