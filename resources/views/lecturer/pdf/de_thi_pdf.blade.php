<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đề Thi - {{ $deThi->ten_de_thi }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.6;
        }

        .question {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .question img {
            max-width: 300px;
            height: auto;
            display: block;
            margin-top: 5px;
        }

        .question-note {
            font-style: italic;
            color: #555;
        }

        .answer-list {
            list-style: none;
            padding-left: 15px;
        }

        .answer-item {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center;">ĐỀ THI: {{ $deThi->ten_de_thi }}</h2>
    <p><strong>Thời gian làm bài:</strong> {{ $deThi->thoi_gian_lam_bai }} phút</p>
    <hr>

    @foreach ($deThi->cauHoi as $index => $cauHoi)
        <div class="question">
            <strong>Câu {{ $index + 1 }}:</strong> {{ $cauHoi->noi_dung }}

            @if ($cauHoi->hinh_anh)
                <img src="{{ public_path('images/' . $cauHoi->hinh_anh) }}" alt="Hình câu hỏi">
            @endif

            @if ($cauHoi->ghi_chu)
                <div class="question-note">Ghi chú: {{ $cauHoi->ghi_chu }}</div>
            @endif

            <ul class="answer-list">
                @foreach ($cauHoi->dapAns as $dapAn)
                    <li>- {{ $dapAn->noi_dung }}
                        @if ($dapAn->ket_qua_dap_an)
                            (đúng)
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

</body>

</html>