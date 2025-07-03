@extends('layout.lecturer_layout')
<style>
    /* Question Management Styles */
    .main-question-header {
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 0;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .add-question-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        margin: 20px 35px;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        text-decoration: none;
    }

    .add-question-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        background: linear-gradient(135deg, #0056b3, #004085);
        color: #fff;
    }

    .add-question-btn:active {
        transform: translateY(0);
    }

    .main-question-body {
        padding: 30px;
        margin: 20px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    /* Table Styles */
    .question-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .question-table thead {
        background: linear-gradient(135deg, rgb(31, 43, 62), rgb(45, 55, 75));
    }

    .question-table tr th {
        padding: 16px 12px;
        text-align: center;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: relative;
    }

    .question-table tr th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .question-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e9ecef;
    }

    .question-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.001);
    }

    .question-table tbody tr:nth-child(odd) {
        background-color: #f8fcff;
    }

    .question-table tbody tr:last-child {
        border-bottom: none;
    }

    .question-table tr td {
        padding: 16px 12px;
        border: none;
        vertical-align: top;
        font-size: 13px;
        line-height: 1.5;
    }

    /* Căn giữa các cột phù hợp */
    .question-table td:first-child,
    .question-table th:first-child {
        text-align: center;
        vertical-align: middle;
        width: 40px;
    }

    .question-table td:nth-child(5),
    .question-table td:nth-child(6),
    .question-table td:nth-child(7),
    .question-table td:nth-child(8),
    .question-table td:nth-child(9),
    .question-table th:nth-child(5),
    .question-table th:nth-child(6),
    .question-table th:nth-child(7),
    .question-table th:nth-child(8),
    .question-table th:nth-child(9) {
        text-align: center;
    }

    /* Nội dung câu hỏi */
    .question-table td:nth-child(2) {
        text-align: left;
    }

    /* Question content styles */
    .question-text {
        margin-bottom: 12px;
        font-weight: 500;
        color: #2c3e50;
        line-height: 1.6;
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

    /* Actions column */
    .actions-cell {
        width: 180px;
        text-align: center;
    }

    .actions-cell button,
    .actions-cell a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        margin: 3px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 70px;
    }

    .actions-cell .btn-edit {
        background-color: #007bff;
        color: white;
    }

    .actions-cell .btn-edit:hover {
        background-color: #0056b3;
    }

    .actions-cell .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .actions-cell .btn-delete:hover {
        background-color: #bd2130;
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        gap: 5px;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 8px 12px;
        margin: 0 2px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        color: #495057;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .pagination a:hover {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-color: #007bff;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .pagination .active,
    .pagination .current-page {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-color: #007bff;
        color: #fff;
        cursor: default;
    }

    .pagination .disabled {
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .pagination .disabled:hover {
        background-color: #fff;
        border-color: #dee2e6;
        color: #6c757d;
        transform: none;
        box-shadow: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-question-body {
            padding: 20px 15px;
            margin: 10px;
        }

        .add-question-btn {
            margin: 15px 20px;
            padding: 10px 20px;
            font-size: 13px;
        }

        .question-table {
            font-size: 12px;
        }

        .question-table tr th,
        .question-table tr td {
            padding: 10px 8px;
        }

        .answers-container {
            max-width: 250px;
        }

        .answer-item {
            font-size: 11px;
            padding: 4px 8px;
        }

        .actions-cell {
            width: 140px;
        }

        .actions-cell button,
        .actions-cell a {
            padding: 6px 10px;
            font-size: 11px;
            min-width: 60px;
        }

        .pagination a,
        .pagination span {
            min-width: 35px;
            height: 35px;
            padding: 6px 10px;
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .question-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .answers-container {
            flex-direction: column;
            align-items: flex-start;
            max-width: 200px;
        }

        .actions-cell {
            width: 120px;
        }

        .actions-cell button,
        .actions-cell a {
            display: block;
            margin: 2px 0;
            width: 100%;
        }
    }

    /* Loading and Empty States */
    .loading-state {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #dee2e6;
    }

    /* Search and Filter Bar */
    .search-filter-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        padding: 10px 15px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .filter-select {
        padding: 10px 15px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        background-color: #fff;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #007bff;
    }
</style>
@section('content')
    <div class="question-content">
        <div class="main-question">
            <div class="main-question-body">
                <table class="question-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Nội dung Câu hỏi</th>
                            <th>Độ khó</th>
                            <th>Hình ảnh</th>
                            <th>Môn</th>
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
                                        <input type="checkbox" class="question-checkbox" data-id="{{ $cauHoi->ma_cau_hoi }}"
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
                                    <td data-label="Thao tác" class="actions-cell">
                                        <a href="" class="btn-restore" title="khôi phục câu hỏi" data-id="{{$cauHoi->ma_cau_hoi }}">
                                            <i class="fas fa-restore"></i> Khôi phục
                                        </a>
                                        <form id="delete-form-{{ $cauHoi->ma_cau_hoi }}"
                                            action="{{ route('question_del_id', $cauHoi->ma_cau_hoi) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-confirm" data-id="{{ $cauHoi->ma_cau_hoi }}">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
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
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/question_management_lecturer.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete-confirm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // 🚫 Ngăn form submit ngay lập tức

                    const questionId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc không?',
                        text: "Thao tác này sẽ xoá vĩnh viễn câu hỏi!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + questionId).submit();
                        }
                    });
                });
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