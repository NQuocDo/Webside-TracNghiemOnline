@extends('layout.dean')
@section('title')
    Phân quyền giảng dạy
@endsection
<style>
    /* Styling tổng thể cho phần nội dung quản lý phân quyền */
    .decentralization-content {
        padding: 20px;
        margin: 20px;
        background-color: #fff;
        /* Nền trắng cho toàn bộ khu vực */
        border-radius: 12px;
        /* Bo tròn góc cho toàn bộ khu vực */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        /* Đổ bóng nhẹ */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Thanh tìm kiếm */
    .search-decentralization {
        margin-bottom: 25px;
        /* Điều chỉnh khoảng cách */
        margin-top: 25px;
        /* Khoảng cách từ form trên */
        text-align: right;
        /* Căn phải toàn bộ search box */
        position: relative;
        /* Thêm để icon tìm kiếm định vị được */
    }

    .search-decentralization input {
        padding: 8px 15px;
        /* Tăng padding */
        border-radius: 20px;
        /* Bo tròn nhiều hơn */
        border: 1px solid #ced4da;
        font-size: 15px;
        width: 280px;
        /* Chiều rộng cố định cho input search */
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
        /* Căn giữa icon theo chiều dọc */
        color: #6c757d;
        cursor: pointer;
    }

    /* ================== TABLE STYLING ================== */
    .decentralization-body {
        margin-top: 20px;
        overflow-x: auto;
        /* Đảm bảo bảng cuộn ngang nếu nội dung quá dài */
    }

    .decentralization-table {
        width: 100%;
        border-collapse: separate;
        /* Quan trọng để dùng border-radius và khoảng cách giữa các ô */
        border-spacing: 0;
        /* Loại bỏ khoảng cách mặc định giữa các ô */
        border-radius: 8px;
        /* Bo tròn góc cho toàn bộ bảng */
        overflow: hidden;
        /* Đảm bảo nội dung không tràn ra khỏi border-radius */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        /* Đổ bóng nhẹ cho bảng */
        margin-bottom: 20px;
    }

    .decentralization-table thead {
        background-color: #343a40;
        /* Màu nền header (xám đậm) */
        color: #fff;
    }

    .decentralization-table th {
        padding: 12px 15px;
        /* Tăng padding để bảng thoáng hơn */
        text-align: left;
        /* Căn trái tiêu đề */
        font-weight: 600;
        font-size: 15px;
        white-space: nowrap;
        /* Ngăn không cho tiêu đề bị xuống dòng */
    }

    /* Bo tròn góc cho header */
    .decentralization-table th:first-child {
        border-top-left-radius: 8px;
    }

    .decentralization-table th:last-child {
        border-top-right-radius: 8px;
    }

    .decentralization-table tbody tr {
        background-color: #fff;
        /* Nền trắng cho hàng chẵn */
        transition: background-color 0.2s ease;
    }

    /* Zebra striping - Hàng lẻ có nền xám nhạt */
    .decentralization-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .decentralization-table tbody tr:hover {
        background-color: #e2e6ea;
        /* Hiệu ứng hover */
    }

    .decentralization-table td {
        padding: 10px 15px;
        /* Tăng padding */
        border-bottom: 1px solid #dee2e6;
        /* Viền dưới mỏng */
        border-right: 1px solid #dee2e6;
        /* Viền phải mỏng */
        font-size: 14px;
        color: #333;
        vertical-align: middle;
        /* Căn giữa theo chiều dọc */
    }

    /* Loại bỏ viền phải cho cột cuối cùng */
    .decentralization-table td:last-child {
        border-right: none;
    }

    /* Loại bỏ viền dưới cho hàng cuối cùng */
    .decentralization-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Căn giữa nội dung cho các cột cụ thể nếu cần */
    .decentralization-code-cell,
    .decentralization-subject-cell,
    .decentralization-semester-cell {
        text-align: center;
    }

    /* Cột thao tác */
    .actions-cell {
        text-align: center;
        width: 180px;
        /* Điều chỉnh độ rộng */
        white-space: nowrap;
        /* Giữ các nút trên một dòng */
    }

    .actions-cell button {
        padding: 8px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 20px;
        /* Bo tròn nhiều hơn */
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        /* Để căn giữa icon và text */
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Đổ bóng nhẹ */
    }

    .actions-cell button i {
        margin-right: 5px;
        /* Khoảng cách giữa icon và text */
    }

    /* Nút "Khoá" */
    .block-btn {
        background-color: #dc3545;
        /* Màu đỏ */
        color: white;
    }

    .block-btn:hover {
        background-color: #c82333;
        /* Đậm hơn khi hover */
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Nút "Xóa" */
    .delete-btn {
        background-color: #ffc107;
        /* Màu vàng cam */
        color: #333;
        /* Chữ màu tối */
    }

    .delete-btn:hover {
        background-color: #e0a800;
        /* Đậm hơn khi hover */
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Nút "Thêm quyền giảng dạy" ở cuối bảng */
    #add-decentralization-btn-fake {
        background-color: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 20px;
        float: right;
        /* Căn phải */
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


    /* Styles cho Container phân trang */
    .pagination-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 25px;
        padding: 10px 0;
        background-color: #fcfcfc;
        border-top: 1px solid #eee;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Styles cho các nút "Trước" và "Sau" */
    .pagination-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.95em;
        transition: background-color 0.3s ease;
        margin: 0 5px;
        min-width: 80px;
        text-align: center;
    }

    .pagination-btn:hover:not(:disabled) {
        background-color: #0056b3;
    }

    .pagination-btn:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* Styles cho container các số trang */
    .page-numbers {
        display: flex;
        gap: 5px;
        margin: 0 10px;
    }

    /* Styles cho từng số trang */
    .page-number {
        background-color: #e9ecef;
        color: #495057;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
        font-weight: 500;
        min-width: 30px;
        text-align: center;
    }

    .page-number:hover:not(.active) {
        background-color: #d4d8db;
    }

    .page-number.active {
        background-color: #007bff;
        color: white;
        font-weight: 600;
        cursor: default;
    }


    /* ================== MODAL STYLING (NEW) ================== */
    /* Overlay nền mờ */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        /* Nền đen trong suốt */
        display: none;
        /* Mặc định ẩn */
        z-index: 999;
        /* Đảm bảo nằm trên các phần tử khác */
        animation: fadeIn 0.3s forwards;
        /* Hiệu ứng mờ dần khi hiện */
    }

    /* Container của Modal */
    .decentralization-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        /* Căn giữa màn hình */
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        max-width: 650px;
        /* Giới hạn chiều rộng */
        width: 90%;
        /* Chiếm 90% chiều rộng trên màn hình nhỏ hơn */
        display: none;
        /* Mặc định ẩn */
        z-index: 1000;
        /* Nằm trên overlay */
        animation: slideIn 0.3s forwards;
        /* Hiệu ứng trượt vào khi hiện */
    }

    /* Hiển thị Modal khi có class 'active' */
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

    /* Keyframes cho hiệu ứng */
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

    /* Áp dụng hiệu ứng fadeOut/slideOut khi modal bị ẩn */
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
        /* Giới hạn chiều rộng để form không quá trải dài */
        margin: 30px auto;
        /* Canh giữa form và tạo khoảng cách trên dưới */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        /* Thêm đổ bóng nhẹ */
        display: flex;
        flex-direction: column;
        /* Sắp xếp các phần tử con theo cột */
        gap: 20px;
        /* Khoảng cách giữa các nhóm input */
    }

    /* Các div chứa label và select */
    .add-decentralization-form>div {
        display: flex;
        flex-direction: column;
        /* Sắp xếp label và section/select theo cột */
    }

    /* Label của các trường */
    .add-decentralization-form label {
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
        font-size: 1em;
        /* Kích thước font cho label */
    }

    /* Section (nếu bạn muốn định kiểu thêm cho container của select) */
    .add-decentralization-form section {
        position: relative;
        /* Dùng cho các icon hoặc mũi tên tùy chỉnh nếu cần */
        display: block;
        /* Đảm bảo section chiếm toàn bộ chiều rộng */
        width: 100%;
    }

    /* Định kiểu cho các thẻ select */
    .add-decentralization-form select {
        width: 100%;
        /* Chiếm toàn bộ chiều rộng của container */
        padding: 10px 15px;
        /* Tăng padding để ô select to hơn */
        font-size: 1.05em;
        /* Tăng kích thước font cho text trong select */
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
        -webkit-appearance: none;
        /* Bỏ giao diện mặc định của trình duyệt cho Chrome/Safari */
        -moz-appearance: none;
        /* Bỏ giao diện mặc định của trình duyệt cho Firefox */
        appearance: none;
        /* Bỏ giao diện mặc định (CSS tiêu chuẩn) */
        cursor: pointer;
        line-height: 1.5;
        /* Đảm bảo text không quá sát */
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        /* Hiệu ứng khi focus */
    }

    /* Mũi tên tùy chỉnh cho select (khi dùng appearance: none) */
    .add-decentralization-form select {
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20256%20512%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M192%20256L64%20128v256l128-128z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 10px;
        /* Kích thước của mũi tên SVG */
    }

    /* Hiệu ứng khi focus vào select */
    .add-decentralization-form select:focus {
        border-color: #007bff;
        /* Màu viền xanh khi focus */
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        /* Đổ bóng nhẹ khi focus */
        outline: none;
        /* Bỏ outline mặc định */
    }

    /* Định kiểu cho nút "Thêm quyền dạy học" */
    .add-decentralization-btn {
        background-color: #28a745;
        /* Màu xanh lá cây */
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.1em;
        font-weight: bold;
        transition: background-color 0.2s ease;
        align-self: flex-end;
        /* Căn nút sang phải (nếu form dùng flex-direction: column) */
        margin-top: 10px;
        /* Khoảng cách trên so với phần tử trước */
    }

    .add-decentralization-btn:hover {
        background-color: #218838;
        /* Màu xanh đậm hơn khi hover */
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
                            <td colspan="5" class="text-center text-muted">Không có quyền nào được tạo.</td>
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
            <div class="pagination-container">
                <button id="prevPage" class="pagination-btn" disabled>&laquo; Trước</button>
                <div id="pageNumbers" class="page-numbers">
                </div>
                <button id="nextPage" class="pagination-btn">&raquo; Sau</button>
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