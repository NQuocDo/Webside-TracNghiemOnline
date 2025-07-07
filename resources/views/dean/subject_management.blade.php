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
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .add-subject-form select {
        flex-grow: 1;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        color: #333;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        background-color: #fff;
        appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%204%205%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M2%200L0%202h4zm0%205L0%203h4z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
    }

    .add-subject-form select:focus {
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

    .subject-manage-body {
        margin-top: 30px;
        overflow-x: auto;
    }

    .subject-manage-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .subject-manage-table thead {
        background-color: #343a40;
        color: #fff;
    }

    .subject-manage-table th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 15px;
        white-space: nowrap;
    }

    .subject-manage-table th:first-child {
        border-top-left-radius: 8px;
    }

    .subject-manage-table th:last-child {
        border-top-right-radius: 8px;
    }

    .subject-manage-table tbody tr {
        background-color: #fff;
        transition: background-color 0.2s ease;
    }

    .subject-manage-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .subject-manage-table tbody tr:hover {
        background-color: #e2e6ea;
    }

    .subject-manage-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .subject-manage-table td:last-child {
        border-right: none;
    }

    .subject-manage-table tbody tr:last-child td {
        border-bottom: none;
    }

    .stt-cell,
    .subject-score-cell,
    .subject-semester-cell,
    .subject-diffculty-cell {
        text-align: center;
        width: 60px;
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
    }

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
    }

    .search-subject {
        margin-bottom: 30px;
        float: inline-end;
        position: relative;
        display: inline-block;
    }

    .search-subject input {
        padding: 8px 15px;
        border-radius: 20px;
        border: 1px solid #ced4da;
        font-size: 15px;
        width: 250px;
    }

    .search-subject i {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .add-subject-btn-fake {
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
    }

    .add-subject-btn-fake:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    .pagination {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 20px;
        padding-bottom: 20px;
    }

    .search-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 16px;
    }

    .search-form {
        display: flex;
        gap: 8px;
        max-width: 300px;
    }

    .search-input {
        padding: 4px 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        flex: 1;
    }

    .search-btn {
        padding: 4px 12px;
        font-size: 14px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .search-btn:hover {
        background-color: #0056b3;
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
    <div class="subject-manage-content">
        <div class="subject-manage-header">
            <div class="add-subject" id="add-subject">
                <h2>Thêm môn học</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $loi)
                            <div>{{ $loi }}</div>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('subject_management.store') }}" method="POST" class="add-subject-form"
                    id="add-subject-form">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit-id">
                    <div>
                        <label for="name_subject">Tên môn học</label>
                        <input type="text" id="name_subject" name="name_subject" value="{{ old('name_subject') }}">
                    </div>
                    <div>
                        <label for="credit_subject">Số tín chỉ</label>
                        <input type="number" id="credit_subject" name="credit_subject" value="{{ old('credit_subject') }}">
                    </div>
                    <div>
                        <label for="semester_subject">Học kỳ</label>
                        <select id="semester_subject" name="semester_subject" required>
                            <option value="" disabled {{ old('semester_subject') == null ? 'selected' : '' }}>-- Chọn học kỳ
                                --</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('semester_subject') == $i ? 'selected' : '' }}>
                                    Học kỳ {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="description_subject">Mô tả</label>
                        <select id="description_subject" name="description_subject">
                            <option value="" disabled {{ old('description_subject') == null ? 'selected' : '' }}>-- Chọn mô tả
                                --</option>
                            <option value="LT" {{ old('description_subject') == 'LT' ? 'selected' : '' }}>Lý thuyết (LT)
                            </option>
                            <option value="TH" {{ old('description_subject') == 'TH' ? 'selected' : '' }}>Thực hành (TH)
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="criteria_subject">Tiêu chí kết thúc môn</label>
                        <input type="text" id="criteria_subject" name="criteria_subject"
                            value="{{ old('criteria_subject') }}">
                    </div>
                    <div class="option-difficulty">
                        <label for="difficulty_subject">Độ khó</label>
                        <section>
                            <select name="difficulty_subject" id="difficulty_subject" required>
                                <option value="" disabled {{ old('difficulty_subject') == null ? 'selected' : '' }}>-- Chọn độ
                                    khó --</option>
                                <option value="Dễ" {{ old('difficulty_subject') == 'Dễ' ? 'selected' : '' }}>Dễ</option>
                                <option value="Trung bình" {{ old('difficulty_subject') == 'Trung bình' ? 'selected' : '' }}>
                                    Trung bình</option>
                                <option value="Khó" {{ old('difficulty_subject') == 'Khó' ? 'selected' : '' }}>Khó</option>
                            </select>
                        </section>
                        @error('difficulty_subject')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" id="add-subject-btn">Thêm môn học</button>
                </form>

            </div>
            <div class="search-container">
                <form id="form-search-lecturer" action="{{ route('subject_management') }}" method="GET" class="search-form">
                    <input type="text" name="keyword" id="keyword" class="search-input"
                        placeholder="Tìm kiếm tên môn học..." value="{{ request('keyword') }}">
                </form>
            </div>

            <div class="subject-manage-body">
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
                        @if ($danhSachMonHoc->isEmpty())
                            <tr>
                                <td colspan="9" class="text-center">Không có môn học nào trong danh sách.</td>
                            </tr>
                        @else
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
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm edit-subject-btn"
                                            data-id="{{ $monHoc->ma_mon_hoc }}" data-name="{{ $monHoc->ten_mon_hoc }}"
                                            data-credit="{{ $monHoc->so_tin_chi }}" data-semester="{{ $monHoc->hoc_ky }}"
                                            data-description="{{ $monHoc->mo_ta }}"
                                            data-criteria="{{ $monHoc->tieu_chi_ket_thuc_mon }}"
                                            data-difficulty="{{ $monHoc->do_kho }}" title="Sửa môn học">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
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
                        @endif
                    </tbody>
                </table>
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
                        event.preventDefault();

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
                const form = document.getElementById("form-search-lecturer");
                const keywordInput = document.getElementById("keyword");

                if (form && keywordInput) {
                    let debounce;

                    keywordInput.addEventListener("input", function () {
                        clearTimeout(debounce);

                        debounce = setTimeout(() => {
                            form.submit();
                        }, 500);
                    });
                }

                const hienThiThemMonhocBtn = document.getElementById('add-subject-btn-fake');
                const addSubjectForm = document.getElementById('add-subject');


                if (hienThiThemMonhocBtn && addSubjectForm) {
                    hienThiThemMonhocBtn.addEventListener('click', function () {
                        if (addSubjectForm.style.display === 'none' || addSubjectForm.style.display === '') {
                            addSubjectForm.style.display = 'block';
                        } else {
                            addSubjectForm.style.display = 'none';
                        }
                    });
                } else {
                    console.error('Không tìm thấy nút "add-subject-btn-fake" hoặc div "add-subject".');
                }
                document.querySelectorAll('.edit-subject-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        const form = document.getElementById('add-subject-form');
                        const id = button.dataset.id;

                        form.action = `/dean/subject_management/${id}`; // Đúng route update
                        form.querySelector('input[name="name_subject"]').value = button.dataset.name;
                        form.querySelector('input[name="credit_subject"]').value = button.dataset.credit;
                        form.querySelector('input[name="semester_subject"]').value = button.dataset.semester;
                        form.querySelector('input[name="description_subject"]').value = button.dataset.description;
                        form.querySelector('input[name="criteria_subject"]').value = button.dataset.criteria;
                        form.querySelector('select[name="difficulty_subject"]').value = button.dataset.difficulty;

                        document.getElementById('edit-id').value = id;

                        // Thêm input _method nếu chưa có
                        let methodInput = form.querySelector('input[name="_method"]');
                        if (!methodInput) {
                            methodInput = document.createElement('input');
                            methodInput.setAttribute('type', 'hidden');
                            methodInput.setAttribute('name', '_method');
                            methodInput.setAttribute('value', 'PUT');
                            form.appendChild(methodInput);
                        }

                        document.getElementById('add-subject-btn').innerText = 'Cập nhật môn học';

                        const addSubjectForm = document.getElementById('add-subject');
                        if (addSubjectForm.style.display === 'none' || addSubjectForm.style.display === '') {
                            addSubjectForm.style.display = 'block';
                        }
                        form.scrollIntoView({ behavior: 'smooth' });
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