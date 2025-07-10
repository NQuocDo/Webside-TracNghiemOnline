@extends('layout.lecturer_layout')
@section('title')
    Trang Danh sách thông báo
@endsection
<style>
    /* Announcement Management Styles - Matching Contact Management Style */

    /* Main Header Section */
    .main-announce-header {
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 0;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .add-announce-btn {
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

    .add-announce-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        background: linear-gradient(135deg, #0056b3, #004085);
        color: #fff;
    }

    .add-announce-btn:active {
        transform: translateY(0);
    }

    /* Main Body Container */
    .announce-content {
        padding: 30px;
        margin: 20px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .main-announce-body {
        padding: 0;
        margin: 0;
    }

    /* Search and Filter Bar */
    .announce-header {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
    }

    .announce-header .search-container {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 300px;
    }

    .announce-header input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        outline: none;
    }

    .announce-header input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .announce-header i {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
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

    /* Table Styles */
    .announce-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .announce-table thead {
        background: linear-gradient(135deg, rgb(31, 43, 62), rgb(45, 55, 75));
    }

    .announce-table tr th {
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

    .announce-table tr th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .announce-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e9ecef;
    }

    .announce-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.001);
    }

    .announce-table tbody tr:nth-child(odd) {
        background-color: #f8fcff;
    }

    .announce-table tbody tr:last-child {
        border-bottom: none;
    }

    .announce-table tr td {
        padding: 16px 12px;
        border: none;
        vertical-align: middle;
        font-size: 13px;
        line-height: 1.5;
    }

    /* Column alignment */
    .announce-table td:first-child,
    .announce-table th:first-child {
        text-align: center;
        width: 40px;
    }

    .announce-table td:nth-child(2),
    .announce-table td:nth-child(3),
    .announce-table td:nth-child(4) {
        text-align: left;
    }

    .announce-table td:nth-child(5),
    .announce-table td:nth-child(6),
    .announce-table th:nth-child(5),
    .announce-table th:nth-child(6) {
        text-align: center;
    }

    /* Announcement Content Styles */
    .announce-list-title {
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.6;
    }

    .announce-list-content {
        color: #495057;
        line-height: 1.5;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .announce-class {
        font-weight: 500;
        color: #007bff;
        background-color: #e7f3ff;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        display: inline-block;
    }

    .announce-time {
        color: #6c757d;
        font-size: 12px;
    }

    /* Actions Column */
    .actions-cell {
        width: 120px;
        text-align: center;
    }

    .actions-cell button,
    .actions-cell a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 8px 12px;
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

    .actions-cell .btn-delete-confirm {
        background-color: #dc3545;
        color: white;
    }

    .actions-cell .btn-delete-confirm:hover {
        background-color: #bd2130;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    /* Empty State */
    .text-center.text-muted {
        padding: 60px 20px;
        color: #6c757d;
        font-style: italic;
        font-size: 14px;
    }

    /* Pagination Styles */
    .main-question-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        padding: 20px 0;
        border-top: 1px solid #e9ecef;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }

    .pagination a {
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
        cursor: pointer;
    }

    .pagination a:hover:not(.disabled) {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-color: #007bff;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .pagination a.disabled {
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .pagination a.disabled:hover {
        background-color: #fff;
        border-color: #dee2e6;
        color: #6c757d;
        transform: none;
        box-shadow: none;
    }

    .pagination a.active {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-color: #007bff;
        color: #fff;
        cursor: default;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .announce-content {
            padding: 20px 15px;
            margin: 10px;
        }

        .add-announce-btn {
            margin: 15px 20px;
            padding: 10px 20px;
            font-size: 13px;
        }

        .announce-header {
            flex-direction: column;
            align-items: stretch;
        }

        .announce-header .search-container {
            max-width: none;
        }

        .announce-table {
            font-size: 12px;
        }

        .announce-table tr th,
        .announce-table tr td {
            padding: 10px 8px;
        }

        .announce-list-content {
            max-width: 200px;
        }

        .actions-cell {
            width: 100px;
        }

        .actions-cell button,
        .actions-cell a {
            padding: 6px 8px;
            font-size: 11px;
            min-width: 60px;
        }

        .pagination a {
            min-width: 35px;
            height: 35px;
            padding: 6px 10px;
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .announce-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .announce-list-content {
            max-width: 150px;
        }

        .actions-cell {
            width: 80px;
        }

        .actions-cell button,
        .actions-cell a {
            padding: 4px 6px;
            font-size: 10px;
            min-width: 50px;
        }

        .pagination a {
            min-width: 30px;
            height: 30px;
            padding: 4px 8px;
            font-size: 12px;
        }
    }

    /* Animation for table rows */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .announce-table tbody tr {
        animation: fadeInUp 0.5s ease forwards;
    }

    .announce-table tbody tr:nth-child(1) {
        animation-delay: 0.1s;
    }

    .announce-table tbody tr:nth-child(2) {
        animation-delay: 0.2s;
    }

    .announce-table tbody tr:nth-child(3) {
        animation-delay: 0.3s;
    }

    .announce-table tbody tr:nth-child(4) {
        animation-delay: 0.4s;
    }

    .announce-table tbody tr:nth-child(5) {
        animation-delay: 0.5s;
    }
</style>

@section('content')
    <div class="announce-content">
        <div class="main-question">
            <div class="main-announce-body">
                <table class="announce-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tiêu đề</th>
                            <th>Nội dung</th>
                            <th>Lớp</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($danhSachThongBao->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-bullhorn"
                                        style="font-size: 48px; margin-bottom: 15px; color: #dee2e6; display: block;"></i>
                                    Bạn chưa tạo thông báo nào
                                </td>
                            </tr>
                        @else
                            @foreach($danhSachThongBao as $index => $thongBao)
                                <tr>
                                    <td class="stt-cell">{{ $index + 1 }}</td>
                                    <td class="announce-list-title">{{ $thongBao->tieu_de }}</td>
                                    <td class="announce-list-content" title="{{ $thongBao->noi_dung }}">{{ $thongBao->noi_dung }}
                                    </td>
                                    <td class="announce-class">{{ optional($thongBao->lopHoc)->ten_lop_hoc }}</td>
                                    <td class="announce-time">{{ $thongBao->ngay_gui }}</td>
                                    <td class="actions-cell">
                                        <form id="delete-form-{{ $thongBao->ma_thong_bao }}"
                                            action="{{ route('announce_list_del', $thongBao->ma_thong_bao) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-confirm"
                                                data-id="{{ $thongBao->ma_thong_bao }}">
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
                    @if ($danhSachThongBao->onFirstPage())
                        <a href="#" class="disabled"><i class="fa-solid fa-chevron-left"></i></a>
                    @else
                        <a href="{{ $danhSachThongBao->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    <a href="{{ $danhSachThongBao->url($danhSachThongBao->currentPage()) }}" class="active">
                        {{ $danhSachThongBao->currentPage() }}
                    </a>

                    @if ($danhSachThongBao->onLastPage())
                        <a href="#" class="disabled"><i class="fa-solid fa-chevron-right"></i></a>
                    @else
                        <a href="{{ $danhSachThongBao->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
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
                    event.preventDefault();

                    const questionId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc không?',
                        text: "Thao tác này sẽ xóa vĩnh viễn thông báo!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy'
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
@endsection