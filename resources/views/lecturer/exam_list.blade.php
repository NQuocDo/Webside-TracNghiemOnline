@extends('layout.lecturer_layout')
<style>
    .lecturer-exam-list {
        padding: 40px 20px;
        background: #f5f7fa;
        min-height: 100vh;
    }

    .exam-list-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 2.2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .page-subtitle {
        color: #636e72;
        font-size: 1rem;
    }

    .subject-dropdown-container {
        position: relative;
        margin-bottom: 30px;
        display: inline-block;
    }

    .subject-button {
        padding: 10px 20px;
        border: none;
        border-radius: 25px;
        background: #0984e3;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .subject-button:hover {
        background: #74b9ff;
    }

    .subject-list {
        position: absolute;
        top: 110%;
        left: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 999;
        min-width: 180px;
    }

    .subject-dropdown-container:hover .subject-list {
        display: block;
    }

    .subject-list li {
        list-style: none;
    }

    .subject-list a {
        display: block;
        padding: 12px 16px;
        color: #2d3436;
        text-decoration: none;
        transition: 0.2s;
    }

    .subject-list a:hover {
        background: #f1f2f6;
    }

    /* Main container với 2 cột dọc */
    .exam-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        align-items: start;
        height: calc(100vh - 200px);
    }

    /* Cột trái - Danh sách đề thi */
    .exam-list-column {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
        height: 100%;
        overflow-y: auto;
    }

    .column-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
        padding-bottom: 12px;
        border-bottom: 2px solid #0984e3;
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }

    .exam-list-grid {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .exam-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 18px;
        border: 1px solid #e9ecef;
        transition: 0.3s ease;
    }

    .exam-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .exam-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 8px;
        text-decoration: none;
        display: block;
    }

    .exam-title:hover {
        color: #0984e3;
    }

    .exam-subject {
        font-size: 0.9rem;
        color: #636e72;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .exam-info {
        font-size: 0.85rem;
        margin-bottom: 6px;
        color: #2d3436;
    }

    .exam-info span.label {
        color: #6c757d;
        font-weight: 600;
        margin-right: 6px;
    }

    .exam-meta {
        font-size: 0.8rem;
        color: #8395a7;
        margin-top: 10px;
        margin-bottom: 12px;
    }

    .exam-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .delete-btn {
        padding: 6px 12px;
        background: #ff6b6b;
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 0.8rem;
        cursor: pointer;
        transition: 0.2s ease;
        flex: 1;
    }

    .delete-btn:hover {
        background: #ff4757;
    }

    .create-btn {
        background-color: #0984e3;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 0.8rem;
        cursor: pointer;
        transition: 0.2s ease;
        flex: 1;
    }

    .create-btn:hover {
        background-color: #0770c4;
    }

    /* Cột phải - Danh sách bài kiểm tra */
    .exam-tests-column {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
        height: 100%;
        overflow-y: auto;
    }

    .exam-tests-column .column-title {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        margin-bottom: 20px;
    }

    .test-section {
        margin-bottom: 20px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #0984e3;
    }

    .test-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .no-test {
        color: #6c757d;
        font-style: italic;
        text-align: center;
        padding: 20px;
    }

    .test-card-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .test-card {
        background: white;
        border-radius: 8px;
        padding: 14px;
        border: 1px solid #dee2e6;
        transition: 0.2s ease;
    }

    .test-card:hover {
        border-color: #0984e3;
        box-shadow: 0 2px 8px rgba(9, 132, 227, 0.1);
    }

    .test-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }

    .test-index {
        background: #0984e3;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .test-id {
        font-size: 0.85rem;
        color: #495057;
        margin: 0;
    }

    .test-status {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .test-actions {
        display: flex;
        gap: 6px;
    }

    .toggle-btn {
        padding: 4px 8px;
        background: #28a745;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 0.75rem;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .toggle-btn:hover {
        background: #218838;
    }

    .test-actions .delete-btn {
        padding: 4px 8px;
        font-size: 0.75rem;
        flex: initial;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #636e72;
        font-size: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .exam-container {
            grid-template-columns: 1fr;
            gap: 20px;
            height: auto;
        }

        .exam-list-column,
        .exam-tests-column {
            height: auto;
            max-height: 500px;
        }

        .exam-actions {
            flex-direction: column;
        }

        .test-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .exam-title {
            font-size: 1rem;
        }

        .column-title {
            font-size: 1.2rem;
        }
    }
</style>

@section('content')
    <div class="lecturer-exam-list">
        <div class="exam-list-content">
            <div class="page-header">
                <h1 class="page-title">Danh sách đề thi</h1>
                <p class="page-subtitle">Lựa chọn và quản lý đề thi của bạn</p>
            </div>

            <div class="subject-dropdown-container">
                <button class="subject-button">Chọn môn ▼</button>
                <ul class="subject-list">
                    @foreach ($danhSachMonHoc as $mon)
                        <li>
                            <a href="{{ route('exam_list', ['ma_mon_hoc' => $mon->ma_mon_hoc]) }}">
                                {{ $mon->ten_mon_hoc }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($danhSachDeThi->isEmpty())
                <div class="empty-state">Chưa có đề thi nào được tạo</div>
            @else
                <div class="exam-list-grid">
                    @foreach ($danhSachDeThi as $deThi)
                        <div class="exam-card">
                            <a href="{{ route('exam_list_detail', $deThi->ma_de_thi) }}"
                                class="exam-title">{{ $deThi->ten_de_thi }}</a>
                            <div class="exam-subject">{{ $deThi->monHoc->ten_mon_hoc ?? 'Không rõ môn học' }}</div>

                            <div class="exam-info">
                                <span class="label">📝 Số câu hỏi:</span>{{ $deThi->so_luong_cau_hoi }}
                            </div>

                            <div class="exam-info">
                                <span class="label">⏱ Thời gian:</span>{{ $deThi->thoi_gian_lam_bai }} phút
                            </div>

                            <div class="exam-meta">
                                📅 Tạo lúc: {{ $deThi->ngay_tao_de_thi }}
                            </div>

                            <form id="delete-form-{{ $deThi->ma_de_thi }}" action="{{ route('exam_list_del', $deThi->ma_de_thi) }}"
                                method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn btn-delete-test" data-id="{{ $deThi->ma_de_thi }}">
                                    Xóa đề thi
                                </button>
                            </form>
                            <form action="{{ route('exam_create_store') }}" method="POST" id="create-form-{{ $deThi->ma_de_thi }}"
                                style="margin-top: 10px;">
                                @csrf
                                <input type="hidden" name="ma_de_thi" value="{{ $deThi->ma_de_thi }}">
                                <button class="create-btn" data-id="{{ $deThi->ma_de_thi }}">Tạo bài kiểm tra</button>
                            </form>
                        </div>

                        <div class="show-exam">
                            @if($deThi->baiKiemTras->isEmpty())
                                <p class="no-test">🔕 Chưa có bài kiểm tra của {{ $deThi->ten_de_thi }}</p>
                            @else
                                <div class="exam-tests">
                                    <strong>📋 {{ $deThi->ten_de_thi }}:</strong>
                                    <div class="test-card-container">
                                        @foreach ($deThi->baiKiemTras as $index => $bkt)
                                            <div class="test-card">
                                                <div class="test-header">
                                                    <span class="test-index">#{{ $index + 1 }}</span>
                                                    <span class="test-id">Tên: <strong>{{ $bkt->ten_bai_kiem_tra }}</strong></span>
                                                    <p class="test-id">Tên:
                                                        <strong>{{ $bkt->lopHoc->ten_lop_hoc ?? 'Không rõ lớp' }}</strong>
                                                    </p>
                                                </div>

                                                <div class="test-status">
                                                    Trạng thái: <em>{{ ucfirst($bkt->trang_thai) }}</em>
                                                </div>

                                                <div class="test-actions">
                                                    <form action="{{ route('exam_create_status', $bkt->ma_bai_kiem_tra) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="toggle-btn">
                                                            {{ $bkt->trang_thai === 'khoa' ? 'Mở' : 'Khoá' }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('exam_create_del', $bkt->ma_bai_kiem_tra) }}" method="POST"
                                                        id="delete-form-{{$bkt->ma_bai_kiem_tra}}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-btn btn-delete-test"
                                                            data-id="{{ $bkt->ma_bai_kiem_tra }}">
                                                            Xoá
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete-test');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const examtId = this.getAttribute('data-id');

                    Swal.fire({
                        title: '⚠️ Bạn chắc chắn muốn xoá đề thi này?',
                        html: "<b>Tất cả các bài kiểm tra liên quan sẽ bị xoá!</b><br>Hãy đảm bảo bạn đã kiểm tra kỹ trước khi thực hiện.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xoá ngay',
                        cancelButtonText: 'Huỷ bỏ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + examtId).submit();
                        }
                    });
                });
            });

            const buttons = document.querySelectorAll('.create-btn');
            const lopHocs = @json($lopHocs);
            let options = lopHocs.map(lop => `<option value="${lop.ma_lop_hoc}">${lop.ten_lop_hoc}</option>`).join('');

            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const examCreateId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Tạo bài kiểm tra',
                        html: `
                                            <input id="ten-bai-kiem-tra" class="swal2-input" placeholder="Nhập tên bài kiểm tra">
                                            <select id="chon-lop" class="swal2-input">
                                                <option value="" disabled selected>Chọn lớp học</option>
                                                ${options}
                                            </select>
                                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Tạo',
                        cancelButtonText: 'Huỷ',
                        focusConfirm: false,
                        preConfirm: () => {
                            const tenBKT = document.getElementById('ten-bai-kiem-tra').value;
                            const maLop = document.getElementById('chon-lop').value;

                            if (!tenBKT || !maLop) {
                                Swal.showValidationMessage('Vui lòng nhập tên bài kiểm tra và chọn lớp học');
                                return false;
                            }

                            return { tenBKT, maLop };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('create-form-' + examCreateId);

                            const inputTen = document.createElement('input');
                            inputTen.type = 'hidden';
                            inputTen.name = 'ten_bai_kiem_tra';
                            inputTen.value = result.value.tenBKT;

                            const inputLop = document.createElement('input');
                            inputLop.type = 'hidden';
                            inputLop.name = 'ma_lop_hoc';
                            inputLop.value = result.value.maLop;

                            form.appendChild(inputTen);
                            form.appendChild(inputLop);
                            form.submit();
                        }
                    });
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
                    icon: 'info',
                    title: 'Không thể thực hiện',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            @endif
                                              });
    </script>
@endsection