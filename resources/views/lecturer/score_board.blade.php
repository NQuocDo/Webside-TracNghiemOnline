@extends('layout.lecturer_layout')
@section('title', "Bảng điểm sinh viên")
<style>
    .score-table-container {
        padding: 30px;
        margin: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .score-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .score-table thead {
        background: linear-gradient(135deg, #343a40, #495057);
        color: white;
    }

    .score-table th,
    .score-table td {
        padding: 14px 12px;
        text-align: center;
        font-size: 14px;
        border-bottom: 1px solid #dee2e6;
    }

    .score-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
        padding: 6px 14px;
        border-radius: 6px;
        border: none;
        font-size: 13px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .btn-delete:hover {
        background-color: #bd2130;
    }

    .filter-form {
        background-color: #ffffff;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: flex-end;
        /* Đẩy về bên phải */
    }

    .filter-group {
        display: flex;
        gap: 20px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
    }

    .filter-item label {
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .filter-item .form-select {
        min-width: 200px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        background-color: #fff;
    }
</style>

@section('content')
    <div class="score-table-container">
        <h3 style="margin-bottom: 20px;">Danh sách điểm sinh viên</h3>
        <form id="filter-form" method="GET" class="filter-form">
            <div class="filter-group">
                <div class="filter-item">
                    <label for="lop">Lọc theo lớp:</label>
                    <select name="lop" id="lop" class="form-select">
                        <option value="">-- Chọn lớp --</option>
                        @foreach($danhSachLop as $lop)
                            <option value="{{ $lop->ma_lop_hoc }}" {{ request('lop') == $lop->ma_lop_hoc ? 'selected' : '' }}>
                                {{ $lop->ten_lop_hoc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="mon">Lọc theo môn:</label>
                    <select name="mon" id="mon" class="form-select">
                        <option value="">-- Chọn môn --</option>
                        @foreach($danhSachMon as $mon)
                            <option value="{{ $mon->ma_mon_hoc }}" {{ request('mon') == $mon->ma_mon_hoc ? 'selected' : '' }}>
                                {{ $mon->ten_mon_hoc }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>


        <table class="score-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sinh viên</th>
                    <th>Tên bài kiểm tra</th>
                    <th>Điểm số</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @if($bangDiem->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center;">Không có dữ liệu điểm.</td>
                    </tr>
                @else
                    @foreach($bangDiem as $index=> $diem)
                        <tr>
                            <td>{{(int) $index +1 }}</td>
                            <td>{{ $diem->ten_sinh_vien ?? 'N/A' }}</td>
                            <td>{{ $diem->ten_bai_kiem_tra ?? 'N/A' }}</td>
                            <td>{{ $diem->diem_so ?? 'Chưa có điểm' }}</td>
                            <td>
                                @if($diem->ma_bang_diem)
    <form method="POST" action="{{ route('scoreboard_del', $diem->ma_bang_diem) }}" class="form-delete">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete">Xoá</button>
    </form>
@else
    <span style="color: #999;">Không thể xoá</span>
@endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("filter-form");
            const selects = form.querySelectorAll("select");

            selects.forEach(select => {
                select.addEventListener("change", () => form.submit());
            });

            const deleteForms = document.querySelectorAll('.form-delete');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Ngăn form gửi đi ngay

                Swal.fire({
                    title: 'Bạn chắc chắn xoá điểm này?',
                    text: "Hành động này không thể hoàn tác.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        });
    </script>
@endsection