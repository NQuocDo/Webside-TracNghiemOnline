@extends('layout.dean')

@section('title')
    Quản lý Lớp học
@endsection
<style>
    .class-management-content {
        padding: 30px;
        margin: 20px auto;
        max-width: 1200px;
    }

    .class-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-form input,
    .filter-form button {
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .filter-button {
        background-color: #28a745;
        color: white;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .filter-button:hover {
        background-color: #218838;
    }

    .add-class-button {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: #fff;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
    }

    .add-class-button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.4);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        position: relative;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .close-button {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 28px;
        color: #999;
        cursor: pointer;
        transition: color 0.3s;
    }

    .close-button:hover {
        color: #000;
    }

    h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
        color: #222;
    }

    .add-class-form {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .add-class-form div {
        display: flex;
        align-items: center;
    }

    .add-class-form label {
        width: 35%;
        min-width: 120px;
        font-weight: 500;
        color: #555;
    }

    .add-class-form input[type="text"],
    .add-class-form input[type="number"],
    .add-class-form textarea,
    .add-class-form select {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-size: 15px;
        transition: 0.2s;
        background-color: #fff;
    }

    .add-class-form input:focus,
    .add-class-form select:focus,
    .add-class-form textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
    }

    .add-class-submit-btn {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 12px 25px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        align-self: flex-end;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
    }

    .add-class-submit-btn:hover {
        background-color: #218838;
        transform: translateY(-1px);
    }

    .class-list-section {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        margin-top: 30px;
    }

    .class-list-section h2 {
        text-align: center;
        font-size: 22px;
        color: #333;
        margin-bottom: 20px;
    }

    .class-list-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    .class-list-table th,
    .class-list-table td {
        padding: 14px 12px;
        border: 1px solid #dee2e6;
        text-align: left;
    }

    .class-list-table th {
        background-color: #f1f3f5;
        color: #495057;
        font-weight: 600;
    }

    .class-list-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .class-list-table tbody tr:hover {
        background-color: #eef4ff;
        transition: 0.2s;
    }

    .btn {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0069d9;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
        margin-left: 10px;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    @media (max-width: 768px) {
        .add-class-form div {
            flex-direction: column;
            align-items: flex-start;
        }

        .add-class-form label {
            width: 100%;
            margin-bottom: 5px;
        }

        .class-list-table,
        .class-list-table thead,
        .class-list-table tbody,
        .class-list-table tr,
        .class-list-table th,
        .class-list-table td {
            display: block;
        }

        .class-list-table thead {
            display: none;
        }

        .class-list-table td {
            padding-left: 50%;
            position: relative;
            text-align: right;
            border: none;
            border-bottom: 1px solid #e9ecef;
        }

        .class-list-table td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 45%;
            white-space: nowrap;
            font-weight: bold;
            color: #495057;
            text-align: left;
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
    <div class="class-management-content">
        <div class="class-actions">
            <button id="openAddClassModalBtn" class="add-class-button">Thêm Lớp học Mới</button>

            <form id="form-search-class-list" method="GET" action="{{ route('add_class') }}" class="filter-form">
                <input type="text" name="ten_lop_hoc" id="ten_lop_hoc" placeholder="Nhập tên lớp"
                    value="{{ request('ten_lop_hoc') }}" />

                <input type="number" name="nam_hoc" id="nam_hoc" placeholder="Năm học" value="{{ request('nam_hoc') }}"
                    min="2000" max="{{ now()->year + 5 }}" />
            </form>
        </div>
        <div id="addClassModal" class="modal">
            <div class="modal-content">
                <span class="close-button">&times;</span>
                <h2>Thêm Lớp học Mới</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $loi)
                            <div>{{ $loi }}</div>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('add_class_store') }}" method="POST" class="add-class-form" id="addClassForm">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit-id">
                    <div>
                        <label for="class_name">Tên Lớp</label>
                        <input type="text" id="class_name" name="ten_lop_hoc" value="{{ old('ten_lop_hoc') }}" required
                            maxlength="255">
                    </div>
                    <div>
                        <label for="academic_year">Năm Học</label>
                        @php
                            $now = now()->year;
                            $min = $now - 2;
                            $max = $now + 3;
                        @endphp
                        <input type="number" id="academic_year" name="nam_hoc" min="{{ $min }}" max="{{ $max }}" required
                            value="{{ old('nam_hoc') }}">
                    </div>
                    <div>
                        <small>(Chỉ cho phép từ {{ $min }} đến {{ $max }}). Nhập niên khóa. Ví dụ: năm 2023 dành cho khoá
                            23.</small>
                    </div>

                    <button type="submit" id="addClassSubmitBtn" class="add-class-submit-btn">
                        Thêm Lớp Học
                    </button>
                </form>
            </div>
        </div>

        <div class="class-list-section">
            <h2>Danh sách Lớp học</h2>
            <table class="class-list-table">
                <thead>
                    <tr>
                        <th>Mã Lớp</th>
                        <th>Tên Lớp</th>
                        <th>Khoá</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if($danhSachLopHoc->isEmpty())
                        <tr class="col-6 text-center p3">Không có lớp học nào được tạo.</tr>
                    @else
                        @foreach($danhSachLopHoc as $index => $lopHoc)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $lopHoc->ten_lop_hoc }}</td>
                                <td>{{ $lopHoc->nien_khoa }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary edit-class-btn"
                                        data-id="{{ $lopHoc->ma_lop_hoc }}" data-ten="{{ $lopHoc->ten_lop_hoc }}"
                                        data-nam="{{ $lopHoc->nam_hoc }}">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form id="delete-form-{{ $lopHoc->ma_lop_hoc }}"
                                        action="{{ route('add_class_del', $lopHoc->ma_lop_hoc) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete('{{ $lopHoc->ma_lop_hoc }}')">Xoá lớp học</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="add-class-footer">
            <div class="pagination">
                <a href="{{$danhSachLopHoc->previousPageUrl()}}"><i class="fa-solid fa-chevron-left"></i></a>
                @if($danhSachLopHoc->currentPage() - 1 != 0) <a
                href="{{$danhSachLopHoc->previousPageUrl()}}">{{$danhSachLopHoc->currentPage() - 1}}</i></a> @endif
                <a href="{{$danhSachLopHoc->currentPage()}}" class="active"> {{$danhSachLopHoc->currentPage()}}</a>
                @if($danhSachLopHoc->currentPage() != $danhSachLopHoc->lastPage())<a
                href="{{$danhSachLopHoc->nextPageUrl()}}">{{$danhSachLopHoc->currentPage() + 1}}</a> @endif
                <a href="{{$danhSachLopHoc->nextPageUrl()}}"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById("form-search-class-list");
            const tenLopInput = document.getElementById("ten_lop_hoc");
            const namHocInput = document.getElementById("nam_hoc");

            if (tenLopInput && namHocInput) {
                const triggerSearch = () => {
                    form.submit();
                };

                tenLopInput.addEventListener("change", triggerSearch);
                namHocInput.addEventListener("change", triggerSearch);
            }
            var modal = document.getElementById('addClassModal');
            var btn = document.getElementById('openAddClassModalBtn');
            var span = document.querySelector('.close-button');

            if (btn && modal && span) {
                btn.onclick = function () {
                    modal.style.display = 'flex';
                };

                span.onclick = function () {
                    modal.style.display = 'none';
                };

                window.onclick = function (event) {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                };
            }
            document.querySelectorAll('.edit-class-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const form = document.getElementById('addClassForm');
                    const id = button.dataset.id;

                    form.action = `/dean/add-class/update/${id}`;
                    form.querySelector('input[name="ten_lop_hoc"]').value = button.dataset.ten;
                    form.querySelector('input[name="nam_hoc"]').value = button.dataset.nam;
                    document.getElementById('edit-id').value = id;

                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.setAttribute('type', 'hidden');
                        methodInput.setAttribute('name', '_method');
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    document.getElementById('addClassSubmitBtn').textContent = 'Cập nhật Lớp học';

                    // Mở popup nếu đang ẩn
                    const modal = document.getElementById('addClassModal');
                    modal.style.display = 'flex';

                    form.scrollIntoView({ behavior: 'smooth' });
                });
            });
        });
        function confirmDelete(id) {
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xoá?',
                text: "Lớp học sẽ bị xoá và không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Vâng, xoá!',
                cancelButtonText: 'Huỷ'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
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
                    icon: 'error',
                    title: 'Lỗi!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            @endif
                                                                       });
    </script>
@endsection