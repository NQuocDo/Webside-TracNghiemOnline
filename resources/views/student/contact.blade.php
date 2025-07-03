@extends('layout.student_layout')
@section('title', 'Trang liên hệ')
<style>
    .contact-container {
        max-width: 700px;
        margin: 40px auto;
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .contact-header {
        background: linear-gradient(135deg, #3498db, #2980b9);
        padding: 20px;
        color: white;
        border-radius: 10px 10px 0 0;
        font-size: 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .contact-header i {
        font-size: 22px;
    }

    .contact-body {
        padding: 25px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-group label.required::after {
        content: " *";
        color: red;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #3498db;
        outline: none;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .contact-footer {
        text-align: right;
    }

    .btn-submit {
        padding: 10px 20px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        cursor: pointer;
    }

    .btn-submit:hover {
        background-color: #2980b9;
    }
</style>
@section('content')
    <div class="contact-container">
        <div class="contact-header">
            <i class="fa-solid fa-envelope"></i> Liên hệ giảng viên / Báo lỗi
        </div>

        <div class="contact-body">
            <form action="{{ route('send_contact') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="errorTitle" class="required">Tiêu đề</label>
                    <input type="text" id="errorTitle" name="errorTitle" placeholder="Nhập tiêu đề..." required>
                </div>

                <div class="form-group">
                    <label for="errorContent" class="required">Nội dung</label>
                    <textarea id="errorContent" name="errorContent" placeholder="Mô tả chi tiết..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="teacherEmail" class="required">Email giảng viên</label>
                    <input type="email" id="teacherEmail" name="teacherEmail" placeholder="Email giảng viên.." required>
                </div>

                <div class="contact-footer">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Gửi liên hệ
                    </button>
                </div>
            </form>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
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