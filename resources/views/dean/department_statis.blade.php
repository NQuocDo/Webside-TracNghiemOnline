@extends('layout.dean')

@section('title')
    Thống kê kết quả
@endsection

<style>
    .department-statis-content {
        padding: 20px;
        margin: 20px;
    }

    .department-statis-header {
        border-radius: 20px;
        border: 1px solid black;
        max-width: 350px;
        margin: 40px auto;
        padding: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .department-statis-header .department-statis-header-title {
        text-align: center;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .chart-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 30px;
        margin-bottom: 30px;
        width: calc(100% - 40px);
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }

    .chart-container h2 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    canvas {
        max-width: 100%;
        height: 300px !important;
        display: block;
    }
</style>

@section('content')
    <div class="department-statis-content">
        <!-- <div class="department-statis-header">
                    <div class="department-statis-header-title">Thống kê</div>
                    <div class="department-statis-header-body">
                        <form action="" method="" class="filler-statis-form">
                            <section>
                                <label for="">Chọn loại thống kê</label>
                                <select name="" id="">
                                    <option value="">Điểm trung bình khoa</option>
                                    <option value="">Loại sinh viên</option>
                                </select>
                            </section>
                        </form>
                    </div>
                </div> -->
        <div class="department-statis-body">
            <div class="chart-container">
                <h2>Thống kê Điểm Trung Bình Các Môn Học</h2>
                <canvas id="averageScoresChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Thống kế Ngân hàng Câu hỏi</h2>
                <canvas id="giangVienCauHoiChart" height="400"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const diemData = @json($thongKeDiem);

            const labels = diemData.map(item => item.ten_mon_hoc);
            const data = diemData.map(item => parseFloat(item.diem_tb));
            const ctxAvg = document.getElementById('averageScoresChart').getContext('2d');

            new Chart(ctxAvg, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Điểm Trung Bình',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10,
                            title: {
                                display: true,
                                text: 'Điểm'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Môn học'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const cauHoiData = @json($thongKeCauHoi);

            const labelsGV = cauHoiData.map(item => item.ho_ten);
            const dataGV = cauHoiData.map(item => parseInt(item.tong_so_cau_hoi));

            const ctxGV = document.getElementById('giangVienCauHoiChart').getContext('2d');

            new Chart(ctxGV, {
                type: 'bar',
                data: {
                    labels: labelsGV,
                    datasets: [{
                        label: 'Số câu hỏi đã thêm',
                        data: dataGV,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số câu hỏi'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Giảng viên'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
        const cauHoiData = @json($thongKeCauHoi);

        const labelsGV = cauHoiData.map(item => item.ho_ten);
        const dataGV = cauHoiData.map(item => parseInt(item.so_cau_hoi));

        const ctxGV = document.getElementById('giangVienCauHoiChart').getContext('2d');

        new Chart(ctxGV, {
            type: 'bar',
            data: {
                labels: labelsGV,
                datasets: [{
                    label: 'Số câu hỏi đã thêm',
                    data: dataGV,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // ✅ DÙNG CÁI NÀY nếu muốn biểu đồ CỘT NGANG (Chart.js v3+)
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số câu hỏi'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Giảng viên'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection