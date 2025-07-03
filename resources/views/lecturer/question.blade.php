@extends('layout.lecturer_layout')
    <style>
        /* ========== CONTAINER CHÍNH ========== */
        .question-content {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ========== HEADER SECTION ========== */
        .main-question-header {
            margin-bottom: 25px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .add-question-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(40, 167, 69, 0.3);
        }

        .add-question-btn:hover {
            background: linear-gradient(135deg, #218838 0%, #1cc88a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.4);
            color: white;
            text-decoration: none;
        }

        /* ========== FORM SEARCH ========== */
        .form-search-question {
            display: flex;
            align-items: center;
            gap: 15px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .form-search-question .mb-2 {
            margin-bottom: 0 !important;
        }

        .form-control {
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            width: 300px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-select {
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            background-color: white;
            cursor: pointer;
            min-width: 200px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        /* ========== TABLE CONTAINER ========== */
        .main-question-body {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        /* ========== TABLE STYLING ========== */
        .question-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header */
        .question-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .question-table thead th {
            padding: 16px 12px;
            text-align: center;
            color: white;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            white-space: nowrap;
        }

        .question-table thead th:last-child {
            border-right: none;
        }

        /* Body */
        .question-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .question-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .question-table tbody tr:hover {
            background-color: #e3f2fd;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .question-table tbody td {
            padding: 16px 12px;
            vertical-align: top;
            border-right: 1px solid #e9ecef;
            font-size: 14px;
        }

        .question-table tbody td:last-child {
            border-right: none;
        }

        /* ========== SPECIFIC CELLS ========== */

        /* STT và Checkbox cell */
        .checkbox-stt-cell {
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            width: 80px;
        }

        .question-checkbox {
            margin-right: 8px;
            transform: scale(1.2);
            cursor: pointer;
        }

        /* Question content */
        .question-text {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 12px;
            line-height: 1.5;
            font-size: 15px;
        }

        .answers-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .answers-container:hover {
            background-color: #e9ecef;
        }

        .answer-item {
            flex: 1;
            font-size: 13px;
            color: #495057;
            margin: 0;
        }

        .answers-container .fas {
            font-size: 16px;
            flex-shrink: 0;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        /* Difficulty cell */
        .question-table tbody td:nth-child(3) {
            text-align: center;
            width: 100px;
        }

        /* Image cell */
        .question-table tbody td:nth-child(4) {
            text-align: center;
            width: 100px;
        }

        .thumb-img {
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .thumb-img:hover {
            transform: scale(1.1);
        }

        .text-muted {
            color: #6c757d;
            font-style: italic;
        }

        /* Subject cell */
        .subject-cell {
            text-align: center;
            font-weight: 500;
            color: #495057;
            width: 120px;
        }

        /* Scope cell */
        .scope-cell {
            width: 150px;
            text-align: center;
        }

        .scope-dropdown {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background-color: white;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .scope-dropdown:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
        }

        /* Actions cell */
        .actions-cell {
            width: 140px;
            text-align: center;
            white-space: nowrap;
        }

        .btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
            color: white;
            text-decoration: none;
        }

        .delete-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }

        /* ========== CREATE EXAM SECTION ========== */
        .create-exam {
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .create-exam-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            margin: 0 10px;
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(23, 162, 184, 0.3);
        }

        .create-exam-btn:hover {
            background: linear-gradient(135deg, #138496 0%, #0f6674 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(23, 162, 184, 0.4);
        }

        /* ========== PAGINATION ========== */
        .main-question-footer {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #f8f9fa;
            color: #495057;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }

        .pagination a:hover:not(.disabled) {
            background: #007bff;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .pagination a.active {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .pagination a.disabled {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* ========== EMPTY STATE ========== */
        .text-center.text-muted {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px 20px;
            background-color: #f8f9fa;
        }

        /* ========== RESPONSIVE DESIGN ========== */
        @media (max-width: 1200px) {
            .question-table {
                font-size: 13px;
            }

            .form-control {
                width: 250px;
            }
        }

        @media (max-width: 992px) {
            .form-search-question {
                flex-direction: column;
                align-items: stretch;
            }

            .form-control,
            .form-select {
                width: 100%;
            }

            .question-table {
                font-size: 12px;
            }

            .question-table thead th,
            .question-table tbody td {
                padding: 10px 8px;
            }
        }

        @media (max-width: 768px) {
            .question-content {
                padding: 15px;
            }

            .question-table thead {
                display: none;
            }

            .question-table tbody tr {
                display: block;
                margin-bottom: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border: none;
            }

            .question-table tbody td {
                display: block;
                border: none;
                border-bottom: 1px solid #e9ecef;
                padding: 12px 15px;
                position: relative;
                padding-left: 120px;
            }

            .question-table tbody td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 15px;
                top: 12px;
                font-weight: 600;
                color: #2c3e50;
                width: 100px;
            }

            .checkbox-stt-cell:before {
                content: "STT: ";
            }

            .actions-cell {
                text-align: left;
            }

            .create-exam-btn {
                display: block;
                width: 90%;
                margin: 10px auto;
            }
        }

        .custom-popup-overlay {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .custom-popup {
            background: white;
            width: 400px;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 576px) {
            .add-question-btn {
                width: 100%;
                justify-content: center;
            }

            .pagination a {
                width: 35px;
                height: 35px;
                font-size: 12px;
            }
        }
    </style>
@section('content')
    <div class="question-content">
        <div class="main-question-header">
            <a href="{{ route('addquestion') }}" class="add-question-btn">
                Thêm câu hỏi
            </a>
        </div>
        <form action="{{ route('question') }}" method="GET" id="filter-form" class="form-search-question"
            style="justify-content: end;">
            <div class="mb-2 me-3">
                <input type="text" class="form-control" id="keyword" name="tu_khoa_tim_kiem"
                    placeholder="Tìm theo nội dung câu hỏi" value="{{ $tuKhoaTimKiem }}">
            </div>

            <div class="mb-2 me-3">
                <select class="form-select" name="mon_hoc_id" id="monHocSelect">
                    <option value="">-- Tất cả môn học --</option>
                    @foreach ($danhSachMonHoc as $mon)
                        <option value="{{ $mon->ma_mon_hoc }}" {{ $locTheoMocHoc == $mon->ma_mon_hoc ? 'selected' : '' }}>
                            {{ $mon->ten_mon_hoc }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="main-question-body">
            <form action="{{ route('create_exam') }}" method="GET" class="form-create-exam" id="form-create-exam"> @csrf
                <table class="question-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Nội dung Câu hỏi</th>
                            <th>Độ khó</th>
                            <th>Hình ảnh</th>
                            <th>Môn</th>
                            <th class="actions-column">Phạm vi</th>
                            <th class="actions-column">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($danhSachCauHoi->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center text-muted">Không có câu hỏi nào được tìm thấy.</td>
                            </tr>
                        @else
                            @foreach ($danhSachCauHoi as $index => $cauHoi)
                                <tr>
                                    <td class="checkbox-stt-cell">
                                        <input type="checkbox" class="question-checkbox" name="ma_cau_hoi[]"
                                            value="{{ $cauHoi->ma_cau_hoi }}" data-id="{{ $cauHoi->ma_cau_hoi }}"
                                            style="margin-right: 5px;">
                                        {{ $danhSachCauHoi->firstItem() + $index }}
                                    </td>
                                    <td data-label="Nội dung Câu hỏi">
                                        <div class="question-text">{{ $cauHoi->noi_dung }}</div>
                                        @foreach ($cauHoi->dapAns as $dapAn)
                                            <div class="answers-container">
                                                <div class="answer-item">{{ $dapAn->noi_dung }}</div>

                                                @if($dapAn->ket_qua_dap_an == 1)
                                                    <i class="fas fa-check-circle text-success ms-1" title="Đáp án đúng"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger ms-1" title="Đáp án sai"></i>
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ $cauHoi->do_kho }}</td>
                                    <td data-label="Hình ảnh" style="text-align: center">
                                        @if($cauHoi->hinh_anh)
                                            <img src="{{ asset('images/' . $cauHoi->hinh_anh) }}" width="70px" height="70px"
                                                alt="Ảnh nhỏ" class="thumb-img" data-src="{{ asset('images/' . $cauHoi->hinh_anh) }}"
                                                data-id="{{ $cauHoi->ma_cau_hoi }}">
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td class="subject-cell" data-label="Môn học">
                                        {{ $cauHoi->monHoc->ten_mon_hoc ?? 'N/A' }}
                                    </td>
                                    <td data-label="Phạm vi" class="scope-cell" style="width:150px;">
                                        <select class="scope-dropdown" data-id="{{ $cauHoi->ma_cau_hoi }}">
                                            <option value="cong_khai" {{ $cauHoi->pham_vi == 'cong_khai' ? 'selected' : '' }}>Công
                                                khai</option>
                                            <option value="rieng_tu" {{ $cauHoi->pham_vi == 'rieng_tu' ? 'selected' : '' }}>Riêng tư
                                            </option>
                                        </select>
                                    </td>
                                    <td data-label="Thao tác" class="actions-cell">
                                        <a href="{{ route('hienThiCauHoi', ['id' => $cauHoi->ma_cau_hoi]) }}"
                                            class="btn btn-primary btn-sm" title="Sửa câu hỏi">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <button class="delete-btn" title="Xóa câu hỏi" data-id="{{$cauHoi->ma_cau_hoi }}">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="create-exam">
                    <button class="create-exam-btn" type="button" id="openPopupBtn">Tạo bài kiểm
                        tra ngẫu nhiên</button>
                    <button class="create-exam-btn" type="submit" id="create-exam-btn">Tạo bài kiểm
                        tra</button>
                </div>
            </form>
        </div>
        <div class="main-question-footer">
            <div class="pagination">
                @if ($danhSachCauHoi->onFirstPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-left"></i></a>
                @else
                    <a href="{{ $danhSachCauHoi->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif
                <a href="{{ $danhSachCauHoi->url($danhSachCauHoi->currentPage()) }}" class="active">
                    {{ $danhSachCauHoi->currentPage() }}</a>
                @if ($danhSachCauHoi->onLastPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <a href="{{ $danhSachCauHoi->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @endif
            </div>
        </div>
        <div id="customPopup" class="custom-popup-overlay">
            <div class="custom-popup">
                <h5>Chọn môn học và số lượng câu hỏi</h5>
                <form action="{{ route('create_exam') }}" method="GET">
                    @csrf
                    <div class="form-group">
                        <label for="monHoc">Môn học:</label>
                        <select name="ma_mon_hoc" id="monHoc" class="form-control">
                            @foreach($danhSachMonHoc as $monHoc)
                                <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="soLuong">Số lượng câu hỏi:</label>
                        <input type="number" name="so_luong" id="soLuong" class="form-control" min="1" required>
                    </div>
                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tạo</button>
                        <button type="button" id="closePopupBtn" class="btn btn-secondary ms-2">Huỷ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/question_management_lecturer.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('create-exam-btn').addEventListener('click', function (e) {
                let checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    Swal.fire({ title: 'Vui lòng chọn câu hỏi!' });
                    e.preventDefault();
                    return;
                }

                let selectedIds = Array.from(checkedBoxes).map(cb => cb.dataset.id);
                document.getElementById('ds-cau-hoi-input').value = selectedIds.join(',');

                document.getElementById('create-exam-form').submit();


            });
            //tạo bài kiểm tra ngẫu nhiên
            document.getElementById('openPopupBtn').addEventListener('click', () => {
                document.getElementById('customPopup').style.display = 'block';
                console.log('ầdf');
            });
            document.getElementById('closePopupBtn').addEventListener('click', () => {
                document.getElementById('customPopup').style.display = 'none';
            });

            // Ẩn popup khi bấm ngoài vùng
            window.addEventListener('click', function (e) {
                const popup = document.getElementById('customPopup');
                if (e.target === popup) {
                    popup.style.display = 'none';
                }
            });
        });
    </script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const thumbs = document.querySelectorAll('.thumb-img');

            thumbs.forEach(img => {
                img.addEventListener('click', function () {
                    const src = this.getAttribute('data-src');
                    const popup = window.open('', '_blank');
                    popup.document.write(`<title>Xem ảnh</title><img src="${src}" style="max-width: 100%; margin: auto; display: block;">`);
                });
            });
        });
    </script>

@endsection