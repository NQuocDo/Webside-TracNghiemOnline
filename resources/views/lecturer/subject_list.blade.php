@extends('layout.lecturer_layout')
<style>
    .subject-list-content {
        padding: 20px;
        margin: 20px;
    }

    .subject-list-table {
        width: 100%;
    }

    .subject-list-table tr th {
        border: 1px solid white;
        padding: 10px;
        text-align: center;
        background-color: rgb(31, 43, 62);
        color: #fff;
    }

    .subject-list-table tr td {
        padding: 10px;
        border: 1px solid black;
    }

    .subject-score-cell,
    .subject-semester-cell {
        width: 100px;
        text-align: center;
    }

    .subject-class-cell {
        width: 250px;
    }

    .subject-class-cell {
        width: 250px;
    }

    .subject-class-cell ul li {
        list-style: none;
        float: inline-start;
        margin-bottom: 5px;
    }

    .pagination {
        width: 100%;
        justify-content: center;
    }

    .pagination a {
        color: black;
        margin: auto 10px;
        text-decoration: none;
        padding: 5px;
    }

    .pagination a:hover {
        color: blue;
        box-shadow: 1px 1px 3px rgb(0, 0, 0, 0.5);
    }
</style>
@section('content')
    <div class="subject-list-content">
        <div class="subject-list-body">
            <table class="subject-list-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên môn</th>
                        <th>Học kì</th>
                        <th>Lớp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhSachMonHoc as $index => $monHoc)
                        <tr>
                            <td class="subject-score-cell">{{ $index + 1 }}</td>
                            <td class="subject-name-cell">{{ $monHoc->ten_mon_hoc }}</td>
                            <td class="subject-semester-cell">{{ $monHoc->mo_ta }}</td>
                            @foreach($monHoc->phanQuyenDays as $phanQuyen)
                                <td class="subject-class-cell">{{ $phanQuyen->lopHoc->ten_lop_hoc }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="subject-list-footer" style="margin-top: 15px;">
            <div class="pagination">
                @if ($danhSachMonHoc->onFirstPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-left"></i></a>
                @else
                    <a href="{{ $danhSachMonHoc->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif
                <a href="{{ $danhSachMonHoc->url($danhSachMonHoc->currentPage()) }}" class="active">
                    {{ $danhSachMonHoc->currentPage() }}</a>
                @if ($danhSachMonHoc->onLastPage())
                    <a href="#" class="disabled"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <a href="{{ $danhSachMonHoc->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @endif
            </div>
        </div>
    </div>

@endsection