@extends('layout.lecturer_layout')
@section('title')
    Quản lý Chương
@endsection
<style>
    .chapter-management-content {
        padding: 20px;
        margin: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .chapter-management-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .chapter-management-header h2 {
        margin: 0;
        color: #343a40;
        font-weight: 600;
    }

    .form-search-chapter {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .add-chapter-btn {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .add-chapter-btn:hover {
        background-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }

    .add-chapter-btn i {
        margin-right: 8px;
    }

    .search-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .search-section input {
        min-width: 300px;
    }

    .chapter-management-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .chapter-management-table thead {
        background-color: #343a40;
        color: #fff;
    }

    .chapter-management-table th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 15px;
        white-space: nowrap;
    }

    .chapter-management-table th:first-child {
        border-top-left-radius: 8px;
        width: 80px;
        text-align: center;
    }

    .chapter-management-table th:last-child {
        border-top-right-radius: 8px;
        width: 200px;
        text-align: center;
    }

    .chapter-management-table tbody tr {
        background-color: #fff;
        transition: background-color 0.2s ease;
    }

    .chapter-management-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .chapter-management-table tbody tr:hover {
        background-color: #e2e6ea;
    }

    .chapter-management-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .chapter-management-table td:last-child {
        border-right: none;
    }

    .chapter-management-table tbody tr:last-child td {
        border-bottom: none;
    }

    .chapter-number-cell {
        text-align: center;
        font-weight: 600;
        color: #495057;
    }

    .subject-name-cell {
        font-weight: 500;
        color: #0056b3;
    }

    .chapter-name-cell {
        font-weight: 500;
        color: #343a40;
    }

    .actions-cell {
        text-align: center;
        white-space: nowrap;
    }

    .actions-cell button {
        padding: 6px 12px;
        margin: 0 3px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        min-width: 70px;
    }

    .actions-cell button.edit-btn {
        background-color: #007bff;
        color: white;
    }

    .actions-cell button.edit-btn:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .actions-cell button.delete-btn {
        background-color: #dc3545;
        color: white;
    }

    .actions-cell button.delete-btn:hover {
        background-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .actions-cell button i {
        margin-right: 4px;
    }

    .chapter-management-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-chapters {
        color: #6c757d;
        font-size: 14px;
    }

    .no-data-message {
        text-align: center;
        padding: 40px;
        color: #6c757d;
        font-style: italic;
    }

    .modal-dialog {
        max-width: 600px;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    .modal-backdrop.show {
    background-color: rgba(0, 0, 0, 0.5); /* Mặc định là 0.5, giảm xuống để overlay nhẹ hơn */
}

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
    }

    .modal-title {
        font-weight: 600;
        color: #343a40;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
        font-size: 14px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-select {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
        font-size: 14px;
    }

    .btn {
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #545b62;
        border-color: #545b62;
    }

    .pagination {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 20px;
        padding-bottom: 20px;
    }

    .pagination a,
    .pagination span {
        color: #007bff;
        margin: 0 5px;
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
        border: 1px solid #dee2e6;
    }

    .pagination a:hover {
        background-color: #007bff;
        color: white;
        box-shadow: none;
    }

    .pagination .active span {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
        font-weight: bold;
    }
</style>
@section('content')
    <div class="chapter-management-content">
        <div class="chapter-management-header">
            <h2>Quản lý Chương</h2>
        </div>

        @if($danhSachMonHoc->count() > 0)
            <form action="{{ route('chapter_management') }}" method="GET" id="form-search-chapter" class="form-search-chapter">
                <div class="search-section">
                    <div class="mb-2">
                        <select class="form-select" name="mon_hoc_id" id="monHocSelect">
                            <option value="">Tất cả môn học</option>
                            @foreach($danhSachMonHoc as $monHoc)
                                <option value="{{ $monHoc->ma_mon_hoc }}" {{ request('mon_hoc_id') == $monHoc->ma_mon_hoc ? 'selected' : '' }}>
                                    {{ $monHoc->ten_mon_hoc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" id="keyword" name="tu_khoa_tim_kiem"
                            placeholder="Tìm theo tên chương" value="{{ request('tu_khoa_tim_kiem') }}">
                    </div>
                </div>
                <button type="button" class="add-chapter-btn btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#addChapterModal">
                    <i class="fa-solid fa-plus"></i> Thêm chương mới
                </button>
            </form>

            {{-- Hiển thị bảng chương --}}
            <div class="chapter-management-body mt-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Môn học</th>
                            <th>Tên chương</th>
                            <th>Số chương</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($danhSachChuong as $index => $chuong)
                            <tr>
                                <td>{{ $loop->iteration + ($danhSachChuong->firstItem() - 1) }}</td>
                                <td>{{ $chuong->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                                <td>{{ $chuong->ten_chuong }}</td>
                                <td>{{ $chuong->so_thu_tu }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editChapterModal"
                                        onclick="editChapter('{{ $chuong->ma_chuong }}', '{{ $chuong->ten_chuong }}', '{{ $chuong->ma_mon_hoc }}', '{{ $chuong->so_thu_tu }}')">
                                        <i class="fa fa-edit"></i> Sửa
                                    </button>
                                    <form id="delete-form-{{ $chuong->ma_chuong }}"
                                        action="{{ route('chapter_management_del', ['id' => $chuong->ma_chuong]) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $chuong->ma_chuong }}')">
                                            <i class="fa fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Không có chương nào được tìm thấy</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="chapter-manage-footer mt-3">
                {{ $danhSachChuong->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info mt-4 text-center">
                Bạn chưa được phân môn học nào nên không có chương để hiển thị.
            </div>
        @endif
    </div>

    <!-- Modal Thêm chương -->
    <div class="modal fade" id="addChapterModal" tabindex="-1" aria-labelledby="addChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('chapter_management_store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm chương mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="ma_mon_hoc_add" class="form-label">Môn học</label>
                            <select name="ma_mon_hoc" id="ma_mon_hoc_add" class="form-select" required>
                                <option disabled selected>-- Chọn môn học --</option>
                                @foreach ($danhSachMonHoc as $monHoc )
                                   <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ten_chuong_add" class="form-label">Tên chương</label>
                            <input type="text" name="ten_chuong" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="so_thu_tu_add" class="form-label">Số chương</label>
                            <input type="number" name="so_thu_tu" class="form-control" min="1" max="20" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sửa chương -->
    <div class="modal fade" id="editChapterModal" tabindex="-1" aria-labelledby="editChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('chapter_management_update') }}" method="POST">
                @csrf
                <input type="hidden" name="ma_chuong" id="ma_chuong_edit">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa chương</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên chương</label>
                            <input type="text" name="ten_chuong" id="ten_chuong_edit" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Môn học</label>
                            <select name="ma_mon_hoc" id="ma_mon_hoc_edit" class="form-select" required>
                                @foreach($danhSachMonHoc as $monHoc)
                                    <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số thứ tự</label>
                            <input type="number" name="so_thu_tu" id="so_thu_tu_edit" class="form-control" min="1" max="20"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Bootstrap & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function editChapter(maChuong, tenChuong, maMonHoc, soChuong) {
            document.getElementById('ma_chuong_edit').value = maChuong;
            document.getElementById('ten_chuong_edit').value = tenChuong;
            document.getElementById('ma_mon_hoc_edit').value = maMonHoc;
            document.getElementById('so_thu_tu_edit').value = soChuong;
        }

        function confirmDelete(maChuong) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Chương này sẽ bị xóa vĩnh viễn!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${maChuong}`).submit();
                }
            });
        }

        

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: @json(session('success')),
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Thất bại!',
                    text: @json(session('error')),
                    confirmButtonText: 'Đóng'
                });
            @endif
    </script>
@endsection