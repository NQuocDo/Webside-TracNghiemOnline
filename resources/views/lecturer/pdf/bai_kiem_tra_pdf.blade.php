<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xuất bài kiểm tra PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.5; }
        .question { margin-bottom: 20px; }
        .header { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Bài kiểm tra: {{ $baiKiemTra->ten_bai_kiem_tra }}</h2>
        <p>Mã lớp: {{ $baiKiemTra->lopHoc->ten_lop_hoc ?? 'Không rõ lớp' }}</p>
        <p>Thời gian làm bài: {{ $baiKiemTra->deThi->thoi_gian_lam_bai }} phút</p>
    </div>
    <hr>

    @foreach ($baiKiemTra->chiTietCauHoi as $index => $chiTiet)
        @php
            $cauHoi = $chiTiet->chiTietDeThi->cauHoi ?? null;
        @endphp

        @if ($cauHoi)
            <div class="question">
                <strong>Câu {{ $index + 1 }}:</strong> {{ $cauHoi->noi_dung }}
                <ul>
                    @foreach ($cauHoi->dapAns as $dapAn)
                        <li>{{ $dapAn->noi_dung }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach
</body>
</html>
