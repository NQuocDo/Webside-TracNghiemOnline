@extends('layout.lecturer_layout')
<style>
    .announce-content {
        background-color: #ffffff;
        /* Nền trắng cho phần nội dung chính */
        padding: 30px;
        /* Tăng padding để nội dung có không gian thở */
        margin: 40px auto;
        /* Căn giữa và tạo khoảng cách với lề trên/dưới */
        max-width: 800px;
        /* Giới hạn chiều rộng tối đa để dễ đọc */
        border-radius: 10px;
        /* Bo tròn góc hộp nội dung */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        /* Đổ bóng nhẹ nhàng */
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        /* Căn nút sang phải */
        align-items: center;
        /* Căn giữa theo chiều dọc */
        margin-bottom: 25px;
        /* Khoảng cách với form */
    }

    .form-header h2 {
        margin: 0;
        /* Bỏ margin mặc định của h2 */
        font-size: 1.8rem;
        /* Kích thước tiêu đề */
        color: #34495e;
    }

    .btn-secondary {
        background-color: #6c757d;
        /* Màu xám */
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        /* Bỏ gạch chân cho link */
        font-size: 0.95rem;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .announce-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .title-annouce,
    .main-annouce,
    .option-class {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .announce-form label {
        flex-shrink: 0;
        width: 100px;
        font-weight: bold;
        color: #555;
        padding-top: 5px;
    }

    .title-annouce input[type="text"] {
        flex-grow: 1;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .title-annouce input[type="text"]:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .main-annouce input[type="text"] {
        flex-grow: 1;
        height: 180px;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 1rem;
        resize: vertical;
        transition: border-color 0.3s ease;
    }

    .main-annouce input[type="text"]:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .option-class section {
        flex-grow: 1;
    }

    .option-class select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        background-color: #fff;
        font-size: 1rem;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
    }

    .announce-form-btn {
        background-color: #28a745;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        align-self: flex-end;
        margin-top: 10px;
    }

    .announce-form-btn:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    .announce-form-btn:active {
        transform: translateY(0);
    }
</style>
@section('content')
    <div class="announce-content">
        <div class="form-header">
            <h2>Gửi thông báo mới</h2>
            <a href="{{ route('announce_list') }}" class="btn btn-secondary">Danh sách thông báo</a>
        </div>
        <form action="{{ route('announce') }}" method="POST" class="announce-form">
            @csrf
            <div class="title-annouce">
                <label>Tiêu đề:</label>
                <input type="text" name="title-announce" class="input-title-annouce" placeholder="Nhập tiêu đề ...">
            </div>
            <div class="main-annouce">
                <label>Nội dung:</label>
                <input type="text" name="title-content" class="input-title-annouce" placeholder="Nhập nội dung ...">
            </div>
            <div class="option-class">
                <label>Lớp:</label>
                <section>
                    <select name="class" id="class">
                        @if($danhSachLopHoc->isEmpty())
                            <option value="">Không có</option>
                        @else
                            @foreach ($danhSachLopHoc as $lopHoc)
                                <option value="">-- Chọn lớp --</option>
                                <option value="{{ $lopHoc->ma_lop_hoc}}">{{ $lopHoc->ten_lop_hoc}}</option>
                            @endforeach
                        @endif
                    </select>
                </section>
            </div>
            <button type="submit" class="announce-form-btn">Gửi thông báo</button>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Thất bại!',
                text: @json(session('error')),
                confirmButtonText: 'Đóng'
            });
        </script>
    @endif
@endsection