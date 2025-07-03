@extends('layout.dean')
@section('title')
    Quản lý môn học
@endsection
<style>
    .subject-manage-content {
        padding: 20px;
        margin: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Popup thêm môn học (giữ nguyên hoặc điều chỉnh nếu cần) */
    .add-subject {
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #e0e0e0;
        background-color: #f8f9fa;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        max-width: 600px;
        margin: 40px auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: none;
        /* Ban đầu ẩn, sẽ hiển thị bằng JS */
    }

    .add-subject h2 {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .add-subject-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .add-subject-form div {
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .add-subject-form label {
        width: 35%;
        min-width: 120px;
        color: #555;
        font-size: 16px;
        font-weight: 500;
        margin-right: 15px;
        text-align: right;
    }

    .add-subject-form input[type="text"],
    .add-subject-form input[type="number"] {
        /* Thêm type="number" */
        flex-grow: 1;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        color: #333;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .add-subject-form input[type="text"]:focus,
    .add-subject-form input[type="number"]:focus {
        /* Thêm type="number" */
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .option-difficulty section {
        flex-grow: 1;
    }

    .option-difficulty select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        background-color: #fff;
        font-size: 1rem;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%204%205%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M2%200L0%202h4zm0%205L0%203h4z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
    }

    /* Nút thêm môn học trong form (nếu là submit trực tiếp) */
    #add-subject-btn {
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
        /* Căn chỉnh nút về bên phải */
        min-width: 180px;
    }

    #add-subject-btn:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    #add-subject-btn:active {
        background-color: #1e7e34;
        transform: translateY(0);
    }

    /* ================== TABLE STYLING ================== */

    /* Container cho bảng */
    .subject-manage-body {
        margin-top: 30px;
        overflow-x: auto;
    }

    .subject-manage-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        /* Loại bỏ khoảng cách mặc định giữa các ô */
        border-radius: 8px;
        /* Bo tròn góc cho toàn bộ bảng */
        overflow: hidden;
        /* Đảm bảo nội dung không tràn ra khỏi border-radius */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        /* Đổ bóng nhẹ cho bảng */
        margin-bottom: 20px;
        /* Khoảng cách dưới bảng */
    }

    .subject-manage-table thead {
        background-color: #343a40;
        /* Màu nền header (xám đậm) */
        color: #fff;
    }

    .subject-manage-table th {
        padding: 12px 15px;
        /* Tăng padding để bảng thoáng hơn */
        text-align: left;
        /* Căn trái theo hình ảnh */
        font-weight: 600;
        font-size: 15px;
        white-space: nowrap;
        /* Ngăn không cho tiêu đề bị xuống dòng */
    }

    /* Bo tròn góc cho header */
    .subject-manage-table th:first-child {
        border-top-left-radius: 8px;
    }

    .subject-manage-table th:last-child {
        border-top-right-radius: 8px;
    }

    .subject-manage-table tbody tr {
        background-color: #fff;
        /* Nền trắng cho hàng chẵn */
        transition: background-color 0.2s ease;
    }

    /* Zebra striping - Hàng lẻ có nền xám nhạt */
    .subject-manage-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .subject-manage-table tbody tr:hover {
        background-color: #e2e6ea;
        /* Hiệu ứng hover */
    }

    .subject-manage-table td {
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
    .subject-manage-table td:last-child {
        border-right: none;
    }

    /* Loại bỏ viền dưới cho hàng cuối cùng */
    .subject-manage-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Căn giữa nội dung cho các cột cụ thể */
    .stt-cell,
    .subject-score-cell,
    .subject-semester-cell,
    .subject-diffculty-cell {
        text-align: center;
        width: 60px;
        /* Giảm chiều rộng cho cột STT */
    }

    /* Cột thao tác */
    .actions-cell {
        text-align: center;
        /* Căn giữa các nút */
        width: 150px;
        /* Điều chỉnh độ rộng nếu cần */
        white-space: nowrap;
        /* Giữ các nút trên một dòng */
    }

    .actions-cell button {
        padding: 8px 12px;
        /* Tăng padding */
        margin: 0 4px;
        /* Giảm khoảng cách giữa các nút */
        border: none;
        border-radius: 10px;
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

    /* Nút xóa */
    .btn-delete-confirm {
        background: red;
        color: white;
        box-shadow: 0 2px 4px rgba(253, 126, 20, 0.3);
    }

    .btn-delete-confirm:hover {
        background-color: #e0a800;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .delete-btn i {
        margin-right: 5px;
        /* Khoảng cách giữa icon và text */
    }

    /* Thanh tìm kiếm (giữ nguyên) */
    .search-subject {
        margin-bottom: 30px;
        float: inline-end;
        /* Hoặc dùng flexbox để căn chỉnh tốt hơn */
        position: relative;
        display: inline-block;
    }

    .search-subject input {
        padding: 8px 15px;
        /* Tăng padding input search */
        border-radius: 20px;
        /* Bo tròn nhiều hơn */
        border: 1px solid #ced4da;
        font-size: 15px;
        width: 250px;
        /* Chiều rộng cố định cho input search */
    }

    .search-subject i {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        /* Căn giữa icon theo chiều dọc */
        color: #6c757d;
    }

    /* Nút "Thêm môn học" ở cuối bảng (nút giả) */
    .add-subject-btn-fake {
        background-color: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 20px;
        float: right;
        /* Thay float: inline-end; */
        transition: background-color 0.3s ease, transform 0.2s ease;
        font-size: 16px;
        font-weight: 500;
    }

    .add-subject-btn-fake:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    /* Pagination (giữ nguyên) */
    .pagination {
        width: 100%;
        display: flex;
        /* Dùng flexbox để căn giữa */
        justify-content: center;
        margin-top: 20px;
        padding-bottom: 20px;
    }

    .pagination a,
    .pagination span {
        /* Thêm span cho các nút không phải link (như ...) */
        color: #007bff;
        /* Màu xanh cho link pagination */
        margin: 0 5px;
        /* Khoảng cách giữa các số trang */
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
        border: 1px solid #dee2e6;
        /* Viền nhẹ */
    }

    .pagination a:hover {
        background-color: #007bff;
        color: white;
        box-shadow: none;
        /* Bỏ shadow cũ */
    }

    .pagination .active span {
        /* Style cho trang hiện tại */
        background-color: #007bff;
        color: white;
        border-color: #007bff;
        font-weight: bold;
    }
</style>
@section('content')
    <div class="subject-manage-content">
        <div class="subject-manage-header">
            <div class="add-subject" id="add-subject">
                <h2>Thêm môn học</h2>
                <form action="{{ route('subject_management.store') }}" method="POST" class="add-subject-form"
                    id="add-subject-form">
                    @csrf
                    <div>
                        <label for="">Tên môn học</label>
                        <input type="text" id="" name="name_subject">
                    </div>
                    <div>
                        <label for="">Số tín chỉ</label>
                        <input type="text" id="" name="credit_subject">
                    </div>
                    <div>
                        <label for="">Học kỳ</label>
                        <input type="text" id="" name="semester_subject">
                    </div>
                    <div>
                        <label for="">Mô tả</label>
                        <input type="text" id="" name="description_subject">
                    </div>
                    <div>
                        <label for="">Tiêu chi kết thúc môn</label>
                        <input type="text" id="" name="criteria_subject">
                    </div>
                    <div class="option-difficulty">
                        <label>Độ khó</label>
                        <section>
                            <select name="difficulty_subject" id="">
                                <option value="Khó">Khó</option>
                                <option value="Trung bình">Trung bình</option>
                                <option value="Dễ">Dễ</option>
                            </select>
                        </section>
                    </div>
                    <button type="submit" id="add-subject-btn">Thêm môn học</button>
                </form>
            </div>
            <!-- <div class="search-subject">
                                <input type="text" placeholder="Tìm kiếm ..." id="searchSubject-input"><i
                                    class="fa-solid fa-magnifying-glass"></i>
                            </div> -->

            <div class="subject-manage-body">
                @if ($danhSachMonHoc->isEmpty())
                    <p>Không có môn học nào trong danh sách.</p>
                @else
                    <table class="subject-manage-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên môn học</th>
                                <th>Số tín chỉ</th>
                                <th>Mô tả</th>
                                <th>Học kỳ</th>
                                <th>Tiêu chí kết thúc môn</th>
                                <th>Độ khó</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($danhSachMonHoc as $index => $monHoc)
                                <tr>
                                    <td class="stt-cell">{{ $index + 1 }}</td>
                                    <td class="subject-name-cell">{{ $monHoc->ten_mon_hoc }}</td>
                                    <td class="subject-score-cell">{{ $monHoc->so_tin_chi }}</td>
                                    <td class="subject-criteria-cell">{{ $monHoc->mo_ta }}</td>
                                    <td class="subject-semester-cell">{{ $monHoc->hoc_ky }}</td>
                                    <td class="subject-criteria-cell">{{ $monHoc->tieu_chi_ket_thuc_mon }}</td>
                                    <td class="subject-diffculty-cell">{{ $monHoc->do_kho }}</td>
                                    <td class="actions-cell">
                                        <form id="delete-form-{{ $monHoc->ma_mon_hoc }}"
                                            action="{{ route('subject_management_del', $monHoc->ma_mon_hoc) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-confirm" data-id="{{ $monHoc->ma_mon_hoc }}">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <button type="button" class="add-subject-btn-fake" id="add-subject-btn-fake">Thêm môn học</button>
            </div>
            <div class="subject-manage-footer">
                <div class="pagination">
                    <a href="{{$danhSachMonHoc->previousPageUrl()}}"><i class="fa-solid fa-chevron-left"></i></a>
                    @if($danhSachMonHoc->currentPage() - 1 != 0) <a
                    href="{{$danhSachMonHoc->previousPageUrl()}}">{{$danhSachMonHoc->currentPage() - 1}}</i></a> @endif
                    <a href="{{$danhSachMonHoc->currentPage()}}" class="active"> {{$danhSachMonHoc->currentPage()}}</a>
                    @if($danhSachMonHoc->currentPage() != $danhSachMonHoc->lastPage())<a
                    href="{{$danhSachMonHoc->nextPageUrl()}}">{{$danhSachMonHoc->currentPage() + 1}}</a> @endif
                    <a href="{{$danhSachMonHoc->nextPageUrl()}}"><i class="fa-solid fa-chevron-right"></i></a>
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
                        event.preventDefault(); // 🚫 Ngăn form submit ngay lập tức

                        const subjectId = this.getAttribute('data-id');

                        Swal.fire({
                            title: 'Bạn có chắc không?',
                            text: "Thao tác này sẽ xoá vĩnh viễn câu hỏi!",
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

                const hienThiThemMonhocBtn = document.getElementById('add-subject-btn-fake');
                const addSubjectForm = document.getElementById('add-subject');

                // Kiểm tra xem các phần tử có tồn tại không trước khi thêm sự kiện
                if (hienThiThemMonhocBtn && addSubjectForm) {
                    hienThiThemMonhocBtn.addEventListener('click', function () {
                        // Kiểm tra trạng thái hiển thị hiện tại của form
                        if (addSubjectForm.style.display === 'none' || addSubjectForm.style.display === '') {
                            // Nếu đang ẩn (hoặc không có thuộc tính display inline nào được đặt), thì hiển thị
                            addSubjectForm.style.display = 'block';
                        } else {
                            // Nếu đang hiển thị, thì ẩn đi
                            addSubjectForm.style.display = 'none';
                        }
                    });
                } else {
                    console.error('Không tìm thấy nút "add-subject-btn-fake" hoặc div "add-subject".');
                }
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

                // Xử lý lỗi validation từ $errors bag (nếu bạn dùng $request->validate() trong controller)
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi nhập liệu!',
                        html: `
                                   <ul>
                                       @foreach ($errors->all() as $error)
                                           <li>{{ $error }}</li>
                                       @endforeach
                                   </ul>
                               `,
                        showConfirmButton: true
                    });
                @endif
                });

        </script>
    @endsection