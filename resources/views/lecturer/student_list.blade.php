@extends('layout.lecturer_layout')
<style>
    /* Cải thiện CSS cho Student List */
    .student-list-content {
        padding: 20px;
        margin: 20px;
        max-width: 1200px;
        /* Giới hạn chiều rộng tối đa */
        margin: 20px auto;
        /* Canh giữa */
    }

    .student-list-header {
        margin-bottom: 30px;
        float: right;
        /* Thay vì inline-end để tương thích tốt hơn */
        position: relative;
        display: inline-block;
    }

    .student-list-header input {
        padding: 8px 40px 8px 12px;
        /* Tăng padding bên trái, để chỗ cho icon */
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        font-size: 14px;
        width: 250px;
        transition: border-color 0.3s ease;
    }

    .student-list-header input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .student-list-header i {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        /* Canh giữa theo chiều dọc */
        color: #666;
        pointer-events: none;
        /* Không can thiệp vào input */
    }

    /* Clearfix để xử lý float */
    .student-list-content::after {
        content: "";
        display: table;
        clear: both;
    }

    .student-list-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .student-list-table thead tr th {
        border: none;
        /* Bỏ border trắng */
        padding: 15px 10px;
        text-align: center;
        background-color: rgb(31, 43, 62);
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .student-list-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .student-list-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .student-list-table tbody tr:nth-child(even) {
        background-color: #fdfdfd;
    }

    .student-list-table tbody tr td {
        padding: 12px 10px;
        border: 1px solid #e9ecef;
        /* Màu border nhẹ hơn */
        font-size: 14px;
        vertical-align: middle;
    }

    .actions-cell {
        width: 200px;
        text-align: center;
    }

    .actions-cell button {
        padding: 6px 12px;
        margin: 0px 3px;
        border-radius: 6px;
        background-color: rgb(253, 177, 27);
        border: none;
        color: #333;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .actions-cell button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Thêm các màu khác nhau cho các nút action */
    .actions-cell button.edit-btn {
        background-color: #28a745;
        color: white;
    }

    .actions-cell button.changepassword-btn {
        margin: 5px;
        background-color: #dc3545;
        color: white;
    }

    .actions-cell button.view-btn {
        background-color: #17a2b8;
        color: white;
    }

    /* Container phân trang */
    .pagination-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 25px;
        padding: 15px 10px;
        background-color: #fcfcfc;
        border-top: 1px solid #eee;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Nút "Trước" và "Sau" */
    .pagination-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin: 0 8px;
        min-width: 80px;
        text-align: center;
    }

    .pagination-btn:hover:not(:disabled) {
        background-color: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .pagination-btn:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
        opacity: 0.6;
        transform: none;
        box-shadow: none;
    }

    /* Container các số trang */
    .page-numbers {
        display: flex;
        gap: 6px;
        margin: 0 15px;
    }

    /* Số trang */
    .page-number {
        background-color: #e9ecef;
        color: #495057;
        padding: 10px 14px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        min-width: 35px;
        text-align: center;
        font-size: 14px;
    }

    .page-number:hover:not(.active) {
        background-color: #d4d8db;
        transform: translateY(-1px);
    }

    .page-number.active {
        background-color: #007bff;
        color: white;
        font-weight: 600;
        cursor: default;
        box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .student-list-content {
            padding: 10px;
            margin: 10px;
        }

        .student-list-header {
            float: none;
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }

        .student-list-header input {
            width: 100%;
            max-width: 300px;
        }

        .student-list-table {
            font-size: 12px;
        }

        .student-list-table th,
        .student-list-table td {
            padding: 8px 5px;
        }

        .actions-cell {
            width: auto;
        }

        .actions-cell button {
            padding: 4px 8px;
            font-size: 10px;
            margin: 1px;
        }

        .pagination-container {
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-btn {
            min-width: 60px;
            padding: 8px 12px;
            font-size: 12px;
        }

        .page-number {
            padding: 8px 10px;
            min-width: 30px;
            font-size: 12px;
        }
    }

    /* Loading state */
    .student-list-table.loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .student-list-table.loading::after {
        content: "Đang tải...";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.9);
        padding: 10px 20px;
        border-radius: 6px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 15px;
        display: block;
    }

    .empty-state h3 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
    }
</style>
@section('content')
    <div class="student-list-content">
        <div class="student-list-header">
            <input type="text" placeholder="Tìm kiếm sinh viên"><i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <div class="student-list-body">
            <table class="student-list-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sinh viên</th>
                        <th>Mã số sinh viên</th>
                        <th>Lớp</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if($danhSachSinhVien->isEmpty())
                        <tr>
                            <td colspan="5" class=" text-center">
                                Không có sinh viên
                            </td>
                        </tr>
                    @else
                        @foreach($danhSachSinhVien as $index => $sinhVien)
                            <tr>
                                <td class="stt-cell">{{ $index + 1 }}</td>
                                <td class="student-name-cell">{{ $sinhVien->nguoiDung->ho_ten }}</td>
                                <td class="student-score-cell">{{ $sinhVien->mssv }}</td>
                                <td class="student-class-cell">{{ $sinhVien->lopHoc->ten_lop_hoc }}</td>
                                <td class="actions-cell">
                                    @if(isset($sinhVien->nguoiDung))
    <button type="button"
        class="btn btn-sm btn-toggle-status"
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

                                    <button class="changepassword-btn" data-bs-toggle="modal" data-bs-target="#doiMatKhauModal"
                                        onclick="setStudentId('{{ $sinhVien->ma_sinh_vien }}')">
                                        <i class="fa-solid fa-eye"></i> Đổi mật khẩu
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="student-list-footer">
            <div class="pagination-container">
                <button id="prevPage" class="pagination-btn" disabled>&laquo; Trước</button>
                <div id="pageNumbers" class="page-numbers">
                </div>
                <button id="nextPage" class="pagination-btn">&raquo; Sau</button>
            </div>
        </div>
        <!-- Modal đổi mật khẩu -->
        <div class="modal fade" id="doiMatKhauModal" tabindex="-1" aria-labelledby="doiMatKhauLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('student_list_update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ma_sinh_vien" id="modal_ma_sinh_vien">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="doiMatKhauLabel">Đổi mật khẩu sinh viên</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
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
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll('.btn-toggle-status');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;

                fetch(`/lecturer/student-list/${userId}/status`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const label = this.querySelector('.status-label');

                        if (data.new_status === 'hoat_dong') {
                            label.textContent = 'Khoá';
                        } else {
                            label.textContent = 'Mở';
                        }

                        this.dataset.status = data.new_status;

                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: `Tài khoản đã được cập nhật: ${data.new_status === 'hoat_dong' ? 'Hoạt động' : 'Không hoạt động'}`,
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Không thể cập nhật trạng thái.',
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Đã xảy ra lỗi khi gửi yêu cầu.',
                    });
                });
            });
        });
    });

    function setStudentId(maSinhVien) {
        document.getElementById('modal_ma_sinh_vien').value = maSinhVien;
    }
</script>
@endsection