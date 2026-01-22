<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kuesioner Tracer Study - {{ $category->name }}</title>
    <style>
        /* CSS tetap sama */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <!-- Header tetap sama -->

    @foreach ($questionnaires as $questionnaire)
        <div class="section @if (!$loop->first) page-break @endif">
            <div class="section-header">{{ $questionnaire->name }}</div>

            @php $questionNumber = 1; @endphp

            @foreach ($questionnaire->questions()->orderBy('order')->get() as $question)
                @php
                    $answer = $answers[$question->id] ?? null;
                    if (!$answer || $answer->is_skipped) {
                        continue;
                    }

                    // Parse data
                    $answerText = $answer->answer;
                    $selectedOptions = $answer->selected_options;
                    $scaleValue = $answer->scale_value;

                    // Handle selected_options untuk checkbox
                    if ($selectedOptions) {
                        if (is_string($selectedOptions)) {
                            $decoded = json_decode($selectedOptions, true);
                            $selectedOptions = $decoded ?? [$selectedOptions];
                        }
                        if (!is_array($selectedOptions)) {
                            $selectedOptions = [$selectedOptions];
                        }
                    } else {
                        $selectedOptions = [];
                    }
                @endphp

                <div class="question-item">
                    <!-- Tampilkan pertanyaan dengan html_entity_decode -->
                    <div class="question-text">
                        <span class="question-number">{{ $questionNumber++ }}</span>
                        {!! html_entity_decode(nl2br(e($question->question_text))) !!}
                    </div>

                    <div class="answer-content">
                        @switch($question->question_type)
                            @case('likert_scale')
                            @case('competency_scale')
                                @if ($scaleValue)
                                    <div class="answer-text">
                                        <table class="scale-table">
                                            <tr>
                                                <th>Skala Nilai</th>
                                                <th>Keterangan</th>
                                            </tr>
                                            <tr>
                                                <td class="scale-value">{{ $scaleValue }}</td>
                                                <td>
                                                    @if ($question->question_type === 'competency_scale')
                                                        @php
                                                            $competencyLabels = [
                                                                1 => 'Pemula',
                                                                2 => 'Dasar',
                                                                3 => 'Menengah',
                                                                4 => 'Mahir',
                                                                5 => 'Expert',
                                                            ];
                                                            echo $competencyLabels[$scaleValue] ?? 'Tidak tersedia';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $likertLabels = [
                                                                1 => 'Sangat Rendah',
                                                                2 => 'Rendah',
                                                                3 => 'Cukup',
                                                                4 => 'Tinggi',
                                                                5 => 'Sangat Tinggi',
                                                            ];
                                                            echo $likertLabels[$scaleValue] ?? 'Tidak tersedia';
                                                        @endphp
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @else
                                    <div class="answer-text no-answer">Tidak diisi</div>
                                @endif
                            @break

                            @case('checkbox')
                            @case('checkbox_per_row')
                                @if (count($selectedOptions) > 0)
                                    <div class="answer-text">
                                        @foreach ($selectedOptions as $option)
                                            @if (str_contains($option, 'email') || str_contains($option, 'Ya,'))
                                                @php
                                                    $parts = explode(':', $option, 2);
                                                    $mainText = trim($parts[0]);
                                                    $emailValue = isset($parts[1]) ? trim($parts[1]) : '';
                                                @endphp
                                                <div class="answer-badge email">{{ $mainText }}</div>
                                                @if ($emailValue)
                                                    <div class="email-display"><strong>Email:</strong> {{ $emailValue }}</div>
                                                @endif
                                            @elseif(str_contains($option, 'WhatsApp'))
                                                @php
                                                    $parts = explode(':', $option, 2);
                                                    $mainText = trim($parts[0]);
                                                    $waValue = isset($parts[1]) ? trim($parts[1]) : '';
                                                @endphp
                                                <div class="answer-badge whatsapp">{{ $mainText }}</div>
                                                @if ($waValue)
                                                    <div class="email-display"><strong>WhatsApp:</strong> {{ $waValue }}
                                                    </div>
                                                @endif
                                            @elseif(str_contains($option, 'Lainnya'))
                                                @php
                                                    $parts = explode(':', $option, 2);
                                                    $mainText = trim($parts[0]);
                                                    $otherValue = isset($parts[1]) ? trim($parts[1]) : '';
                                                @endphp
                                                <div class="answer-badge other">{{ $mainText }}</div>
                                                @if ($otherValue)
                                                    <div class="email-display"><strong>Keterangan:</strong> {{ $otherValue }}
                                                    </div>
                                                @endif
                                            @else
                                                <div class="answer-badge checkbox">{{ $option }}</div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="answer-text no-answer">Tidak diisi</div>
                                @endif
                            @break

                            @case('likert_per_row')
                                @php
                                    $answerValues = [];
                                    if (is_string($answerText)) {
                                        $answerValues = json_decode($answerText, true) ?? [];
                                    } elseif (is_array($answerText)) {
                                        $answerValues = $answerText;
                                    }

                                    $rowItems = $question->row_items;
                                    if (is_string($rowItems)) {
                                        $rowItems = json_decode($rowItems, true) ?? [];
                                    }
                                @endphp

                                @if (count($answerValues) > 0)
                                    <div class="answer-text">
                                        <table class="scale-table">
                                            <tr>
                                                <th>Item</th>
                                                <th>Skala</th>
                                                <th>Keterangan</th>
                                            </tr>
                                            @foreach ($rowItems as $key => $item)
                                                @if (isset($answerValues[$key]))
                                                    @php
                                                        $itemText = is_array($item) ? $item['text'] ?? $item : $item;
                                                        $scaleVal = $answerValues[$key];
                                                        // Tentukan label berdasarkan pertanyaan
                                                        $isSecondQuestion =
                                                            $questionnaire->is_general && $question->order == 2;
                                                        if ($isSecondQuestion) {
                                                            $scaleLabels = [
                                                                1 => 'Tidak Sama Sekali',
                                                                2 => 'Kurang',
                                                                3 => 'Cukup',
                                                                4 => 'Besar',
                                                                5 => 'Sangat Besar',
                                                            ];
                                                        } else {
                                                            $scaleLabels = [
                                                                1 => 'Sangat Rendah',
                                                                2 => 'Rendah',
                                                                3 => 'Cukup',
                                                                4 => 'Tinggi',
                                                                5 => 'Sangat Tinggi',
                                                            ];
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $itemText }}</td>
                                                        <td class="scale-value">{{ $scaleVal }}</td>
                                                        <td>{{ $scaleLabels[$scaleVal] ?? '' }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    </div>
                                @else
                                    <div class="answer-text no-answer">Tidak diisi</div>
                                @endif
                            @break

                            @case('textarea')
                                @if ($answerText)
                                    <div class="answer-text" style="white-space: pre-line;">{{ $answerText }}</div>
                                @else
                                    <div class="answer-text no-answer">Tidak diisi</div>
                                @endif
                            @break

                            @default
                                @if ($answerText)
                                    <div class="answer-text">{{ $answerText }}</div>
                                @else
                                    <div class="answer-text no-answer">Tidak diisi</div>
                                @endif
                        @endswitch
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>

</html>
