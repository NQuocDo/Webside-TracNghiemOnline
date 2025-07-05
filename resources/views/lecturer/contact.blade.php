@extends('layout.lecturer_layout')
<style>
    .contact-content {
        padding: 30px;
        margin: 20px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .contact-header {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
        justify-content: flex-start;
        flex-wrap: nowrap;
        justify-content: end;
    }

    .contact-header .search-container {
        position: relative;
        flex: 1;
        max-width: 300px;
        min-width: 200px;
    }

    .contact-header .filter-container select {
        padding: 10px;
        font-size: 14px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        min-width: 180px;
    }

    .contact-header input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        outline: none;
    }

    .contact-header input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .contact-header i {
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

    .contact-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .contact-table thead {
        background: linear-gradient(135deg, rgb(31, 43, 62), rgb(45, 55, 75));
    }

    .contact-table tr th {
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

    .contact-table tr th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .contact-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e9ecef;
    }

    .contact-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.001);
    }

    .contact-table tbody tr:nth-child(odd) {
        background-color: #f8fcff;
    }

    .contact-table tbody tr:last-child {
        border-bottom: none;
    }

    .contact-table tr td {
        padding: 16px 12px;
        border: none;
        vertical-align: middle;
        font-size: 13px;
        line-height: 1.5;
    }


    .contact-table td:first-child,
    .contact-table th:first-child {
        text-align: center;
        width: 40px;
    }

    .contact-table td:nth-child(2),
    .contact-table td:nth-child(3),
    .contact-table td:nth-child(4) {
        text-align: left;
    }

    .contact-table td:nth-child(5),
    .contact-table td:nth-child(6),
    .contact-table th:nth-child(5),
    .contact-table th:nth-child(6) {
        text-align: center;
    }

    .contact-info {
        font-weight: 500;
        color: #2c3e50;
        line-height: 1.6;
    }

    .contact-details {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
        font-size: 13px;
        color: #495057;
    }

    .contact-details i {
        font-size: 14px;
        color: #6c757d;
        width: 16px;
    }

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

    .actions-cell .btn-view {
        background-color: #28a745;
        color: white;
    }

    .actions-cell .btn-view:hover {
        background-color: #1e7e34;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .actions-cell .btn-edit {
        background-color: #007bff;
        color: white;
    }

    .actions-cell .btn-edit:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }

    .actions-cell .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .actions-cell .btn-delete:hover {
        background-color: #bd2130;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .contact-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
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

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .contact-content {
            padding: 20px 15px;
            margin: 10px;
        }

        .add-contact-btn {
            margin: 15px 20px;
            padding: 10px 20px;
            font-size: 13px;
        }

        .contact-header {
            flex-direction: column;
            align-items: stretch;
        }

        .contact-header .search-container {
            max-width: none;
        }

        .contact-table {
            font-size: 12px;
        }

        .contact-table tr th,
        .contact-table tr td {
            padding: 10px 8px;
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

        .pagination-btn,
        .page-number {
            min-width: 35px;
            height: 35px;
            padding: 6px 10px;
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .contact-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
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

        .page-numbers {
            gap: 3px;
            margin: 0 5px;
        }

        .pagination-btn,
        .page-number {
            min-width: 30px;
            height: 30px;
            padding: 4px 8px;
            font-size: 12px;
        }
    }
</style>
@section('content')
    <div class="contact-content">
        <form id="filter-form" method="GET" action="{{ route('lecturer_contact') }}" class="contact-header">
            <div class="search-container">
                <input type="text" id="keyword" name="keyword" placeholder="Tìm kiếm liên hệ"
                    value="{{ old('keyword', $keyword ?? '') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>

            <div class="filter-container">
                <select name="trang_thai" id="trangThaiSelect">
                    <option value="hien" {{ ($trangThai ?? '') === 'hien' ? 'selected' : '' }}>Hiện</option>
                    <option value="an" {{ ($trangThai ?? '') === 'an' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
        </form>
        <table class="contact-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sinh viên</th>
                    <th>Gmail</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    @if(($trangThai ?? '') != 'an')
                        <th>Thao tác</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($danhSachLienHe as $index => $lienHe)
                    {{-- Chỉ hiển thị nếu là "hien" hoặc nếu lọc đang chọn là "an" --}}
                    @if($lienHe->trang_thai == 'hien' || ($trangThai ?? '') == 'an')
                        <tr>
                            <td class="stt-cell">{{ $index + 1 }}</td>
                            <td class="student-name-cell">{{ $lienHe->sinhVien->nguoiDung->ho_ten }}</td>
                            <td class="student-mail-cell">{{ $lienHe->sinhVien->nguoiDung->email }}</td>
                            <td class="student-title-cell">{{ $lienHe->tieu_de }}</td>
                            <td class="student-error-content-cell">{{ $lienHe->noi_dung }}</td>

                            @if(($trangThai ?? '') != 'an' && $lienHe->trang_thai == 'hien')
                                <td class="actions-cell">
                                    <form id="delete-form-{{ $lienHe->ma_lien_he }}"
                                        action="{{ route('lecturer_contact_del', $lienHe->ma_lien_he) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="del-btn btn-delete-confirm" data-id="{{ $lienHe->ma_lien_he }}">
                                            <i class="fas fa-trash-alt"></i> Xoá
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có liên hệ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="contact-footer">
            <div class="pagination">
                @if ($danhSachLienHe->onFirstPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-left"></i></a>
                @else
                    <a href="{{ $danhSachLienHe->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif
                <a href="{{ $danhSachLienHe->url($danhSachLienHe->currentPage()) }}" class="active">
                    {{ $danhSachLienHe->currentPage() }}</a>
                @if ($danhSachLienHe->onLastPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <a href="{{ $danhSachLienHe->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete-confirm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    const lienHeId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc muốn xoá liên hệ này?',
                        text: "Thao tác này không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + lienHeId).submit();
                        }
                    });
                });
            });
            const form = document.getElementById("filter-form");
            const keywordInput = document.getElementById("keyword");
            const trangThaiSelect = document.getElementById("trangThaiSelect");

            if (trangThaiSelect) {
                trangThaiSelect.addEventListener("change", function () {
                    form.submit();
                });
            }

            if (keywordInput) {
                let debounce;
                keywordInput.addEventListener("input", function () {
                    clearTimeout(debounce);
                    debounce = setTimeout(() => {
                        form.submit();
                    }, 500);
                });
            }
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: @json(session('success'))
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: @json(session('error'))
            });
        </script>
    @endif
@endsection