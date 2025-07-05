@extends('layout.lecturer_layout')
@section('title')
    Trang Cộng đồng
@endsection
<style>
    .global-content {
        padding: 20px;
        background-color: #f8f9fa;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

    .form-search-question {
        display: flex;
        align-items: center;
        gap: 15px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
        justify-content: flex-start;
        width: auto;
        padding-right: 20px;
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

    .main-question-body {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }

    .global-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border: 1px solid #dee2e6;
    }

    .global-table thead {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .global-table thead th {
        padding: 16px 12px;
        text-align: center;
        color: white;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        white-space: nowrap;
        border: none;
    }

    .global-table thead th:last-child {
        border-right: none;
    }

    .global-table tbody tr {
        border-radius: 12px;
        background-color: #eaf4ff;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .global-table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .global-table tbody tr:hover {
        background-color: #e3f2fd;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .global-table tbody td {
        padding: 16px 12px;
        vertical-align: top;
        border-right: 1px solid #e9ecef;
        font-size: 14px;
        border: none;
        border-bottom: 1px solid #e9ecef;
    }

    .global-table tbody td:last-child {
        border-right: none;
    }

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
        flex-wrap: nowrap;
    }

    .answers-container:hover {
        background-color: #e9ecef;
    }

    .answer-item {
        flex: 1;
        font-size: 13px;
        color: #495057;
        margin: 0;
        background-color: transparent;
        padding: 0;
        border-radius: 0;
        border: none;
        white-space: normal;
        g */
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

    .global-table tbody td:nth-child(3) {
        text-align: center;
        width: 100px;
    }

    .global-table tbody td:nth-child(4) {
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

    .subject-cell {
        text-align: center;
        font-weight: 500;
        color: #495057;
        width: 120px;
    }

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
        margin-top: 30px;
        width: auto;
        justify-content: center;
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
        margin: 0 0;
        padding: 0;
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

    .text-center.text-muted {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        padding: 40px 20px;
        background-color: #f8f9fa;
    }

    @media (max-width: 1200px) {
        .global-table {
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

        .global-table {
            font-size: 12px;
        }

        .global-table thead th,
        .global-table tbody td {
            padding: 10px 8px;
        }
    }

    @media (max-width: 768px) {
        .global-content {
            padding: 15px;
        }

        .global-table thead {
            display: none;
        }

        .global-table tbody tr {
            display: block;
            margin-bottom: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .global-table tbody td {
            display: block;
            border: none;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 15px;
            position: relative;
            padding-left: 120px;
        }

        .global-table tbody td:before {
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
    <div class="global-content">
        <form action="{{ route('question_global') }}" method="GET" id="filter-form" class="form-search-question"
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
        <table class="global-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Nội dung Câu hỏi</th>
                    <th>Độ khó</th>
                    <th>Hình ảnh</th>
                    <th>Môn học</th>
                    <th>Tên giảng viên</th>

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
                            <td data-label="Độ khó">{{ $cauHoi->do_kho }}</td>
                            <td data-label="Hình ảnh" style="text-align: center">
                                @if($cauHoi->hinh_anh)
                                    <img src="{{ asset('images/' . $cauHoi->hinh_anh) }}" width="70px" height="70px" alt="Ảnh nhỏ"
                                        class="thumb-img" data-src="{{ asset('images/' . $cauHoi->hinh_anh) }}"
                                        data-id="{{ $cauHoi->ma_cau_hoi }}">
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td data-label="Môn học">
                                {{ $cauHoi->monHoc->ten_mon_hoc ?? 'Chưa xác định' }}
                            </td>
                            <td data-label="Giảng viên">
                                {{ $cauHoi->giangVien->ten_giang_vien ?? 'Chưa rõ' }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="global-footer">
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
@endsection
@section('scripts')
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