<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài kiểm tra năng lực</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
</head>

<style>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: #f5f5f5;
        min-height: 100vh;
    }

    .exam-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        border-bottom: 3px solid #2e7cc4;
    }

    .item-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .exam-item-header img {
        border-radius: 50%;
        border: 3px solid #ffffff;
        width: 45px;
        height: 45px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .exam-item-header img:hover {
        transform: scale(1.1);
    }

    .name {
        color: white;
        font-weight: 600;
        font-size: 16px;
    }

    .timer-container {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .timer-segment {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 18px;
        min-width: 40px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .timer-separator {
        color: white;
        font-size: 20px;
        font-weight: bold;
        margin: 0 5px;
    }

    .item-header-right {
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
    }

    .progress-info {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }

    .exam-submit-btn {
        padding: 10px 20px;
        border-radius: 25px;
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .exam-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    }

    .exam-question-body {
        padding: 20px;
        margin-top: 100px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 25px;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
    }

    .question-content {
        background: #ffffff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
    }

    .question-content:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .question-count {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        display: block;
        padding-bottom: 10px;
        border-bottom: 2px solid #4a90e2;
    }

    .question-text {
        color: #34495e;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .question-src {
        max-width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        margin: 15px 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .question-note {
        color: #7f8c8d;
        font-size: 13px;
        font-style: italic;
        margin-bottom: 20px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #4a90e2;
    }

    .answer-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .answer-list li {
        margin-bottom: 12px;
        padding: 12px 15px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: rgba(248, 249, 250, 0.8);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .answer-list li:hover {
        background: rgba(74, 144, 226, 0.1);
        border-color: #4a90e2;
        transform: translateX(5px);
    }

    .answer-list li.selected {
        background: rgba(74, 144, 226, 0.15);
        border-color: #4a90e2;
        color: #2c3e50;
    }

    .answer-list input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #4a90e2;
        cursor: pointer;
    }

    .answer-list label {
        flex: 1;
        cursor: pointer;
        font-weight: 500;
        color: #2c3e50;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .exam-item-header {
            flex-direction: column;
            gap: 15px;
            padding: 15px;
        }

        .item-header-left,
        .item-header-middle,
        .item-header-right {
            justify-content: center;
        }

        .exam-question-body {
            grid-template-columns: 1fr;
            margin-top: 160px;
            padding: 15px;
        }

        .question-content {
            padding: 20px;
        }

        .timer-segment {
            padding: 6px 10px;
            font-size: 16px;
        }
    }

    @media (max-width: 768px) {
        .exam-question-body {
            margin-top: 180px;
        }

        .question-content {
            padding: 15px;
        }

        .question-count {
            font-size: 16px;
        }

        .question-text {
            font-size: 14px;
        }

        .exam-submit-btn {
            padding: 8px 15px;
            font-size: 12px;
        }
    }

    /* Animation cho việc chọn đáp án */
    .answer-list input[type="checkbox"]:checked+label {
        font-weight: 600;
        color: #357abd;
    }

    /* Hiệu ứng loading cho timer */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .timer-segment {
        animation: pulse 2s infinite;
    }
</style>

<body>
    <div class="exam-content">
        <form action="{{ route('nop_bai') }}" method="POST" id="examForm">
            @csrf
            <input type="hidden" name="bai_lam_json" id="baiLamInput">

            <div class="exam-item-header">
                <div class="item-header-left">
                    <img src="{{ asset('images/studentboy.j') }}" alt="Avatar" class="src">
                    <span class="name">{{ $hoTenSinhVien }}</span>
                </div>
                <div class="item-header-middle">
                    <div class="timer-container">
                        <div class="timer-segment"><span id="minutes-tens">1</span></div>
                        <div class="timer-segment"><span id="minutes-units">5</span></div>
                        <span class="timer-separator">:</span>
                        <div class="timer-segment"><span id="seconds-tens">0</span></div>
                        <div class="timer-segment"><span id="seconds-units">0</span></div>
                    </div>
                </div>
                <div class="item-header-right">
                    <div class="progress-info">
                        <i class="fas fa-check-circle"></i>
                        <span>Đã chọn</span>
                        <strong>0/{{ $deThis->so_luong_cau_hoi }}</strong>
                    </div>
                    <button class="exam-submit-btn" type="submit">
                        <i class="fas fa-paper-plane"></i>
                        Nộp bài
                    </button>
                </div>
            </div>

            <div class="exam-item-main">
                <div class="exam-question-body">
                    @if($baiKiemTra->trang_thai_hien_thi === 'an')
                        <div class="text-center m-2">
                            <h4>Bài kiểm tra đã đóng.</h4>
                            <p>Vui lòng chọn nộp bài để kết thúc.</p>
                            <strong>Xin cảm ơn!</strong>
                        </div>
                    @else
                        @foreach ($cauHoiList as $index => $cauHoi)
                            <div class="question-content">
                                <strong class="question-count">Câu hỏi {{ $index + 1 }}:</strong>
                                <div class="question-text">{{ $cauHoi->noi_dung }}</div>

                                @if ($cauHoi->hinh_anh)
                                    <img src="{{ asset('images/' . $cauHoi->hinh_anh) }}" alt="Question Image" class="question-src">
                                @endif

                                @if ($cauHoi->ghi_chu)
                                    <div class="question-note"><i class="fas fa-info-circle"></i> {{ $cauHoi->ghi_chu }}</div>
                                @endif

                                <ul class="answer-list">
                                    @foreach ($cauHoi->dap_an as $indexDA => $dapAn)
                                        <li>
                                            <input type="checkbox" id="q{{ $index + 1 }}_answer{{ $indexDA + 1 }}"
                                                name="answers[{{ $cauHoi->ma_cau_hoi }}][]" value="{{ $dapAn->ma_dap_an }}">
                                            <label for="q{{ $index + 1 }}_answer{{ $indexDA + 1 }}">{{ $dapAn->noi_dung }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const numberQuestion = {{ $deThis->so_luong_cau_hoi ?? 0 }};
        const maDeThi = "{{ $deThis->ma_de_thi }}";
        const maSinhVien = "{{ $ma_sinh_vien }}";
        const maMonHoc = "{{ $deThis->ma_mon_hoc }}";

        const key = `exam_${maDeThi}_${maSinhVien}`;

        function updateLocalStorage(maCauHoi, maDapAn, isChecked) {
            let data = JSON.parse(localStorage.getItem(key)) || {
                ma_de_thi: maDeThi,
                ma_sinh_vien: maSinhVien,
                ma_mon_hoc: maMonHoc,
                bai_lam: []
            };
            data.ma_de_thi = maDeThi;
            data.ma_sinh_vien = maSinhVien;
            data.ma_mon_hoc = maMonHoc;

            let cauHoi = data.bai_lam.find(item => item.ma_cau_hoi === maCauHoi);
            if (!cauHoi) {
                cauHoi = { ma_cau_hoi: maCauHoi, ma_dap_an: [] };
                data.bai_lam.push(cauHoi);
            }

            if (isChecked) {
                if (!cauHoi.ma_dap_an.includes(maDapAn)) {
                    cauHoi.ma_dap_an.push(maDapAn);
                }
            } else {
                cauHoi.ma_dap_an = cauHoi.ma_dap_an.filter(da => da !== maDapAn);
                if (cauHoi.ma_dap_an.length === 0) {
                    data.bai_lam = data.bai_lam.filter(item => item.ma_cau_hoi !== maCauHoi);
                }
            }

            localStorage.setItem(key, JSON.stringify(data));
        }

        function restoreSelections() {
            const data = JSON.parse(localStorage.getItem(key));
            if (!data || !data.bai_lam) return;

            data.bai_lam.forEach(item => {
                item.ma_dap_an.forEach(dapAn => {
                    const checkbox = document.querySelector(`input[name="answers[${item.ma_cau_hoi}][]"][value="${dapAn}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        checkbox.parentElement.classList.add('selected');
                    }
                });
            });

            updateSelectedCount();
        }

        restoreSelections();

        document.querySelectorAll('.answer-list input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const maCauHoi = this.name.match(/answers\[(.*?)\]/)[1];
                const maDapAn = this.value;
                updateLocalStorage(maCauHoi, maDapAn, this.checked);
                this.parentElement.classList.toggle('selected', this.checked);
                updateSelectedCount();
            });
        });

        function updateSelectedCount() {
            let selectedCount = 0;
            const questions = document.querySelectorAll('.question-content');
            questions.forEach(question => {
                const hasChecked = question.querySelector('input[type="checkbox"]:checked');
                if (hasChecked) selectedCount++;
            });

            const progressInfo = document.querySelector('.progress-info strong');
            if (progressInfo) {
                progressInfo.textContent = `${selectedCount}/${numberQuestion}`;
            }
        }

        const examTime = {{ $deThis->thoi_gian_lam_bai ?? 60 }};
        console.log(examTime);
        let totalSeconds;
        const timeKey = `${key}_time`;
        const savedTime = localStorage.getItem(timeKey);

        if (savedTime !== null && !isNaN(savedTime)) {
            totalSeconds = parseInt(savedTime);
            if (totalSeconds <= 0) {
                Swal.fire('Hết giờ', 'Bài thi sẽ được nộp tự động.', 'info');
                submitExam();
            }
        } else {
            totalSeconds = examTime * 60;
        }

        function updateTimer() {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;

            document.getElementById('minutes-tens').textContent = Math.floor(minutes / 10);
            document.getElementById('minutes-units').textContent = minutes % 10;
            document.getElementById('seconds-tens').textContent = Math.floor(seconds / 10);
            document.getElementById('seconds-units').textContent = seconds % 10;

            localStorage.setItem(timeKey, totalSeconds);
            if (totalSeconds > 0) {
                totalSeconds--;
            } else {
                clearInterval(timerInterval);
                Swal.fire('Hết giờ', 'Bài thi sẽ được nộp tự động.', 'info');
                submitExam();
            }
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();

        document.querySelector('.exam-submit-btn').addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Xác nhận nộp bài?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Nộp ngay',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitExam();
                }
            });
        });

        function submitExam() {
            const data = JSON.parse(localStorage.getItem(key));
            if (!data || !data.bai_lam || data.bai_lam.length === 0) {
                Swal.fire('Lỗi dữ liệu', 'Không có bài làm để nộp!', 'error');
                return;
            }

            data.ma_sinh_vien = maSinhVien;
            data.ma_de_thi = maDeThi;
            data.ma_mon_hoc = maMonHoc;

            document.getElementById('baiLamInput').value = JSON.stringify(data);
            localStorage.removeItem(key);
            localStorage.removeItem(timeKey);
            document.getElementById('examForm').submit();
        }
    </script>

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
</body>


</html>