@extends('layout.dean')
@section('title')
    Quản lý sinh viên
@endsection
<style>
    .student-manage-content {
        padding: 20px;
        margin: 20px;
    }

    .student-manage-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .student-manage-header h2 {
        margin: 0;
        color: #343a40;
        font-weight: 600;
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
        width: 150px;
        text-align: center;
        padding: 12px 8px;
    }

    .actions-cell button {
        padding: 8px 16px;
        margin: 4px 4px;
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
        <div class="student-manage-header">
            <h2>Quản lý Sinh Viên</h2>
        </div>
        <form action="{{ route('student_management') }}" method="GET" id="form-search-student-list"
            class="form-search-student d-flex flex-wrap justify-content-end gap-2">
            <div class="mb-2">
                <input type="text" class="form-control" id="keyword" name="tu_khoa_tim_kiem"
                    placeholder="Tìm theo tên sinh viên" value="{{ $tuKhoaTimKiem }}">
            </div>
            <div class="mb-2">
                <select class="form-select" name="loai_lop"
                    onchange="document.getElementById('form-search-student-list').submit();">
                    <option value="">-- Loại lớp học --</option>
                    <option value="chinh_thuc">Chính thức</option>
                    <option value="nang_cao">Nâng cao</option>
                </select>
            </div>
            <div class="mb-2">
                <select class="form-select" name="ma_lop_hoc"
                    onchange="document.getElementById('form-search-student-list').submit();">
                    <option value="">-- Tất cả lớp học --</option>
                    @foreach ($danhSachLopHoc as $lop)
                        <option value="{{ $lop->ma_lop_hoc }}" {{ $maLopHoc == $lop->ma_lop_hoc ? 'selected' : '' }}>
                            {{ $lop->ten_lop_hoc }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="student-manage-body">
            <table class="student-manage-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="check-all"> STT</th>
                        <th>Tên sinh viên</th>
                        <th>MSSV</th>
                        <th>Email</th>
                        <th>Giới tính</th>
                        <th>Lớp hiện tại</th>
                        <th>Lớp đã học</th>
                        <th>Lớp ghép</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if($danhSachSinhVien->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center text-muted">Không có sinh viên</td>
                        </tr>
                    @else
                        @foreach($danhSachSinhVien as $index => $sinhVien)
                            @php
                                $nguoiDung = $sinhVien->nguoiDung;
                                $stt = ($danhSachSinhVien->currentPage() - 1) * $danhSachSinhVien->perPage() + $index + 1;
                                $tatCaLopHoc = $sinhVien->sinhVienLopHocs->sortByDesc('nam_hoc')->sortByDesc('hoc_ky');

                                // Lớp hiện tại: is_hien_tai = 1
                                $lopHienTai = $tatCaLopHoc->firstWhere('is_hien_tai', 1);
                                $lopHienTaiID = $lopHienTai?->ma_lop_hoc;

                                // Lớp đã học: is_hien_tai = 0
                                $lopDaHoc = $tatCaLopHoc->filter(fn($item) => $item->is_hien_tai == 0);

                                // Lớp học ghép: hinh_thuc = 'hoc_ghep'
                                $lopHocGhep = $tatCaLopHoc->filter(fn($item) => $item->hinh_thuc === 'hoc_ghep');
                            @endphp

                            <tr>
                                <!-- STT và checkbox -->
                                <td class="stt-cell text-center">
                                    <input type="checkbox" class="student-checkbox" value="{{ $sinhVien->ma_sinh_vien }}">
                                    {{ $stt }}
                                </td>

                                <!-- Thông tin cá nhân -->
                                <td>{{ $nguoiDung->ho_ten ?? 'Không rõ' }}</td>
                                <td>{{ $sinhVien->mssv ?? 'Không rõ' }}</td>
                                <td>{{ $nguoiDung->email ?? 'Không rõ' }}</td>
                                <td>{{ $nguoiDung->gioi_tinh ?? 'Không rõ' }}</td>

                                    <!-- Lớp hiện tại -->
                                    <td>
                                        @if($lopHienTai)
                                            {{ optional($lopHienTai->lopHoc)->ten_lop_hoc }}
                                            ({{ $lopHienTai->hoc_ky }}/{{ $lopHienTai->nam_hoc }})
                                        @else
                                            <span class="text-muted">Chưa có lớp chính thức</span>
                                        @endif
                                    </td>

                                    <!-- Lớp đã học -->
                                    <td>
                                        @if($lopDaHoc->isNotEmpty())
                                            <ul class="mb-0 ps-3">
                                                @foreach ($lopDaHoc as $lh)
                                                    <li>{{ optional($lh->lopHoc)->ten_lop_hoc }} ({{ $lh->hoc_ky }}/{{ $lh->nam_hoc }}) -
                                                        {{ $lh->hinh_thuc === 'chinh_thuc' ? 'Chính thức' : ($lh->hinh_thuc === 'hoc_ghep' ? 'Học ghép' : ucfirst($lh->hinh_thuc)) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">Không có lớp đã học</span>
                                        @endif
                                    </td>

                                <!-- Lớp ghép -->
                                <td>
                                    @if($lopHocGhep->isNotEmpty())
                                        <ul class="mb-0 ps-3">
                                            @foreach ($lopHocGhep as $lh)
                                                <li>{{ optional($lh->lopHoc)->ten_lop_hoc }} ({{ $lh->hoc_ky }}/{{ $lh->nam_hoc }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Không có lớp học ghép</span>
                                    @endif
                                </td>

                                <!-- Thao tác -->
                                <td class="actions-cell">
                                    @if($nguoiDung)
                                        <button type="button" class="btn btn-sm btn-toggle-status" style="color:white;"
                                            data-id="{{ $nguoiDung->ma_nguoi_dung }}"
                                            data-status="{{ $nguoiDung->trang_thai_tai_khoan }}">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>
                                            <span class="status-label">
                                                {{ $nguoiDung->trang_thai_tai_khoan === 'hoat_dong' ? 'Khoá' : 'Mở' }}
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
                                            <i class="fas fa-trash-alt"></i> Xoá
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-warning btn-edit-student" data-bs-toggle="modal"
                                        data-bs-target="#editStudentModal" data-id="{{ $sinhVien->ma_sinh_vien }}"
                                        data-mssv="{{ $sinhVien->mssv }}" data-ho-ten="{{ $nguoiDung->ho_ten }}"
                                        data-email="{{ $nguoiDung->email }}" data-gioi-tinh="{{ $nguoiDung->gioi_tinh }}"
                                        data-lop-hien-tai="{{ optional($lopHienTai)->ma_lop_hoc }}">
                                        <i class="fa fa-edit me-1"></i> Sửa
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success mb-3 mt-3" id="open-multi-class-modal">
                Thêm nhiều sinh viên vào lớp
            </button>
        </div>

        <div class="student-manage-footer">
            <div class="pagination">
                <a href="{{ $danhSachSinhVien->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @if($danhSachSinhVien->currentPage() > 1)
                    <a href="{{ $danhSachSinhVien->previousPageUrl() }}">{{ $danhSachSinhVien->currentPage() - 1 }}</a>
                @endif
                <a href="#" class="active">{{ $danhSachSinhVien->currentPage() }}</a>
                @if($danhSachSinhVien->hasMorePages())
                    <a href="{{ $danhSachSinhVien->nextPageUrl() }}">{{ $danhSachSinhVien->currentPage() + 1 }}</a>
                @endif
                <a href="{{ $danhSachSinhVien->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="modal fade" id="changeClassModal" tabindex="-1" aria-labelledby="changeClassModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('student_management_store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeClassModalLabel">Thêm nhiều sinh viên vào lớp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div id="selected-student-inputs"></div>

                        <label for="ma_lop_hoc">Chọn lớp:</label>
                        <select name="ma_lop_hoc" class="form-select" required>
                            @foreach($danhSachLopHoc as $lop)
                                <option value="{{ $lop->ma_lop_hoc }}">{{ $lop->ten_lop_hoc }}</option>
                            @endforeach
                        </select>
                        <label for="hinh_thuc" class="mt-3">Hình thức:</label>
                        <select name="hinh_thuc" class="form-select" required>
                            <option value="chinh_thuc" selected>Chính thức</option>
                            <option value="hoc_ghep">Học ghép</option>
                            <option value="nang_cao">Nâng cao</option>
                        </select>
                        <label for="ma_mon_hoc" class="mt-3">Môn học (bỏ qua nếu chuyển lớp):</label>
                        <select name="ma_mon_hoc" id="monHocSelect" class="form-select">
                            <option value="" disabled selected>-- Chọn môn học --</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Thêm vào lớp</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="edit-student-form" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa thông tin sinh viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <input type="hidden" id="edit-student-id" name="ma_sinh_vien">

                        <div class="col-md-6">
                            <label>MSSV</label>
                            <input type="text" name="mssv" id="edit-mssv" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Họ tên</label>
                            <input type="text" name="ho_ten" id="edit-ho-ten" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" id="edit-email" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Giới tính</label>
                            <select name="gioi_tinh" id="edit-gioi-tinh" class="form-select">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label>Lớp hiện tại</label>
                            <select name="lop_hien_tai" id="edit-lop-hien-tai" class="form-select">
                                <option value="">-- Chọn lớp --</option>
                                @foreach ($danhSachLopHoc as $lop)
                                    <option value="{{ $lop->ma_lop_hoc }}">{{ $lop->ten_lop_hoc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('check-all').addEventListener('change', function () {
                const isChecked = this.checked;
                document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = isChecked);
            });

            document.getElementById('open-multi-class-modal').addEventListener('click', function () {
                const selectedIds = [];
                document.querySelectorAll('.student-checkbox:checked').forEach(cb => {
                    selectedIds.push(cb.value);
                });

                if (selectedIds.length === 0) {
                    Swal.fire('Chưa chọn sinh viên nào!');
                    return;
                }

                const container = document.getElementById('selected-student-inputs');
                container.innerHTML = '';
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ma_sinh_viens[]';
                    input.value = id;
                    container.appendChild(input);
                });

                const modal = new bootstrap.Modal(document.getElementById('changeClassModal'));
                modal.show();
            });
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
            document.querySelectorAll('.btn-edit-student').forEach(button => {
                button.addEventListener('click', function () {
                    // Lấy dữ liệu từ attribute
                    const maSinhVien = this.getAttribute('data-id');
                    const mssv = this.getAttribute('data-mssv');
                    const hoTen = this.getAttribute('data-ho-ten');
                    const email = this.getAttribute('data-email');
                    const gioiTinh = this.getAttribute('data-gioi-tinh');
                    const lopHienTai = this.getAttribute('data-lop-hien-tai');

                    // Gán vào form trong modal
                    document.getElementById('edit-student-id').value = maSinhVien;
                    document.getElementById('edit-mssv').value = mssv;
                    document.getElementById('edit-ho-ten').value = hoTen;
                    document.getElementById('edit-email').value = email;
                    document.getElementById('edit-gioi-tinh').value = gioiTinh;
                    document.getElementById('edit-lop-hien-tai').value = lopHienTai;

                    // Gán action cho form
                    const form = document.getElementById('edit-student-form');
                    form.action = `/dean/student-management/${maSinhVien}`; // route('student_management_edit', maSinhVien)
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
        $(document).ready(function () {
            $('select[name="ma_lop_hoc"]').on('change', function () {
                let maLopHoc = $(this).val();

                if (maLopHoc) {
                    $.ajax({
                        url: '/get-subject-by-class',
                        type: 'GET',
                        data: { ma_lop_hoc: maLopHoc },
                        success: function (data) {
                            let $monHocSelect = $('#monHocSelect');
                            $monHocSelect.empty().append('<option value="" disabled selected>-- Chọn môn học --</option>');

                            if (data.length > 0) {
                                data.forEach(function (mon) {
                                    $monHocSelect.append(`<option value="${mon.ma_mon_hoc}">${mon.ten_mon_hoc}</option>`);
                                });
                            } else {
                                $monHocSelect.append('<option disabled>Không có môn học nào</option>');
                            }
                        },
                        error: function () {
                            alert('Lỗi khi tải môn học');
                        }
                    });
                }
            });
        });
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'warning',
                title: 'Không thể thực hiện!',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        @endif
    </script>
@endsection