@extends('layout.student_layout')

@section('content')
    <div class="container mt-4">
        <div class="exam-item-main mt-4">
            <div class="exam-question-body row g-4">
                @foreach ($cauHoiList as $index => $cauHoi)
                    <div class="col-12">
                        <div class="question-content p-4 bg-white shadow-sm rounded border">
                            <strong class="question-count d-block mb-2">Câu hỏi {{ $index + 1 }}:</strong>
                            <div class="question-text mb-3">{{ $cauHoi['noi_dung'] }}</div>

                            @if ($cauHoi['hinh_anh'])
                                <img src="{{ asset('storage/cauhoi/' . $cauHoi['hinh_anh']) }}" alt="Question Image"
                                    class="img-fluid rounded mb-3" style="max-height: 250px;">
                            @endif

                            @if ($cauHoi['ghi_chu'])
                                <div class="alert alert-info small"><i class="fas fa-info-circle"></i> {{ $cauHoi['ghi_chu'] }}
                                </div>
                            @endif

                            <ul class="list-unstyled answer-list">
                                @foreach ($cauHoi->dapAns as $dapAn)
                                    @php
                                        $isCorrect = in_array($dapAn->ma_dap_an, $dapAnDung[$cauHoi->ma_cau_hoi] ?? []);
                                        $isChosen = in_array($dapAn->ma_dap_an, $dapAnChon[$cauHoi->ma_cau_hoi] ?? []);
                                    @endphp

                                    <li class="p-3 mb-2 rounded border d-flex align-items-center" style="
                                                                @if ($isCorrect && $isChosen)
                                                                    background-color: #d4edda; border-color: #28a745;
                                                                @elseif ($isCorrect)
                                                                    background-color: #f0fff4; border-color: #28a745;
                                                                @elseif ($isChosen)
                                                                    background-color: #f8d7da; border-color: #dc3545;
                                                                @endif
                                                            ">
                                        <input type="checkbox" disabled {{ $isChosen ? 'checked' : '' }} class="me-2">
                                        <label class="mb-0 flex-grow-1">
                                            {{ $dapAn->noi_dung }}
                                            @if ($isCorrect)
                                                <span class="text-success fw-bold"> (✓ Đúng)</span>
                                            @elseif ($isChosen)
                                                <span class="text-danger fw-bold"> (✗ Sai)</span>
                                            @endif
                                        </label>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection