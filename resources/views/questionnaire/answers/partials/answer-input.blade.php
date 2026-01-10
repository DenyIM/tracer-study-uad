@php
    $answerValue = $answer->answer ?? ($answer->selected_options ?? ($answer->scale_value ?? ''));
    $selectedOptions = $answer && $answer->selected_options ? json_decode($answer->selected_options, true) : [];
@endphp

@switch($question->question_type)
    @case('radio')
    @case('radio_per_row')
        <div class="radio-options">
            @foreach ($question->available_options as $option)
                <div class="answer-option {{ $answerValue == $option ? 'selected' : '' }}" data-option="{{ $option }}">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_{{ $question->id }}"
                            id="radio_{{ $question->id }}_{{ $loop->index }}" value="{{ $option }}"
                            {{ $answerValue == $option ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}>
                        <label class="form-check-label fw-medium" for="radio_{{ $question->id }}_{{ $loop->index }}">
                            {{ $option }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    @break

    @case('checkbox')
    @case('checkbox_per_row')
        <div class="checkbox-options">
            @foreach ($question->available_options as $option)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="question_{{ $question->id }}[]"
                        id="checkbox_{{ $question->id }}_{{ $loop->index }}" value="{{ $option }}"
                        {{ in_array($option, $selectedOptions) ? 'checked' : '' }}>
                    <label class="form-check-label" for="checkbox_{{ $question->id }}_{{ $loop->index }}">
                        {{ $option }}
                    </label>
                </div>
            @endforeach
        </div>
    @break

    @case('dropdown')
        <div class="dropdown-option">
            <select class="form-select" name="question_{{ $question->id }}" {{ $question->is_required ? 'required' : '' }}>
                <option value="" disabled {{ !$answerValue ? 'selected' : '' }}>Pilih opsi...</option>
                @foreach ($question->available_options as $option)
                    <option value="{{ $option }}" {{ $answerValue == $option ? 'selected' : '' }}>
                        {{ $option }}
                    </option>
                @endforeach
            </select>
        </div>
    @break

    @case('text')
        <div class="text-option">
            <input type="text" class="form-control" name="question_{{ $question->id }}" value="{{ $answerValue }}"
                placeholder="{{ $question->placeholder ?? 'Tulis jawaban...' }}"
                {{ $question->is_required ? 'required' : '' }}
                {{ $question->max_length ? 'maxlength=' . $question->max_length : '' }}>
        </div>
    @break

    @case('textarea')
        <div class="textarea-option">
            <textarea class="form-control" name="question_{{ $question->id }}" rows="{{ $question->rows ?? 4 }}"
                placeholder="{{ $question->placeholder ?? 'Tulis jawaban...' }}" {{ $question->is_required ? 'required' : '' }}
                {{ $question->max_length ? 'maxlength=' . $question->max_length : '' }}>{{ $answerValue }}</textarea>
        </div>
    @break

    @case('number')
        <div class="number-option">
            <input type="number" class="form-control" name="question_{{ $question->id }}" value="{{ $answerValue }}"
                min="{{ $question->min_value ?? '' }}" max="{{ $question->max_value ?? '' }}"
                placeholder="{{ $question->placeholder ?? 'Masukkan angka...' }}"
                {{ $question->is_required ? 'required' : '' }}>
        </div>
    @break

    @case('date')
        <div class="date-option">
            <input type="date" class="form-control" name="question_{{ $question->id }}" value="{{ $answerValue }}"
                {{ $question->is_required ? 'required' : '' }}>
        </div>
    @break

    @case('likert_scale')
    @case('competency_scale')
        <div class="likert-scale">
            <div class="row justify-content-center">
                @foreach ([1, 2, 3, 4, 5] as $value)
                    <div class="col-auto text-center">
                        <div class="likert-option">
                            <input type="radio" name="question_{{ $question->id }}"
                                id="scale_{{ $question->id }}_{{ $value }}" value="{{ $value }}"
                                {{ $answerValue == $value ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}>
                            <div class="likert-label">{{ $value }}</div>
                            @if ($value == 1 && $question->scale_label_low)
                                <div class="likert-label small">{{ $question->scale_label_low }}</div>
                            @elseif($value == 5 && $question->scale_label_high)
                                <div class="likert-label small">{{ $question->scale_label_high }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @break

    @case('likert_per_row')
        @if ($question->row_items)
            <div class="competency-grid">
                @foreach ($question->row_items as $key => $item)
                    <div class="competency-item">
                        <div class="competency-name">{{ $item['text'] ?? $item }}</div>
                        <div class="competency-scale">
                            @foreach ([1, 2, 3, 4, 5] as $value)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="question_{{ $question->id }}[{{ $key }}]"
                                        id="row_{{ $question->id }}_{{ $key }}_{{ $value }}"
                                        value="{{ $value }}"
                                        {{ isset($answerValue[$key]) && $answerValue[$key] == $value ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="row_{{ $question->id }}_{{ $key }}_{{ $value }}">
                                        {{ $value }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @break

    @default
        <div class="default-option">
            <input type="text" class="form-control" name="question_{{ $question->id }}" value="{{ $answerValue }}"
                placeholder="Jawaban..." {{ $question->is_required ? 'required' : '' }}>
        </div>
@endswitch

@if ($question->has_other_option && in_array($question->question_type, ['radio', 'dropdown', 'checkbox']))
    <div class="other-option mt-3" id="other-option-{{ $question->id }}"
        style="{{ strpos($answerValue ?? '', 'Lainnya:') === 0 ? '' : 'display: none;' }}">
        <label for="other_input_{{ $question->id }}" class="form-label">Sebutkan:</label>
        <input type="text" class="form-control" id="other_input_{{ $question->id }}"
            name="other_{{ $question->id }}"
            value="{{ strpos($answerValue ?? '', 'Lainnya:') === 0 ? substr($answerValue, 9) : '' }}"
            placeholder="Tuliskan lainnya...">
    </div>
@endif
