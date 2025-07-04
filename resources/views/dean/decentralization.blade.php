@extends('layout.dean')
@section('title')
    Phân quyền giảng dạy
@endsection
<style>
    .decentralization-content {
        padding: 20px;
        margin: 20px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .search-decentralization {
        margin-bottom: 25px;
        margin-top: 25px;
        text-align: right;
        position: relative;
    }

    .search-decentralization input {
        padding: 8px 15px;
        border-radius: 20px;
        border: 1px solid #ced4da;
        font-size: 15px;
        width: 280px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .search-decentralization input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }


    .search-decentralization i {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: #6c757d;
        cursor: pointer;
    }

    .decentralization-body {
        margin-top: 20px;
        overflow-x: auto;
    }

    .decentralization-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .decentralization-table thead {
        background-color: #343a40;
        color: #fff;
    }

    .decentralization-table th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 15px;
        white-space: nowrap;
    }

    .decentralization-table th:first-child {
        border-top-left-radius: 8px;
    }

    .decentralization-table th:last-child {
        border-top-right-radius: 8px;
    }

    .decentralization-table tbody tr {
        background-color: #fff;
        transition: background-color 0.2s ease;
    }

    .decentralization-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .decentralization-table tbody tr:hover {
        background-color: #e2e6ea;
    }

    .decentralization-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .decentralization-table td:last-child {
        border-right: none;
    }

    .decentralization-table tbody tr:last-child td {
        border-bottom: none;
    }

    .decentralization-code-cell,
    .decentralization-subject-cell,
    .decentralization-semester-cell {
        text-align: center;
    }

    .actions-cell {
        text-align: center;
        width: 180px;
        white-space: nowrap;
    }

    .actions-cell button {
        padding: 8px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 20px;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .actions-cell button i {
        margin-right: 5px;
    }

    .block-btn {
        background-color: #dc3545;
        color: white;
    }

    .block-btn:hover {
        background-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .delete-btn {
        background-color: #ffc107;
        color: #333;
    }

    .delete-btn:hover {
        background-color: #e0a800;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    #add-decentralization-btn-fake {
        background-color: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 20px;
        float: right;
        transition: background-color 0.3s ease, transform 0.2s ease;
        font-size: 16px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    #add-decentralization-btn-fake:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    #add-decentralization-btn-fake:active {
        background-color: #1e7e34;
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: none;
        z-index: 999;
        animation: fadeIn 0.3s forwards;
    }

    .decentralization-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        max-width: 650px;
        width: 90%;
        display: none;
        z-index: 1000;
        animation: slideIn 0.3s forwards;
    }

    .modal-overlay.active,
    .decentralization-modal.active {
        display: block;
    }

    .close-modal {
        position: absolute;
        top: 15px;
        right: 25px;
        font-size: 30px;
        color: #aaa;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: #333;
        text-decoration: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translate(-50%, -60%);
        }

        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translate(-50%, -50%);
        }

        to {
            opacity: 0;
            transform: translate(-50%, -60%);
        }
    }

    .modal-overlay.hide-animation {
        animation: fadeOut 0.3s forwards;
    }

    .decentralization-modal.hide-animation {
        animation: slideOut 0.3s forwards;
    }

    .add-decentralization-form {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 25px;
        max-width: 500px;
        margin: 30px auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .add-decentralization-form>div {
        display: flex;
        flex-direction: column;
    }

    .add-decentralization-form label {
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
        font-size: 1em;
    }

    .add-decentralization-form section {
        position: relative;
        display: block;
        width: 100%;
    }

    .add-decentralization-form select {
        width: 100%;
        padding: 10px 15px;
        font-size: 1.05em;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        cursor: pointer;
        line-height: 1.5;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .add-decentralization-form select {
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20256%20512%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M192%20256L64%20128v256l128-128z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 10px;
    }

    .add-decentralization-form select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .add-decentralization-btn {
        background-color: #28a745;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.1em;
        font-weight: bold;
        transition: background-color 0.2s ease;
        align-self: flex-end;
        margin-top: 10px;
    }

    .add-decentralization-btn:hover {
        background-color: #218838;
    }
</style>
@section('content')
    <div class="decentralization-content">
        <form action="{{ route('decentralization') }}" method="GET" id="filter-form-decentralization"
            class="form-search-decentralization" style="justify-content: end;">
            <div class="mb-2 me-3">
                <select class="form-select" name="giang_vien_id" id="giangVienSelect">
                    <option value="">-- Tất cả giảng viên --</option>
                    @foreach ($danhSachGiangVien as $giangVien)
                        <option value="{{ $giangVien->ma_giang_vien }}">
                            {{ $giangVien->nguoiDung->ho_ten }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="decentralization-body">
            <table class="decentralization-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên giảng viên</th>
                        <th>Môn</th>
                        <th>Học kỳ</th>
                        <th>Lớp</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if($danhSachPhanQuyen->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">Không có quyền nào được tạo.</td>
                        </tr>
                    @else
                        @foreach($danhSachPhanQuyen as $index => $phanQuyen)
                            <tr>
                                <td class="decentralization-code-cell">{{$index + 1}}</td>
                                <td class="decentralization-lecturer-cell">
                                    {{ $phanQuyen->giangVien && $phanQuyen->giangVien->nguoiDung ? $phanQuyen->giangVien->nguoiDung->ho_ten : 'N/A' }}
                                </td>
                                <td class="decentralization-subject-cell">{{ $phanQuyen->monHoc->ten_mon_hoc }}</td>
                                <td class="decentralization-semester-cell">{{ $phanQuyen->monHoc->hoc_ky }}</td>
                                <td class="decentralization-class-cell">
                                    {{ optional($phanQuyen->lopHoc)->ten_lop_hoc ?? 'Chưa có lớp' }}
                                </td>
                                <td class="actions-cell">
                                    <form id="delete-form-{{ $phanQuyen->ma_phan_quyen }}"
                                        action="{{ route('decentralization_del', $phanQuyen->ma_phan_quyen) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-confirm" data-id="{{ $phanQuyen->ma_phan_quyen }}">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <button type="button" id="add-decentralization-btn-fake">Thêm quyền giảng
                dạy</button>

        </div>
        <div class="decentralization-footer">
            <div class="pagination">
                <a href="{{$danhSachPhanQuyen->previousPageUrl()}}"><i class="fa-solid fa-chevron-left"></i></a>
                @if($danhSachPhanQuyen->currentPage() - 1 != 0) <a
                href="{{$danhSachPhanQuyen->previousPageUrl()}}">{{$danhSachPhanQuyen->currentPage() - 1}}</i></a> @endif
                <a href="{{$danhSachPhanQuyen->currentPage()}}" class="active"> {{$danhSachPhanQuyen->currentPage()}}</a>
                @if($danhSachPhanQuyen->currentPage() != $danhSachPhanQuyen->lastPage())<a
                href="{{$danhSachPhanQuyen->nextPageUrl()}}">{{$danhSachPhanQuyen->currentPage() + 1}}</a> @endif
                <a href="{{$danhSachPhanQuyen->nextPageUrl()}}"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="decentralizationModalOverlay"></div>
    <div class="decentralization-modal" id="decentralizationModal">
        <span class="close-modal">&times;</span>
        <div class="decentralization-header">
            <div class="header-title">
                <h3>Thêm quyền giảng dạy</h3>
            </div>
            <div class="header-content">
                <form action="{{ route('decentralization.store') }}" method="POST" class="add-decentralization-form">
                    @csrf
                    <div>
                        <label for="">Giảng viên:</label>
                        <section>
                            <select name="lecturer" id="lecturer_select" required>
                                @foreach($danhSachGiangVien as $giangVien)
                                    <option value="{{ $giangVien->ma_giang_vien }}">
                                        {{ $giangVien->nguoiDung->ho_ten ?? $giangVien->ma_giang_vien }}
                                    </option>
                                @endforeach
                            </select>
                        </section>
                    </div>
                    <div><label for="subject_select">Môn học:</label>
                        <section>
                            <select name="subject" id="subject_select" required>
                                <option value="" disabled selected>-- Chọn môn học --</option>
                                @foreach($danhSachMonHoc as $monHoc)
                                    <option value="{{ $monHoc->ma_mon_hoc}}">{{ $monHoc->ten_mon_hoc }}</option>
                                @endforeach
                            </select>
                        </section>
                    </div>
                    <div><label for="class_select">Lớp học:</label>
                        <section>
                            <select name="class" id="class_select" required>
                                <option value="" disabled selected>-- Chọn lớp học --</option>
                                @foreach($danhSachLopHoc as $lopHoc)
                                    <option value="{{ $lopHoc->ma_lop_hoc}}">{{ $lopHoc->ten_lop_hoc }}</option>
                                @endforeach
                            </select>
                        </section>
                    </div>
                    <button type="submit" class="add-decentralization-btn">Thêm quyền dạy học</button>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const openModalBtn = document.getElementById('add-decentralization-btn-fake');
            const decentralizationModal = document.getElementById('decentralizationModal');
            const modalOverlay = document.getElementById('decentralizationModalOverlay');
            const closeModalBtn = decentralizationModal.querySelector('.close-modal');

            function openModal() {
                modalOverlay.classList.remove('hide-animation');
                decentralizationModal.classList.remove('hide-animation');
                modalOverlay.classList.add('active');
                decentralizationModal.classList.add('active');
            }

            function closeOutModal() {
                modalOverlay.classList.add('hide-animation');
                decentralizationModal.classList.add('hide-animation');

                // Chờ animation kết thúc trước khi ẩn hoàn toàn
                setTimeout(() => {
                    modalOverlay.classList.remove('active', 'hide-animation');
                    decentralizationModal.classList.remove('active', 'hide-animation');
                }, 300); // Thời gian này phải khớp với transition duration trong CSS (0.3s)
            }
            const deleteButtons = document.querySelectorAll('.btn-delete-confirm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // 🚫 Ngăn form submit ngay lập tức

                    const subjectId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Bạn có chắc không?',
                        text: "Bạn có chắc chắn xoá quyền dạy câu hỏi này",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + subjectId).submit();
                        }
                    });
                });
            });
            const form = document.getElementById("filter-form-decentralization");
            const select = document.getElementById("giangVienSelect");

            if (form && select) {
                select.addEventListener("change", function () {
                    form.submit();
                });
            }

            // Mở modal khi click nút
            openModalBtn.addEventListener('click', openModal);

            // Đóng modal khi click nút đóng
            closeModalBtn.addEventListener('click', closeOutModal);

            // Đóng modal khi click ra ngoài overlay
            modalOverlay.addEventListener('click', closeOutModal);

            // Ngăn chặn đóng modal khi click vào nội dung modal (để tránh xung đột với overlay)
            decentralizationModal.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // Kiểm tra thông báo thành công từ session flash
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false, // Tự động đóng sau một khoảng thời gian
                    timer: 2000 // Tự động đóng sau 2 giây
                });
            @endif

            // Kiểm tra thông báo lỗi từ session flash
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true // Giữ thông báo lỗi cho người dùng đọc
                });
            @endif
                                                    });
    </script>
@endsection