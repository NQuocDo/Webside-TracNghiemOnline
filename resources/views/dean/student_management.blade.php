@extends('layout.dean')
@section('title')
    Quản lý sinh viên
@endsection
<style>
    .student-manage-content {
        padding: 20px;
        margin: 20px;
    }

    .form-search-student {
        width: 100%;
        display: flex;
        align-items: baseline;
        justify-content: flex-end;
        margin-bottom: 50px;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background: white;
        margin: 20px 0;
    }

    .student-manage-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: white;
        border-radius: 12px;
        overflow: hidden;
    }

    .student-manage-table thead {
        background: linear-gradient(135deg, #1f2b3e 0%, #2c3e50 100%);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .student-manage-table thead th {
        padding: 16px 12px;
        text-align: center;
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .student-manage-table thead th:last-child {
        border-right: none;
    }

    .student-manage-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e9ecef;
    }

    .student-manage-table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .student-manage-table tbody tr:hover {
        background-color: #e3f2fd;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .student-manage-table tbody td {
        padding: 14px 12px;
        border: none;
        vertical-align: middle;
        font-size: 14px;
        color: #495057;
    }

    .stt-cell {
        text-align: center;
        font-weight: 600;
        color: #6c757d;
        width: 60px;
    }

    .student-name-cell {
        font-weight: 500;
        color: #2c3e50;
        min-width: 180px;
    }

    .student-sex-cell {
        text-align: center;
        width: 100px;
    }

    .student-class-cell {
        min-width: 120px;
        font-style: italic;
    }

    .actions-cell {
        width: 200px;
        text-align: center;
        padding: 12px 8px;
    }

    .actions-cell button {
        padding: 8px 16px;
        margin: 0 4px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-width: 70px;
        justify-content: center;
    }

    .btn-toggle-status {
        background: linear-gradient(135deg, #fd7e14 0%, #fd9644 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(253, 126, 20, 0.3);
    }

    .btn-toggle-status:hover {
        background: linear-gradient(135deg, #e8590c 0%, #fd7e14 100%);
        box-shadow: 0 4px 8px rgba(253, 126, 20, 0.4);
        transform: translateY(-2px);
    }

    .delete-btn {
        background: linear-gradient(135deg, #dc3545 0%, #e85d75 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }

    .delete-btn:hover {
        background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        transform: translateY(-2px);
    }

    .actions-cell button i {
        font-size: 12px;
    }

    .text-center.text-muted {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        padding: 40px 20px;
        background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
        .student-manage-table {
            font-size: 12px;
        }

        .student-manage-table thead th,
        .student-manage-table tbody td {
            padding: 10px 8px;
        }

        .actions-cell {
            width: 150px;
        }

        .actions-cell button {
            padding: 6px 12px;
            font-size: 11px;
            margin: 2px;
        }

        .student-name-cell {
            min-width: 120px;
        }
    }

    @media (max-width: 576px) {
        .actions-cell button {
            display: block;
            width: 100%;
            margin: 2px 0;
        }

        .actions-cell {
            width: 100px;
        }
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
    <div class="student-manage-content">
        <form action="{{ route('student_management') }}" method="GET" id="form-search-student-list"
            class="form-search-student" style="justify-content: end;">
            <div class="mb-2 me-3">
                <input type="text" class="form-control" id="keyword" name="tu_khoa_tim_kiem"
                    placeholder="Tìm theo tên sinh viên" value="{{ $tuKhoaTimKiem }}">
            </div>
        </form>
        <div class="student-manage-body">
            <table class="student-manage-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sinh viên</th>
                        <th>Mã số sinh viên</th>
                        <th>Email</th>
                        <th>Giới tính</th>
                        <th>Lớp</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if($danhSachSinhVien->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có sinh viên</td>
                        </tr>
                    @else
                        @foreach($danhSachSinhVien as $index => $sinhVien)
                            @php
                                $nguoiDung = $sinhVien->nguoiDung;
                            @endphp
                            <tr>
                                <td class="stt-cell" style="text-align:center">
                                    {{ ($danhSachSinhVien->currentPage() - 1) * $danhSachSinhVien->perPage() + $index + 1 }}
                                </td>

                                <td class="student-name-cell">
                                    {{ $nguoiDung->ho_ten ?? 'Không rõ' }}
                                </td>

                                <td class="student-score-cell">
                                    {{ $sinhVien->mssv ?? 'Không rõ' }}
                                </td>
                                <td class="student-email-cell">
                                    {{ $nguoiDung->email ?? 'Không rõ' }}
                                </td>

                                <td class="student-sex-cell">
                                    {{ $nguoiDung->gioi_tinh ?? 'Không rõ' }}
                                </td>

                                <td class="student-class-cell">
                                    {{ $sinhVien->lopHoc->ten_lop_hoc ?? 'Chưa có lớp' }}
                                </td>

                                <td class="actions-cell">
                                    @if(isset($sinhVien->nguoiDung))
                                        <button type="button" class="btn btn-sm btn-toggle-status"style="color:white;"
                                            data-id="{{ $sinhVien->nguoiDung->ma_nguoi_dung }}"
                                            data-status="{{ $sinhVien->nguoiDung->trang_thai_tai_khoan }}">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>
                                            <span class="status-label">
                                                {{ $sinhVien->nguoiDung->trang_thai_tai_khoan === 'hoat_dong' ? 'Khoá' : 'Mở' }}
                                            </span>
                                        </button>
                                    @else
                                        <span class="text-muted">Không rõ trạng thái</span>
                                    @endif
                                    <form id="delete-form-{{ $sinhVien->ma_sinh_vien }}"
                                        action="{{ route('student_management_delete', $sinhVien->ma_sinh_vien) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn" data-id="{{ $sinhVien->ma_sinh_vien }}">
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
        <div class="student-manage-footer">
            <div class="pagination">
                <a href="{{$danhSachSinhVien->previousPageUrl()}}"><i class="fa-solid fa-chevron-left"></i></a>
                @if($danhSachSinhVien->currentPage() - 1 != 0) <a
                href="{{$danhSachSinhVien->previousPageUrl()}}">{{$danhSachSinhVien->currentPage() - 1}}</i></a> @endif
                <a href="{{$danhSachSinhVien->currentPage()}}" class="active"> {{$danhSachSinhVien->currentPage()}}</a>
                @if($danhSachSinhVien->currentPage() != $danhSachSinhVien->lastPage())<a
                href="{{$danhSachSinhVien->nextPageUrl()}}">{{$danhSachSinhVien->currentPage() + 1}}</a> @endif
                <a href="{{$danhSachSinhVien->nextPageUrl()}}"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-toggle-status').forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.dataset.id;

                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: "Thao tác này sẽ thay đổi trạng thái tài khoản!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Huỷ',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/dean/student-management/${userId}/status`, {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                }
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        const label = button.querySelector('.status-label');
                                        label.textContent = data.new_status === 'hoat_dong' ? 'Khoá' : 'Mở';
                                        button.dataset.status = data.new_status;

                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Thành công!',
                                            text: `Tài khoản đã chuyển sang trạng thái ${data.new_status === 'hoat_dong' ? 'hoạt động' : 'không hoạt động'}`,
                                            timer: 2000,
                                            showConfirmButton: false,
                                        });
                                    } else {
                                        Swal.fire('Lỗi!', data.message || 'Không thể cập nhật trạng thái.', 'error');
                                    }
                                })
                                .catch(() => {
                                    Swal.fire('Lỗi!', 'Đã xảy ra lỗi khi gửi yêu cầu.', 'error');
                                });
                        }
                    });
                });
            });

            // Tìm kiếm sinh viên
            const form = document.getElementById("form-search-student-list");
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

            // Xác nhận xoá sinh viên
            document.querySelectorAll(".delete-btn").forEach(buttonDelete => {
                buttonDelete.addEventListener('click', function (e) {
                    e.preventDefault();
                    const userId = this.dataset.id;
                    Swal.fire({
                        title: 'Bạn có chắc muốn xoá sinh viên này?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + userId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection