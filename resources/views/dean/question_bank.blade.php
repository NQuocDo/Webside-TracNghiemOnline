@extends('layout.dean')
@section('title')
    Ngân hàng câu hỏi
@endsection
<style>
    .question-bank-content {
        padding: 20px;
        margin: 20px;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .question-bank-header {
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #e0e0e0;
        max-width: 800px;
        background-color: #f8f9fa;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin: 40px auto;

    }

    .header-title h4 {
        text-align: center;
        margin-bottom: 25px;

        font-weight: 600;

        font-size: 24px;
        color: #333;
    }

    .header-content {
        display: flex;
        flex-wrap: wrap;
        gap: 20px 30px;

        justify-content: center;
        align-items: center;

    }

    .header-content div {
        display: flex;
        align-items: center;
        margin: 10px;
    }

    .header-content label {
        min-width: 100px;
        color: #555;
        font-size: 16px;
        font-weight: 500;
        margin-right: 10px;
        white-space: nowrap;
    }

    .header-content select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        color: #333;
        background-color: #fff;
        cursor: pointer;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        min-width: 150px;
        appearance: none;
        -moz-appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%204%205%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M2%200L0%202h4zm0%205L0%203h4z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
    }

    .header-content select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .header-content option {
        min-width: 150px;
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
        width: 280px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .search-subject input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .search-subject i {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }

    .question-bank-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .question-bank-table thead {
        background-color: #343a40;
        color: #fff;
    }

    .question-bank-table th {
        padding: 12px 15px;
        text-align: left;

        font-weight: 600;
        font-size: 15px;
        white-space: nowrap;
    }

    .question-bank-table th:first-child {
        border-top-left-radius: 8px;
    }

    .question-bank-table th:last-child {
        border-top-right-radius: 8px;
    }

    .question-bank-table tbody tr {
        background-color: #fff;
        transition: background-color 0.2s ease;
    }

    .question-bank-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .question-bank-table tbody tr:hover {
        background-color: #e2e6ea;
    }

    .question-bank-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        font-size: 14px;
        color: #333;
        vertical-align: top;
    }

    .question-bank-table td:last-child {
        border-right: none;
    }

    .question-bank-table tbody tr:last-child td {
        border-bottom: none;
    }

    .stt-cell,
    .actions-cell {
        text-align: center;
        width: 60px;
    }

    .actions-cell {
        width: 120px;
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

    .edit-btn {
        background-color: #007bff;
        color: white;
    }

    .edit-btn:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
    }

    .delete-btn:hover {
        background-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .actions-cell button i {
        margin-right: 5px;
    }

    .answers-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 15px;
        align-items: flex-start;
        margin-top: 5px;
    }

    .question-text {
        margin-bottom: 5px;
        font-weight: 500;
        color: #333;
        line-height: 1.5;
    }

    .answer-item {
        font-size: 0.85em;
        color: #495057;
        background-color: #e9ecef;
        padding: 5px 10px;
        /* Giảm padding */
        border-radius: 6px;
        /* Bo tròn ít hơn */
        border: 1px solid #dee2e6;
        /* Viền nhẹ */
        white-space: normal;
        /* Cho phép đáp án xuống dòng */
        word-break: break-word;
        /* Ngắt từ nếu quá dài */
        max-width: 48%;
        /* Giới hạn độ rộng để wrap tốt hơn */
    }

    /* Đáp án đúng (Ví dụ: màu xanh lá) */
    .answer-item.correct {
        background-color: #d4edda;
        /* Nền xanh lá nhạt */
        color: #155724;
        /* Chữ xanh lá đậm */
        border-color: #c3e6cb;
        font-weight: 500;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 6px;
        background-color: #f2f2f2;
        text-decoration: none;
        color: #333;
        transition: 0.3s ease;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    .pagination a:hover:not(.disabled):not(.active) {
        background-color: #ddd;
    }

    .pagination a.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
</style>
@section('content')
    <div class="question-bank-content">
        <div class="question-bank-header">
            <div class="header-title">
                <h4>Danh sách câu hỏi</h4>
            </div>
            <div class="header-content">
                <form action="{{ route('question_bank') }}" method="GET" id="filler-question" class="filler-question">
                    <div>
                        <section>
                            <label for="giang_vien_select">Chọn giảng viên</label>
                            <select name="ma_giang_vien" id="giang_vien_select">
                                <option value="">Tất cả giảng viên</option>
                                @foreach($danhSachGiangVien as $giangVien)
                                    <option value="{{ $giangVien->giangVien->ma_giang_vien }}" {{ request('ma_giang_vien') == $giangVien->giangVien->ma_giang_vien ? 'selected' : '' }}>
                                        {{ $giangVien->ho_ten }}
                                    </option>
                                @endforeach
                            </select>
                        </section>
                    </div>
                    <div>
                        <section>
                            <label for="mon_hoc_select">Chọn môn học</label>
                            <select name="ma_mon_hoc" id="mon_hoc_select">
                                <option value="" {{ request('ma_mon_hoc') === null || request('ma_mon_hoc') === '' ? 'selected' : '' }}>
                                    Tất cả môn học
                                </option>
                                @foreach($danhSachMonHoc as $monHoc)
                                    <option value="{{ $monHoc->ma_mon_hoc }}" {{ request('ma_mon_hoc') === $monHoc->ma_mon_hoc ? 'selected' : '' }}>
                                        {{ $monHoc->ten_mon_hoc }}
                                    </option>
                                @endforeach
                            </select>
                        </section>
                    </div>
                    <div class="search-subject">
                        <input type="text" name="search" id="search-input" placeholder="Tìm kiếm..."
                            value="{{ request('search') }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </form>
            </div>
        </div>

        <div class="question-bank-body">
            <table class="question-bank-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Nội dung Câu hỏi</th>
                        <th>Hình ảnh</th>
                        <th>Môn</th>
                        <th>Giảng viên</th>
                    </tr>
                </thead>
                <tbody>
                    @if($danhSachCauHoi->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có câu hỏi nào được tìm thấy.</td>
                        </tr>
                    @else
                        @foreach($danhSachCauHoi as $index => $cauHoi)
                            <tr>
                                <td>
                                    {{ ($danhSachCauHoi->currentPage() - 1) * $danhSachCauHoi->perPage() + $index + 1 }}
                                </td>
                                <td>
                                    <div class="question-text">{{ $cauHoi->noi_dung }}</div>
                                    @foreach ($cauHoi->dapAns as $dapAn)
                                        <div class="answers-container">
                                            <div class="answer-item">{{ $dapAn->noi_dung }}</div>
                                            @if($dapAn->ket_qua_dap_an == 1)
                                                <i class="fas fa-check-circle text-success ms-1" title="Đáp án đúng"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger ms-1" title="Đáp án sai"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </td>
                                <td style="text-align: center">
                                    @if($cauHoi->hinh_anh)
                                        <img src="{{ asset('images/' . $cauHoi->hinh_anh) }}" width="70" height="70"
                                            alt="Ảnh nhỏ" class="thumb-img" data-id="{{ $cauHoi->ma_cau_hoi }}">
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>{{ $cauHoi->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                                <td>{{ $cauHoi->giangVien->nguoiDung->ho_ten ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="pagination-wrapper">
            <div class="pagination">
                @if ($danhSachCauHoi->onFirstPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-left"></i></a>
                @else
                    <a href="{{ $danhSachCauHoi->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                <a href="{{ $danhSachCauHoi->url($danhSachCauHoi->currentPage()) }}" class="active">
                    {{ $danhSachCauHoi->currentPage() }}</a>

                @if ($danhSachCauHoi->onLastPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <a href="{{ $danhSachCauHoi->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById('filler-question');
            const giangViensSelect = document.getElementById('giang_vien_select');
            const monHocSelect = document.getElementById('mon_hoc_select');
            const searchInput = document.getElementById('search-input');

            if (monHocSelect) {
                monHocSelect.addEventListener("change", function () {
                    form.submit();
                });
            }
            if (giangViensSelect) {
                giangViensSelect.addEventListener("change", function () {
                    form.submit();
                });
            }
            if (searchInput) {
                let debounceTimeout;
                searchInput.addEventListener("input", function () {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        form.submit();
                    }, 500);
                });
            }
        });
    </script>
@endsection