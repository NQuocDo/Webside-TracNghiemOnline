@extends('layout.lecturer_layout')
@section('title')
    Trang Danh sách đề thi
@endsection
<style>
    .lecturer-exam-list {
        padding: 40px 20px;
        background: #f8f9fa;
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
        color: #6c757d;
        font-size: 1rem;
    }

    .subject-dropdown-container {
        position: relative;
        margin-bottom: 30px;
        display: inline-block;
    }

    .subject-button {
        padding: 10px 20px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: white;
        color: #495057;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .subject-button:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    .subject-list {
        position: absolute;
        top: 110%;
        left: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
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
        color: #495057;
        text-decoration: none;
        transition: 0.2s;
    }

    .subject-list a:hover {
        background: #f8f9fa;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        font-size: 1rem;
    }

    .exam-list-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .exam-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 2px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .exam-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
        text-decoration: none;
        display: block;
        padding-right: 80px;
        /* Để tránh đè lên label */
    }

    .exam-title:hover {
        color: #495057;
    }

    .exam-subject {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 16px;
        font-weight: 500;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid #e9ecef;
    }

    .exam-info {
        font-size: 0.85rem;
        margin-bottom: 8px;
        color: #495057;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-right: 8px;
        border: 1px solid #e9ecef;
    }

    .exam-info span.label {
        color: #6c757d;
        font-weight: 600;
        margin-right: 4px;
    }

    .exam-meta {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 12px;
        margin-bottom: 16px;
        font-style: italic;
    }

    .exam-actions {
        display: flex;
        gap: 8px;
        margin-top: 16px;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
    }

    .delete-btn {
        padding: 8px 16px;
        background: white;
        border: 1px solid #dc3545;
        border-radius: 6px;
        color: #dc3545;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s ease;
        flex: 1;
        min-width: 120px;
    }

    .delete-btn:hover {
        background: #dc3545;
        color: white;
    }

    .create-btn {
        background: white;
        border: 1px solid #28a745;
        color: #28a745;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s ease;
        flex: 1;
        min-width: 120px;
    }

    .create-btn:hover {
        background: #28a745;
        color: white;
    }

    .create-dethi-pdf-btn {
        background: white;
        border: 1px solid #fd7e14;
        color: #fd7e14;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s ease;
        flex: 1;
        min-width: 120px;
    }

    .create-dethi-pdf-btn:hover {
        background: #fd7e14;
        color: white;
    }

    /* ===== BÀI KIỂM TRA SECTION ===== */
    .show-exam {
        margin-top: 24px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        position: relative;
    }

    .show-exam::before {
        content: "BÀI KIỂM TRA";
        position: absolute;
        top: -8px;
        left: 20px;
        background: #6c757d;
        color: white;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }

    .exam-tests strong {
        color: #2c3e50;
        font-size: 1rem;
        display: block;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #dee2e6;
    }

    .no-test {
        color: #6c757d;
        font-style: italic;
        text-align: center;
        padding: 30px;
        font-size: 0.9rem;
        background: white;
        border-radius: 6px;
        border: 1px dashed #dee2e6;
    }

    .test-card-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .test-card {
        background: white;
        border-radius: 8px;
        padding: 16px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .test-card:hover {
        border-color: #6c757d;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .test-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .test-index {
        background: #495057;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        min-width: 30px;
        text-align: center;
    }

    .test-id {
        font-size: 0.85rem;
        color: #2c3e50;
        margin: 0;
        font-weight: 500;
    }

    .test-status {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 12px;
        padding: 4px 8px;
        background: #f8f9fa;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid #e9ecef;
    }

    .test-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .toggle-btn {
        padding: 6px 12px;
        background: white;
        border: 1px solid #28a745;
        color: #28a745;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .toggle-btn:hover {
        background: #28a745;
        color: white;
    }

    .detail-btn {
        padding: 6px 12px;
        background: white;
        border: 1px solid #007bff;
        color: #007bff;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .detail-btn:hover {
        background: #007bff;
        color: white;
    }

    .create-baikiemtra-pdf-btn {
        padding: 6px 12px;
        background: white;
        border: 1px solid #fd7e14;
        color: #fd7e14;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .create-baikiemtra-pdf-btn:hover {
        background: #fd7e14;
        color: white;
    }

    .test-actions .delete-btn {
        padding: 6px 12px;
        font-size: 0.75rem;
        flex: initial;
        min-width: auto;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .exam-actions {
            flex-direction: column;
        }

        .test-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .test-actions {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .exam-title {
            font-size: 1.1rem;
        }

        .exam-card {
            padding: 20px;
        }

        .show-exam {
            padding: 16px;
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

                            <div class="exam-actions">
                                <form id="delete-form-{{ $deThi->ma_de_thi }}"
                                    action="{{ route('exam_list_del', $deThi->ma_de_thi) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn btn-delete-test" data-id="{{ $deThi->ma_de_thi }}">
                                        Xóa đề thi
                                    </button>
                                </form>
                                <form action="{{ route('exam_create_store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="ma_de_thi" value="{{ $deThi->ma_de_thi }}">
                                    <button class="create-btn" data-id="{{ $deThi->ma_de_thi }}">Tạo bài kiểm tra</button>
                                </form>
                                <form action="{{ route('export_de_thi_pdf', $deThi->ma_de_thi) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="create-dethi-pdf-btn">
                                        📄 Xuất PDF đề thi
                                    </button>
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
                                                        Trạng thái: <em> {{ $bkt->trang_thai === 'khoa' ? 'Khóa' : 'Mở' }}</em>
                                                    </div>

                                                    <div class="test-actions">
                                                        <form action="{{ route('exam_create_status', $bkt->ma_bai_kiem_tra) }}"
                                                            method="POST" style="display:inline;">
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
                                                        <a href="{{ route('exam_check', ['id' => $bkt->ma_bai_kiem_tra]) }}"
                                                            class="detail-btn">Xem chi tiết</a>
                                                        <form action="{{ route('export_bai_kiem_tra_pdf', $bkt->ma_bai_kiem_tra) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="create-baikiemtra-pdf-btn">
                                                                📄 Xuất PDF bài kiểm tra
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