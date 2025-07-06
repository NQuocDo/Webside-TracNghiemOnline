@extends('layout.dean')
@section('title')
    Danh sách Giảng viên
@endsection
<style>
    .lecturer-list-content {
        padding: 20px;
        margin: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .form-search-student {
        width: 100%;
        display: flex;
        align-items: baseline;
        justify-content: flex-end;
        margin-bottom: 50px;
    }


    .lecturer-list-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .lecturer-list-table thead {
        background-color: #343a40;
        color: #fff;
    }

    .lecturer-list-table th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 15px;
        white-space: nowrap;
    }

    .lecturer-list-table th:first-child {
        border-top-left-radius: 8px;
    }

    .lecturer-list-table th:last-child {
        border-top-right-radius: 8px;
    }

    .lecturer-list-table tbody tr {
        background-color: #fff;
        transition: background-color 0.2s ease;
    }

    .lecturer-list-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .lecturer-list-table tbody tr:hover {
        background-color: #e2e6ea;
    }

    .lecturer-list-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .lecturer-list-table td:last-child {
        border-right: none;
    }

    .lecturer-list-table tbody tr:last-child td {
        border-bottom: none;
    }

    .lecturer-subject-cell,
    .lecturer-class-cell {
        text-align: left;
    }

    .lecturer-subject-cell ul,
    .lecturer-class-cell ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .lecturer-subject-cell li,
    .lecturer-class-cell li {
        margin-bottom: 2px;
    }

    .actions-cell {
        text-align: center;
        width: 150px;
        white-space: nowrap;
    }

    .actions-cell button {
        padding: 8px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #007bff;
        color: white;
    }

    .actions-cell button.delete-btn {
        background-color: #dc3545;
    }

    .actions-cell button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .actions-cell button.delete-btn:hover {
        background-color: #c82333;
    }

    .actions-cell button i {
        margin-right: 5px;
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
    <div class="lecturer-list-content">
        <form action="{{ route('lecturer_list') }}" method="GET" id="form-search-lecturer" class="form-search-student"
            style="justify-content: end;">
            <div class="mb-2 me-3">
                <input type="text" class="form-control" id="keyword" name="tu_khoa_tim_kiem"
                    placeholder="Tìm theo tên giảng viên" value="{{ $tuKhoaTimKiem }}">
            </div>
        </form>
        <div class="lecturer-list-body">
            <table class="lecturer-list-table">
                <thead>
                    <tr>
                        <th>Mã giảng viên</th>
                        <th>Tên giảng viên</th>
                        <th>Giới tính</th>
                        <th>Môn đang giảng dạy</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if($danhSachGiangVien->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có giảng viên</td>
                        </tr>
                    @else
                        @foreach($danhSachGiangVien as $index => $giangVien)
                            <tr>
                                <td class="lecture-code-cell">{{ $index + 1 }}</td>
                                <td class="lecturer-name-cell">{{ $giangVien->nguoiDung->ho_ten ?? '' }}</td>
                                <td class="lecturer-sex-cell">{{ $giangVien->nguoiDung->gioi_tinh ?? '' }}</td>
                                <td class="lecturer-subject-cell">
                                    <ul>
                                        @forelse($giangVien->monHocs as $monHoc) <li>{{ $monHoc->ten_mon_hoc }}</li>
                                        @empty
                                            <li>Chưa phân công</li>
                                        @endforelse
                                    </ul>
                                </td>
                                <td class="actions-cell">
                                    <button class="block-btn" data-id="{{ $giangVien->nguoiDung->ma_nguoi_dung }}"
                                        data-status="{{ $giangVien->nguoiDung->trang_thai_tai_khoan }}">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                        {{ $giangVien->nguoiDung->trang_thai_tai_khoan === 'hoat_dong' ? 'Khoá' : 'Mở' }}
                                    </button>
                                    <button class="changepassword-btn" data-bs-toggle="modal" data-bs-target="#doiMatKhauModal"
                                        onclick="setGiangVienId('{{ $giangVien->ma_giang_vien }}')">
                                        <i class="fa-solid fa-eye"></i> Đổi mật khẩu
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="lecturer-list-footer">
            <div class="pagination">
                @if ($danhSachGiangVien->onFirstPage())
                    <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $danhSachGiangVien->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @if ($danhSachGiangVien->currentPage() > 1)
                    <a href="{{ $danhSachGiangVien->url($danhSachGiangVien->currentPage() - 1) }}">
                        {{ $danhSachGiangVien->currentPage() - 1 }}
                    </a>
                @endif

                <a href="#" class="active">{{ $danhSachGiangVien->currentPage() }}</a>

                @if ($danhSachGiangVien->currentPage() < $danhSachGiangVien->lastPage())
                    <a href="{{ $danhSachGiangVien->url($danhSachGiangVien->currentPage() + 1) }}">
                        {{ $danhSachGiangVien->currentPage() + 1 }}
                    </a>
                @endif

                @if ($danhSachGiangVien->hasMorePages())
                    <a href="{{ $danhSachGiangVien->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="doiMatKhauModal" tabindex="-1" aria-labelledby="doiMatKhauModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('lecturer_list_changepassword') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="doiMatKhauModalLabel">Đổi mật khẩu giảng viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="ma_giang_vien" id="maGiangVienInput">
                        <div class="mb-3">
                            <label for="mat_khau_moi" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="mat_khau_moi" required>
                        </div>
                        <div class="mb-3">
                            <label for="xac_nhan_mat_khau" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" name="xac_nhan_mat_khau" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function setGiangVienId(maGV) {
            document.getElementById('maGiangVienInput').value = maGV;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Đổi trạng thái tài khoản giảng viên
            const buttons = document.querySelectorAll('.block-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: "Thao tác này sẽ thay đổi trạng thái tài khoản!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/dean/lecturer-list/${userId}/status`, {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Thực hiện thành công!',
                                            text: data.message,
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                        setTimeout(() => location.reload(), 2000);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Lỗi',
                                            text: data.message
                                        });
                                    }
                                })
                                .catch(() => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi kết nối',
                                        text: 'Không thể gửi yêu cầu đến máy chủ.'
                                    });
                                });
                        }
                    });
                });
            });

            // Tìm kiếm tự động
            const form = document.getElementById("form-search-lecturer");
            const keywordInput = document.getElementById("keyword");

            if (keywordInput) {
                let debounce;
                keywordInput.addEventListener("input", function () {
                    clearTimeout(debounce);
                    debounce = setTimeout(() => {
                        form.submit();
                    }, 500);
                });
            }

            // Hiển thị thông báo từ session (success/error)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: @json(session('success')),
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Thất bại!',
                    text: @json(session('error')),
                    confirmButtonText: 'Đóng'
                });
            @endif
                                    });
    </script>
@endsection