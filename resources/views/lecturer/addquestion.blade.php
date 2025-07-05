@extends('layout.lecturer_layout')
<style>
    .addquestion-content {
        padding: 20px;
        margin: 0;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .page-title {
        text-align: center;
        margin-bottom: 25px;
        color: #2c3e50;
        font-size: 1.8rem;
        font-weight: 600;
        padding: 20px 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .count-question {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 15px;
        border-left: 4px solid #4a90e2;
    }

    .count-question label {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
        white-space: nowrap;
    }

    .count-question select {
        padding: 10px 15px;
        border: 2px solid #e1e8ed;
        border-radius: 6px;
        background: white;
        color: #2c3e50;
        font-weight: 500;
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .count-question select:focus {
        border-color: #4a90e2;
        outline: none;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }

    #count-question-btn {
        padding: 10px 20px;
        background: #4a90e2;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        white-space: nowrap;
    }

    #count-question-btn:hover {
        background: #357abd;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }

    .form-addquestion {
        background: white;
        margin: 15px 0;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #4a90e2;
        position: relative;
        overflow: hidden;
    }

    .form-addquestion::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #4a90e2, #357abd);
    }

    .question-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f3f4;
    }

    .question-number {
        background: linear-gradient(135deg, #4a90e2, #357abd);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        margin-right: 15px;
        font-size: 0.9rem;
        box-shadow: 0 2px 8px rgba(74, 144, 226, 0.3);
    }

    .question-header h3 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-group input[type="text"],
    .form-group input[type="file"],
    .form-group select {
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

    .form-group input[type="text"]:focus,
    .form-group select:focus {
        border-color: #4a90e2;
        outline: none;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        background: #fafbfc;
    }

    .form-group input[type="file"] {
        padding: 10px;
        border-style: dashed;
        background: #f8f9fa;
        border-color: #4a90e2;
    }

    .form-group input[type="file"]:hover {
        background: #f1f3f4;
        border-color: #357abd;
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .form-row .form-group {
        flex: 1;
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

    .answer-inputs-container h4 {
        margin-bottom: 15px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.1rem;
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

    .checkbox-label {
        font-weight: 600;
        color: #28a745;
        cursor: pointer;
        font-size: 0.9rem;
        user-select: none;
    }

    .addquestion-btn {
        display: block;
        width: 250px;
        margin: 30px auto;
        padding: 15px 30px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .addquestion-btn:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .addquestion-btn:active {
        transform: translateY(0);
    }

    /* Alert styles để match với giao diện */
    .alert {
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 6px;
        border-left: 4px solid;
        font-weight: 500;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left-color: #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left-color: #dc3545;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .addquestion-content {
            padding: 10px;
        }

        .page-title {
            font-size: 1.5rem;
            padding: 15px 0;
        }

        .count-question {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .count-question select,
        #count-question-btn {
            width: 100%;
        }

        .form-addquestion {
            padding: 20px 15px;
        }

        .question-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .form-row {
            flex-direction: column;
            gap: 15px;
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

        .addquestion-btn {
            width: 100%;
            margin: 20px 0;
        }
    }

    /* Animation cho form mới */
    .form-addquestion.new-form {
        animation: slideInUp 0.5s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loading state */
    .loading {
        opacity: 0.7;
        pointer-events: none;
        position: relative;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 30px;
        height: 30px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #4a90e2;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    /* Cải thiện focus states */
    *:focus-visible {
        outline: 2px solid #4a90e2;
        outline-offset: 2px;
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .pdfPopup {
        display: none;
        /* Hiển thị bằng JS khi cần */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .pdfPopup-content {
        background: white;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.3s ease-in-out;
    }

    .pdfPopup-content h3 {
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }

    .pdfPopup .form-group {
        margin-bottom: 15px;
    }

    .pdfPopup .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2c3e50;
    }

    .pdfPopup .form-group select,
    .pdfPopup .form-group input[type="file"] {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    .pdfPopup .form-group input[type="file"] {
        background: #f9f9f9;
    }

    .pdfPopup-btn {
        display: block;
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pdfPopup-btn:hover {
        background: linear-gradient(135deg, #e67e22 0%, #f1c40f 100%);
        transform: translateY(-1px);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .add-question-btn-import {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 6px rgba(253, 126, 20, 0.3);
        outline: none;
        margin-bottom: 10px;
        justify-content: end;
    }

    .add-question-btn-import:hover {
        background: linear-gradient(135deg, #e67e22 0%, #f1c40f 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(253, 126, 20, 0.4);
        color: white;
        text-decoration: none;
    }
</style>

@section('content')
    <div class="addquestion-content">
        <h1 class="page-title">Thêm Câu Hỏi Mới</h1>

        <button type="button" class="add-question-btn-import">Thêm câu hỏi pdf</button>
        <div class="count-question">
            <label for="question-count">Số lượng câu hỏi:</label>
            <select id="question-count" class="form-select">
                <option value="1">1 câu hỏi</option>
                <option value="2">2 câu hỏi</option>
                <option value="3">3 câu hỏi</option>
                <option value="4">4 câu hỏi</option>
                <option value="5">5 câu hỏi</option>
            </select>
            <button type="button" id="count-question-btn">Thêm Form</button>
        </div>

        <div class="question">
            <form action="{{ route('question_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-addquestion">
                    <div class="question-header">
                        <span class="question-number">Câu 1</span>
                        <h3>Thông tin câu hỏi</h3>
                    </div>

                    <div class="form-group">
                        <label>Nội dung câu hỏi:</label>
                        <input type="text" name="question_content_1" placeholder="Nhập nội dung câu hỏi..." required>
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh (tùy chọn):</label>
                        <input type="file" name="question_image_1" accept="image/*" class="input-hinh-anh" data-index="1">
                        <img id="xemTruocHinhAnh_1" class="xem-truoc-hinh-anh" src="#" alt="Xem trước"
                            style="display: none; max-width: 200px; margin-top: 10px;">
                    </div>

                    <div class="form-group">
                        <label>Ghi chú (tùy chọn):</label>
                        <input type="text" name="note_1" placeholder="Thêm ghi chú cho câu hỏi...">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Độ khó:</label>
                            <select name="difficulty_1" required>
                                <option value="">Chọn độ khó</option>
                                <option value="Dễ">Dễ</option>
                                <option value="Trung bình">Trung bình</option>
                                <option value="Khó">Khó</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Môn học:</label>
                            <select name="subject_1" required>
                                <option value="">Chọn môn học</option>
                                @foreach ($danhSachMonHoc as $monHoc)
                                    <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="answer-inputs-container">
                        <h4>Các đáp án (chọn đáp án đúng)</h4>
                        @for ($i = 0; $i < 4; $i++)
                            <div class="answer-option-group">
                                <input type="text" name="answers_1[{{ $i }}][text]" placeholder="Đáp án {{ chr(65 + $i) }}"
                                    required>
                                <input type="checkbox" name="answers_1[{{ $i }}][is_correct]" value="1" id="answer_1_{{ $i }}">
                                <label for="answer_1_{{ $i }}" class="checkbox-label">Đúng</label>
                            </div>
                        @endfor
                    </div>
                </div>

                <div id="form-container"></div>
                <button type="submit" class="addquestion-btn">Lưu Tất Cả Câu Hỏi</button>
            </form>
        </div>
        <div class="pdfPopup" id="pdfPopup">
            <div class="pdfPopup-content">
                <h3>Thêm Câu Hỏi từ PDF</h3>
                <form action="{{ route('question_store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="color: red;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="monHoc">Chọn Môn Học:</label>
                        <select id="monHoc" name="subject_pdf" required>
                            <option value="">-- Chọn môn học --</option>
                            @foreach($danhSachMonHoc as $monHoc)
                                <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="difficulty_pdf">Chọn Độ khó:</label>
                        <select name="difficulty_pdf" required>
                            <option value="">Chọn độ khó</option>
                            <option value="Dễ">Dễ</option>
                            <option value="Trung bình">Trung bình</option>
                            <option value="Khó">Khó</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pdf_file">Chọn File PDF:</label>
                        <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" required>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label>📄 Định dạng mẫu trong file PDF:</label>
                        <div
                            style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; font-size: 14px; font-family: monospace; white-space: pre-line;">
                            Câu 1: Thủ đô của Việt Nam là gì?
                            A. TP.HCM
                            B. Hà Nội
                            C. Đà Nẵng
                            D. Hải Phòng
                            Đáp án đúng: B
                        </div>
                    </div>
                    <button type="submit" class="pdfPopup-btn">Thêm Câu Hỏi</button>
                </form>
            </div>
        </div>
    </div>
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
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4a90e2',
                    background: '#fff',
                    color: '#2c3e50'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Có lỗi xảy ra!',
                    text: {!! json_encode(session('error')) !!},
                    confirmButtonText: 'Thử lại',
                    confirmButtonColor: '#dc3545',
                    background: '#fff',
                    color: '#2c3e50'
                });
            @endif

            btn = document.getElementById("count-question-btn");
            const select = document.getElementById("question-count");
            const container = document.getElementById("form-container");
            const danhSachMonHoc = @json($danhSachMonHoc ?? []);
            let currentIndex = 1;

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
                                                        <div class="form-addquestion new-form">
                                                            <div class="question-header">
                                                                <span class="question-number">Câu ${index}</span>
                                                                <h3>Thông tin câu hỏi</h3>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Nội dung câu hỏi:</label>
                                                                <input type="text" name="question_content_${index}" placeholder="Nhập nội dung câu hỏi...">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Hình ảnh (tùy chọn):</label>
                                                                <input type="file" name="question_image_${index}" accept="image/*" class="input-hinh-anh" data-index="${index}">
                                                                <img id="xemTruocHinhAnh_${index}" class="xem-truoc-hinh-anh" src="#" alt="Xem trước"
                                                                    style="display: none; max-width: 200px; margin-top: 10px;">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Ghi chú (tùy chọn):</label>
                                                                <input type="text" name="note_${index}" placeholder="Thêm ghi chú cho câu hỏi...">
                                                            </div>

                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Độ khó:</label>
                                                                    <select name="difficulty_${index}">
                                                                        <option value="">Chọn độ khó</option>
                                                                        <option value="Dễ">Dễ</option>
                                                                        <option value="Trung bình">Trung bình</option>
                                                                        <option value="Khó">Khó</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Môn học:</label>
                                                                    <select name="subject_${index}">
                                                                        <option value="">Chọn môn học</option>
                                                                        ${options}
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="answer-inputs-container">
                                                                <h4>Các đáp án (chọn đáp án đúng)</h4>
                                                                ${[0, 1, 2, 3].map(j => `
                                                                    <div class="answer-option-group">
                                                                        <input type="text" name="answers_${index}[${j}][text]" 
                                                                               placeholder="Đáp án ${String.fromCharCode(65 + j)}">
                                                                        <input type="checkbox" name="answers_${index}[${j}][is_correct]" 
                                                                               value="1" id="answer_${index}_${j}">
                                                                        <label for="answer_${index}_${j}" class="checkbox-label">Đúng</label>
                                                                    </div>
                                                                `).join('')}
                                                            </div>
                                                        </div>`;
                    container.insertAdjacentHTML("beforeend", formHTML);
                }

                const newForm = container.lastElementChild;
                if (newForm) {
                    newForm.scrollIntoView({ behavior: 'smooth' });
                }
            });
            document.body.addEventListener("change", function (e) {
                if (e.target.matches(".input-hinh-anh")) {
                    const input = e.target;
                    const index = input.dataset.index;
                    const preview = document.getElementById(`xemTruocHinhAnh_${index}`);
                    const file = input.files[0];

                    if (file) {
                        const objectURL = URL.createObjectURL(file);
                        preview.src = objectURL;
                        preview.style.display = "block";
                        preview.onload = () => {
                            URL.revokeObjectURL(objectURL);
                        };
                    } else {
                        preview.src = "#";
                        preview.style.display = "none";
                    }
                }
            });
            document.querySelector(".add-question-btn-import").addEventListener("click", function () {
                document.getElementById("pdfPopup").style.display = "flex";
            });

            function closePdfPopup() {
                document.getElementById("pdfPopup").style.display = "none";
            }
            document.body.addEventListener("click", function (e) {
                const popup = document.getElementById("pdfPopup");
                if (e.target === popup) {
                    closePdfPopup();
                }
            });
        });
    </script>
@endsection