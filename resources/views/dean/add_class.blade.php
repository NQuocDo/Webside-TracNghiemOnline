@extends('layout.dean')

@section('title')
    Quản lý Lớp học
@endsection
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }

    .class-management-content {
        padding: 20px;
        margin: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .add-class-button {
        background-color: #007bff;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        align-self: flex-start;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }

    .add-class-button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .add-class-button:active {
        background-color: #004085;
        transform: translateY(0);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fefefe;
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        width: 90%;
        position: relative;
        animation: animateshow 0.3s ease-out;
    }

    @keyframes animateshow {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .close-button {
        color: #aaa;
        position: absolute;
        top: 15px;
        right: 25px;
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-button:hover,
    .close-button:focus {
        color: #333;
        text-decoration: none;
    }

    h2 {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .add-class-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .add-class-form div {
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .add-class-form label {
        width: 35%;
        min-width: 120px;
        color: #555;
        font-size: 16px;
        font-weight: 500;
        margin-right: 15px;
        text-align: right;
    }

    .add-class-form input[type="text"],
    .add-class-form input[type="number"],
    .add-class-form textarea,
    .add-class-form select {
        flex-grow: 1;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        color: #333;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        resize: vertical;
    }

    .add-class-form input[type="text"]:focus,
    .add-class-form input[type="number"]:focus,
    .add-class-form textarea:focus,
    .add-class-form select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .add-class-submit-btn {
        background-color: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: 20px;
        align-self: flex-end;
        min-width: 180px;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
    }

    .add-class-submit-btn:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    .add-class-submit-btn:active {
        background-color: #1e7e34;
        transform: translateY(0);
    }

    .class-list-section {
        background-color: #fff;
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .class-list-section h2 {
        text-align: center;
        margin-bottom: 25px;
    }

    .class-list-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .class-list-table th,
    .class-list-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    .class-list-table th {
        background-color: #f2f2f2;
        font-weight: 600;
        color: #555;
    }

    .class-list-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .class-list-table tr:hover {
        background-color: #e9e9e9;
    }

    @media (max-width: 768px) {
        .add-class-form label {
            width: 100%;
            text-align: left;
            margin-right: 0;
            margin-bottom: 5px;
        }

        .add-class-form div {
            flex-direction: column;
            align-items: flex-start;
        }

        .add-class-form input[type="text"],
        .add-class-form input[type="number"],
        .add-class-form textarea,
        .add-class-form select {
            width: 100%;
        }

        .add-class-submit-btn {
            align-self: stretch;
            min-width: unset;
        }

        .modal-content {
            width: 95%;
            margin: 20px;
        }

        .class-list-section {
            padding: 15px;
        }

        .class-list-table,
        .class-list-table thead,
        .class-list-table tbody,
        .class-list-table th,
        .class-list-table td,
        .class-list-table tr {
            display: block;
        }

        .class-list-table thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .class-list-table tr {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .class-list-table td {
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            text-align: right;
        }

        .class-list-table td:before {
            position: absolute;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: 600;
            color: #777;
        }

        .class-list-table td:nth-of-type(1):before {
            content: "Mã Lớp:";
        }

        .class-list-table td:nth-of-type(2):before {
            content: "Tên Lớp:";
        }

        .class-list-table td:nth-of-type(3):before {
            content: "Ngành:";
        }

        .class-list-table td:nth-of-type(4):before {
            content: "Năm Học:";
        }

        .class-list-table td:nth-of-type(5):before {
            content: "Học Kỳ:";
        }

        .class-list-table td:nth-of-type(6):before {
            content: "Mô Tả:";
        }
    }
</style>
@section('content')
    <div class="class-management-content">
        <button id="openAddClassModalBtn" class="add-class-button">Thêm Lớp học Mới</button>

        <div id="addClassModal" class="modal">
            <div class="modal-content">
                <span class="close-button">&times;</span>
                <h2>Thêm Lớp học Mới</h2>
                <form action="{{ route('add_class_store') }}" method="POST" class="add-class-form" id="addClassForm">
                    @csrf
                    <div>
                        <label for="class_name">Tên Lớp</label>
                        <input type="text" id="class_name" name="ten_lop_hoc" required>
                    </div>
                    <div>
                        <label for="major">Ngành</label>
                        <input type="text" id="major" name="nganh" value="Công nghệ thông tin" required>
                    </div>
                    <div>
                        <label for="academic_year">Năm Học</label>
                        <input type="number" id="academic_year" name="nam_hoc" min="1900" max="2100" required>
                    </div>
                    <div>
                        <label for="semester">Học Kỳ</label>
                        <input type="number" id="semester" name="hoc_ky" min="1" max="10" required>
                    </div>
                    <div>
                        <label for="description">Mô Tả</label>
                        <textarea id="description" name="mo_ta" rows="4"></textarea>
                    </div>

                    <button type="submit" id="addClassSubmitBtn" class="add-class-submit-btn">Thêm Lớp Học</button>
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
                        <th>Ngành</th>
                        <th>Năm Học</th>
                        <th>Học Kỳ</th>
                        <th>Mô Tả</th>
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
                                <td>{{ $lopHoc->nganh }}</td>
                                <td>{{ $lopHoc->nam_hoc }}</td>
                                <td>{{ $lopHoc->hoc_ky }}</td>
                                <td>{{ $lopHoc->mo_ta }}</td>
                                <td>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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