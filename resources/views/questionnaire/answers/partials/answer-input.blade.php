{{-- resources/views/questionnaire/answers/partials/answer-input.blade.php --}}
@php
    // Helper untuk mengambil data dari database
    $scaleLabelLow = $question->scale_label_low ?? 'Sangat Rendah';
    $scaleLabelHigh = $question->scale_label_high ?? 'Sangat Tinggi';

    // Ambil scale options
    $scaleOptions = [];
    if ($question->scale_options) {
        if (is_string($question->scale_options)) {
            $scaleOptions = json_decode($question->scale_options, true) ?? [1, 2, 3, 4, 5];
        } else {
            $scaleOptions = $question->scale_options;
        }
    } else {
        $scaleOptions = [1, 2, 3, 4, 5];
    }

    // Ambil jawaban saat ini
    $answerValue = $answer->answer ?? ($answer->selected_options ?? ($answer->scale_value ?? ''));
    $selectedOptions = $answer && $answer->selected_options ? json_decode($answer->selected_options, true) : [];

    // Parse row items
    $rowItems = [];
    if ($question->row_items) {
        if (is_string($question->row_items)) {
            $rowItems = json_decode($question->row_items, true) ?? [];
        } else {
            $rowItems = $question->row_items;
        }
    }

    // Batasan validasi
    $validationAttributes = [
        'max_length' => $question->max_length ?? null,
        'min_value' => $question->min_value ?? null,
        'max_value' => $question->max_value ?? null,
        'max_selections' => $question->max_selections ?? null,
        'rows' => $question->rows ?? 4,
    ];
@endphp

@switch($question->question_type)
    {{-- Tipe Pertanyaan Teks --}}
    @case('text')
        <div class="text-option">
            <input type="text" class="form-control text-answer-input" name="answers[{{ $question->id }}]"
                value="{{ $answerValue ?? '' }}" placeholder="{{ $question->placeholder ?? 'Tulis jawaban...' }}"
                {{ $question->is_required ? 'required' : '' }}
                @if ($validationAttributes['max_length']) maxlength="{{ $validationAttributes['max_length'] }}"
                    data-max-length="{{ $validationAttributes['max_length'] }}" @endif
                data-question-id="{{ $question->id }}" data-question-type="text">

            @if ($validationAttributes['max_length'])
                <div class="form-text text-muted small mt-1">
                    <span id="char-counter-{{ $question->id }}">0</span>/{{ $validationAttributes['max_length'] }} karakter
                </div>
            @endif
        </div>
    @break

    {{-- Tipe Pertanyaan Textarea --}}
    @case('textarea')
        <div class="textarea-option">
            <textarea class="form-control textarea-answer-input" name="answers[{{ $question->id }}]"
                rows="{{ $validationAttributes['rows'] }}" placeholder="{{ $question->placeholder ?? 'Tulis jawaban...' }}"
                {{ $question->is_required ? 'required' : '' }}
                @if ($validationAttributes['max_length']) maxlength="{{ $validationAttributes['max_length'] }}"
                    data-max-length="{{ $validationAttributes['max_length'] }}" @endif
                data-question-id="{{ $question->id }}" data-question-type="textarea">{{ $answerValue ?? '' }}</textarea>

            @if ($validationAttributes['max_length'])
                <div class="form-text text-muted small mt-1">
                    <span id="char-counter-{{ $question->id }}">0</span>/{{ $validationAttributes['max_length'] }} karakter
                </div>
            @endif
        </div>
    @break

    {{-- Tipe Pertanyaan Number --}}
    @case('number')
        <div class="number-option">
            <input type="number" class="form-control number-answer-input" name="answers[{{ $question->id }}]"
                value="{{ $answerValue ?? '' }}"
                @if ($validationAttributes['min_value'] !== null) min="{{ $validationAttributes['min_value'] }}" @endif
                @if ($validationAttributes['max_value'] !== null) max="{{ $validationAttributes['max_value'] }}" @endif
                placeholder="{{ $question->placeholder ?? 'Masukkan angka...' }}"
                {{ $question->is_required ? 'required' : '' }} data-question-id="{{ $question->id }}"
                data-question-type="number">

            @if ($validationAttributes['min_value'] !== null || $validationAttributes['max_value'] !== null)
                <div class="form-text text-muted small mt-1">
                    @if ($validationAttributes['min_value'] !== null && $validationAttributes['max_value'] !== null)
                        Rentang: {{ $validationAttributes['min_value'] }} - {{ $validationAttributes['max_value'] }}
                    @elseif($validationAttributes['min_value'] !== null)
                        Minimal: {{ $validationAttributes['min_value'] }}
                    @elseif($validationAttributes['max_value'] !== null)
                        Maksimal: {{ $validationAttributes['max_value'] }}
                    @endif
                </div>
            @endif
        </div>
    @break

    {{-- Tipe Pertanyaan Date --}}
    @case('date')
        <div class="date-option">
            <input type="date" class="form-control date-answer-input" name="answers[{{ $question->id }}]"
                value="{{ $answerValue ?? '' }}" {{ $question->is_required ? 'required' : '' }}>
        </div>
    @break

    {{-- Tipe Pertanyaan Radio --}}
    @case('radio')
    @case('radio_per_row')
        <div class="radio-options">
            @if (is_array($question->available_options) && count($question->available_options) > 0)
                @foreach ($question->available_options as $index => $option)
                    @php
                        $optionText = is_array($option) && isset($option['text']) ? $option['text'] : $option;
                        $isSelected = is_string($answerValue) && strpos($answerValue, $optionText) === 0;

                        // PERBAIKAN: Deteksi yang lebih spesifik untuk "Lainnya"
                        $isOtherOption =
                            $optionText === 'Lainnya, sebutkan!' ||
                            $optionText === 'Lainnya' ||
                            strpos($optionText, 'Lainnya') === 0;
                        $hasOtherInput = $isOtherOption;

                        $hasEmailInput =
                            stripos($optionText, 'email') !== false ||
                            (stripos($optionText, 'Ya,') !== false && stripos($optionText, 'email') !== false);
                        $hasWhatsappInput =
                            stripos($optionText, 'WhatsApp') !== false ||
                            stripos($optionText, 'nomor WhatsApp') !== false ||
                            stripos($optionText, 'nomor WA') !== false;

                        $emailValue = '';
                        $whatsappValue = '';
                        $otherValue = '';

                        if ($answerValue && stripos($answerValue, $optionText) === 0) {
                            // Extract additional value from answer (format: "Option: value")
                            if (strpos($answerValue, ':') !== false) {
                                $parts = explode(':', $answerValue, 2);
                                $additionalValue = trim($parts[1]);
                                if ($hasEmailInput) {
                                    $emailValue = $additionalValue;
                                } elseif ($hasWhatsappInput) {
                                    $whatsappValue = $additionalValue;
                                } elseif ($hasOtherInput) {
                                    $otherValue = $additionalValue;
                                }
                            }
                        }
                    @endphp

                    <div class="answer-option {{ $isSelected ? 'selected' : '' }}" data-option="{{ $optionText }}"
                        data-has-email="{{ $hasEmailInput ? 'true' : 'false' }}"
                        data-has-whatsapp="{{ $hasWhatsappInput ? 'true' : 'false' }}"
                        data-has-other="{{ $hasOtherInput ? 'true' : 'false' }}">

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                id="radio_{{ $question->id }}_{{ $index }}" value="{{ $optionText }}"
                                {{ $isSelected ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}>
                            <label class="form-check-label fw-medium" for="radio_{{ $question->id }}_{{ $index }}">
                                {{ $optionText }}
                            </label>
                        </div>

                        {{-- Input Email --}}
                        @if ($hasEmailInput && !$hasOtherInput)
                            {{-- Jangan tampilkan email jika ini opsi Lainnya --}}
                            <div class="email-input-container mt-2 {{ $isSelected ? 'show' : '' }}">
                                <label for="email_{{ $question->id }}_{{ $index }}" class="form-label small">
                                    <i class="fas fa-envelope me-1"></i> Masukkan email:
                                </label>
                                <input type="email" class="form-control form-control-sm email-input"
                                    id="email_{{ $question->id }}_{{ $index }}" placeholder="contoh@email.com"
                                    value="{{ $emailValue }}" style="max-width: 300px;">
                            </div>
                        @endif

                        {{-- Input WhatsApp --}}
                        @if ($hasWhatsappInput && !$hasOtherInput)
                            {{-- Jangan tampilkan WhatsApp jika ini opsi Lainnya --}}
                            <div class="whatsapp-input-container mt-2 {{ $isSelected ? 'show' : '' }}">
                                <label for="whatsapp_{{ $question->id }}_{{ $index }}" class="form-label small">
                                    <i class="fab fa-whatsapp me-1"></i> Masukkan nomor WhatsApp:
                                </label>
                                <input type="text" class="form-control form-control-sm whatsapp-input"
                                    id="whatsapp_{{ $question->id }}_{{ $index }}" placeholder="081234567890"
                                    value="{{ $whatsappValue }}" style="max-width: 300px;">
                            </div>
                        @endif

                        {{-- Input Lainnya --}}
                        @if ($hasOtherInput)
                            <div class="other-input-container mt-2 {{ $isSelected ? 'show' : '' }}">
                                <label for="other_{{ $question->id }}_{{ $index }}" class="form-label small">
                                    <i class="fas fa-edit me-1"></i> Sebutkan:
                                </label>
                                <input type="text" class="form-control form-control-sm other-input"
                                    id="other_{{ $question->id }}_{{ $index }}" placeholder="Tuliskan lainnya..."
                                    value="{{ $otherValue }}" style="max-width: 300px;">
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-muted">Tidak ada opsi tersedia</p>
            @endif
        </div>
    @break

    {{-- Tipe Pertanyaan Dropdown --}}
    @case('dropdown')
        <div class="dropdown-option">
            <select class="form-select dropdown-answer-select" name="answers[{{ $question->id }}]"
                id="dropdown_{{ $question->id }}" {{ $question->is_required ? 'required' : '' }}>
                <option value="" disabled {{ !$answerValue ? 'selected' : '' }}>Pilih opsi...</option>

                @php
                    $hasOtherInOptions = false;
                    $otherOptionText = '';
                @endphp

                @if (is_array($question->available_options) && count($question->available_options) > 0)
                    @foreach ($question->available_options as $option)
                        @php
                            $optionText = is_array($option) && isset($option['text']) ? $option['text'] : $option;
                            $isSelected = is_string($answerValue) && strpos($answerValue, $optionText) === 0;

                            // Cek apakah ini opsi Lainnya
                            if ($optionText === 'Lainnya, sebutkan!' || $optionText === 'Lainnya') {
                                $hasOtherInOptions = true;
                                $otherOptionText = $optionText;
                            }
                        @endphp
                        <option value="{{ $optionText }}" {{ $isSelected ? 'selected' : '' }}>
                            {{ $optionText }}
                        </option>
                    @endforeach
                @endif

                {{-- PERBAIKAN: Hanya tambahkan opsi Lainnya jika belum ada di available_options --}}
                @if ($question->has_other_option && !$hasOtherInOptions)
                    <option value="Lainnya, sebutkan!" {{ strpos($answerValue ?? '', 'Lainnya:') === 0 ? 'selected' : '' }}>
                        Lainnya, sebutkan!
                    </option>
                @endif
            </select>

            {{-- Input untuk "Lainnya" pada dropdown --}}
            @if ($question->has_other_option)
                @php
                    $shouldShowOtherInput = false;
                    $otherValue = '';

                    if ($answerValue && strpos($answerValue, 'Lainnya:') === 0) {
                        $shouldShowOtherInput = true;
                        $otherValue = substr($answerValue, 9); // Remove "Lainnya: "
                    } elseif (strpos($answerValue ?? '', ':') !== false) {
                        // Juga cek format lainnya
                        $parts = explode(':', $answerValue, 2);
                        if (strpos(trim($parts[0]), 'Lainnya') === 0) {
                            $shouldShowOtherInput = true;
                            $otherValue = trim($parts[1]);
                        }
                    }
                @endphp

                <div class="other-input-container mt-2" style="{{ $shouldShowOtherInput ? '' : 'display: none;' }}">
                    <label for="other_dropdown_{{ $question->id }}" class="form-label small">
                        <i class="fas fa-edit me-1"></i> Sebutkan:
                    </label>
                    <input type="text" class="form-control form-control-sm other-input"
                        id="other_dropdown_{{ $question->id }}" name="other_dropdown_{{ $question->id }}"
                        value="{{ $otherValue }}" placeholder="Tuliskan lainnya..." style="max-width: 300px;">
                </div>
            @endif
        </div>
    @break

    {{-- Tipe Pertanyaan Checkbox --}}
    @case('checkbox')
    @case('checkbox_per_row')
        <div class="checkbox-options">
            @if (is_array($question->available_options) && count($question->available_options) > 0)
                <div class="checkbox-grid"
                    @if ($validationAttributes['max_selections']) data-max-selections="{{ $validationAttributes['max_selections'] }}" @endif>
                    @foreach ($question->available_options as $index => $option)
                        @php
                            $optionText = is_array($option) && isset($option['text']) ? $option['text'] : $option;

                            // Cek apakah opsi ini dipilih
                            $isChecked = false;
                            $additionalValue = '';

                            if (is_array($selectedOptions)) {
                                foreach ($selectedOptions as $selected) {
                                    if (is_string($selected) && strpos($selected, $optionText) === 0) {
                                        $isChecked = true;
                                        // Extract additional value
                                        if (strpos($selected, ':') !== false) {
                                            $parts = explode(':', $selected, 2);
                                            if (count($parts) > 1) {
                                                $additionalValue = trim($parts[1]);
                                            }
                                        }
                                        break;
                                    }
                                }
                            }

                            // PERBAIKAN: Deteksi yang lebih spesifik
                            $isOtherOption =
                                $optionText === 'Lainnya, sebutkan!' ||
                                $optionText === 'Lainnya' ||
                                strpos($optionText, 'Lainnya') === 0;
                            $hasOtherInput = $isOtherOption;

                            $hasEmailInput =
                                stripos($optionText, 'email') !== false ||
                                (stripos($optionText, 'Ya,') !== false && stripos($optionText, 'email') !== false);
                            $hasWhatsappInput =
                                stripos($optionText, 'WhatsApp') !== false ||
                                stripos($optionText, 'nomor WhatsApp') !== false ||
                                stripos($optionText, 'nomor WA') !== false;

                            $emailValue = $hasEmailInput ? $additionalValue : '';
                            $whatsappValue = $hasWhatsappInput ? $additionalValue : '';
                            $otherValue = $hasOtherInput ? $additionalValue : '';
                        @endphp

                        <div class="form-check mb-2"
                            data-has-email="{{ $hasEmailInput && !$hasOtherInput ? 'true' : 'false' }}" {{-- Jangan set email untuk opsi Lainnya --}}
                            data-has-whatsapp="{{ $hasWhatsappInput && !$hasOtherInput ? 'true' : 'false' }}"
                            {{-- Jangan set WhatsApp untuk opsi Lainnya --}} data-has-other="{{ $hasOtherInput ? 'true' : 'false' }}">
                            <input class="form-check-input checkbox-option" type="checkbox"
                                name="answers[{{ $question->id }}][]" id="checkbox_{{ $question->id }}_{{ $index }}"
                                value="{{ $optionText }}" {{ $isChecked ? 'checked' : '' }}
                                data-question-id="{{ $question->id }}">

                            <label class="form-check-label" for="checkbox_{{ $question->id }}_{{ $index }}">
                                {{ $optionText }}
                            </label>

                            {{-- Input Email untuk checkbox --}}
                            @if ($hasEmailInput && !$hasOtherInput)
                                {{-- Hanya tampilkan email jika bukan opsi Lainnya --}}
                                <div class="email-input-container mt-2 {{ $isChecked ? 'show' : '' }}">
                                    <label for="email_checkbox_{{ $question->id }}_{{ $index }}"
                                        class="form-label small">
                                        <i class="fas fa-envelope me-1"></i> Masukkan email:
                                    </label>
                                    <input type="email" class="form-control form-control-sm email-input"
                                        id="email_checkbox_{{ $question->id }}_{{ $index }}"
                                        placeholder="contoh@email.com" value="{{ $emailValue }}"
                                        style="max-width: 300px;">
                                </div>
                            @endif

                            {{-- Input WhatsApp untuk checkbox --}}
                            @if ($hasWhatsappInput && !$hasOtherInput)
                                {{-- Hanya tampilkan WhatsApp jika bukan opsi Lainnya --}}
                                <div class="whatsapp-input-container mt-2 {{ $isChecked ? 'show' : '' }}">
                                    <label for="whatsapp_checkbox_{{ $question->id }}_{{ $index }}"
                                        class="form-label small">
                                        <i class="fab fa-whatsapp me-1"></i> Masukkan nomor WhatsApp:
                                    </label>
                                    <input type="text" class="form-control form-control-sm whatsapp-input"
                                        id="whatsapp_checkbox_{{ $question->id }}_{{ $index }}"
                                        placeholder="081234567890" value="{{ $whatsappValue }}" style="max-width: 300px;">
                                </div>
                            @endif

                            {{-- Input Lainnya untuk checkbox --}}
                            @if ($hasOtherInput)
                                <div class="other-input-container mt-2 {{ $isChecked ? 'show' : '' }}">
                                    <label for="other_checkbox_{{ $question->id }}_{{ $index }}"
                                        class="form-label small">
                                        <i class="fas fa-edit me-1"></i> Sebutkan:
                                    </label>
                                    <input type="text" class="form-control form-control-sm other-input"
                                        id="other_checkbox_{{ $question->id }}_{{ $index }}"
                                        placeholder="Tuliskan lainnya..." value="{{ $otherValue }}"
                                        style="max-width: 300px;">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($validationAttributes['max_selections'])
                    <div class="form-text text-muted small mt-2" id="selection-counter-{{ $question->id }}">
                        Maksimal {{ $validationAttributes['max_selections'] }} pilihan
                    </div>
                @endif
            @else
                <p class="text-muted">Tidak ada opsi tersedia</p>
            @endif
        </div>
    @break

    {{-- Tipe Pertanyaan Likert Scale --}}
    @case('likert_scale')
    @case('competency_scale')
        <div class="scale-options">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Label Skala Rendah di kiri -->
                <div class="scale-label-left me-3" style="min-width: 120px;">
                    <small class="text-muted fw-semibold">{{ $scaleLabelLow }}</small>
                </div>

                <!-- Opsi Skala -->
                <div class="scale-options-grid flex-grow-1 d-flex justify-content-center px-3">
                    @foreach ($scaleOptions as $option)
                        @php
                            $optionValue = $option;
                            $isChecked = ($answerValue ?? 0) == $optionValue;
                        @endphp
                        <div class="scale-option-item text-center mx-2">
                            <div class="likert-option {{ $isChecked ? 'selected' : '' }}"
                                style="position: relative; min-width: 50px;">
                                <input type="radio" name="answers[{{ $question->id }}]"
                                    id="scale_{{ $question->id }}_{{ $optionValue }}" value="{{ $optionValue }}"
                                    {{ $isChecked ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}
                                    style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                <div class="likert-value p-2 rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto mb-1"
                                    style="width: 40px; height: 40px;">
                                    {{ $optionValue }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Label Skala Tinggi di kanan -->
                <div class="scale-label-right ms-3" style="min-width: 120px;">
                    <small class="text-muted fw-semibold">{{ $scaleLabelHigh }}</small>
                </div>
            </div>
        </div>
    @break

    {{-- Tipe Pertanyaan Likert per Baris --}}
    @case('likert_per_row')
        @if (count($rowItems) > 0)
            <div class="likert-per-row-container">
                <div class="competency-grid" id="competency-grid-{{ $question->id }}">
                    @foreach ($rowItems as $key => $item)
                        @php
                            $itemText = is_array($item) ? $item['text'] ?? $item : $item;
                            $itemKey = is_string($key) ? $key : $itemText;

                            $answerValues = [];
                            if ($answer && $answer->answer) {
                                if (is_string($answer->answer)) {
                                    $answerValues = json_decode($answer->answer, true) ?? [];
                                } elseif (is_array($answer->answer)) {
                                    $answerValues = $answer->answer;
                                }
                            }

                            $currentValue = $answerValues[$itemKey] ?? null;
                            $isAnswered = $currentValue !== null;
                        @endphp

                        <div class="competency-item d-flex align-items-center {{ $isAnswered ? 'answered' : 'unanswered' }} mb-3"
                            data-competency-key="{{ $itemKey }}" data-question-id="{{ $question->id }}">
                            <!-- Item Baris -->
                            <div class="competency-name flex-shrink-0"
                                style="width: 250px; min-width: 250px; max-width: 250px; word-wrap: break-word;">
                                {{ $itemText }}
                            </div>

                            <!-- Container untuk Skala -->
                            <div class="competency-scale-container d-flex align-items-center flex-grow-1">
                                <!-- Label Skala Rendah -->
                                <div class="scale-label-left me-2" style="min-width: 100px; text-align: right;">
                                    <small class="text-muted fw-semibold">{{ $scaleLabelLow }}</small>
                                </div>

                                <!-- Opsi Skala -->
                                <div
                                    class="competency-scale d-flex justify-content-between align-items-center flex-grow-1 px-2">
                                    @foreach ($scaleOptions as $scaleOption)
                                        @php
                                            $scaleValue = $scaleOption;
                                        @endphp
                                        <div class="form-check form-check-inline m-0" style="flex: 1;">
                                            <input class="form-check-input" type="radio"
                                                name="answers[{{ $question->id }}][{{ $itemKey }}]"
                                                id="row_{{ $question->id }}_{{ $itemKey }}_{{ $scaleValue }}"
                                                value="{{ $scaleValue }}"
                                                {{ $currentValue == $scaleValue ? 'checked' : '' }}
                                                style="width: 18px; height: 18px;">
                                            <label class="form-check-label d-block text-center mt-1"
                                                for="row_{{ $question->id }}_{{ $itemKey }}_{{ $scaleValue }}"
                                                style="font-size: 0.85rem;">
                                                <div>{{ $scaleValue }}</div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Label Skala Tinggi -->
                                <div class="scale-label-right ms-2" style="min-width: 100px;">
                                    <small class="text-muted fw-semibold">{{ $scaleLabelHigh }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($question->is_required)
                    <div class="validation-message mt-2" id="validation-{{ $question->id }}">
                        <div class="alert alert-warning py-2 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Harap isi semua skala di atas sebelum melanjutkan.
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @break

    @default
        <div class="default-option">
            <p class="text-muted">Tipe pertanyaan tidak dikenali</p>
        </div>
@endswitch

<style>
    /* Styling untuk input container */
    .email-input-container,
    .whatsapp-input-container,
    .other-input-container {
        margin-top: 10px;
        display: none;
        padding-left: 25px;
        border-left: 3px solid #f0f0f0;
    }

    .email-input-container.show,
    .whatsapp-input-container.show,
    .other-input-container.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .email-input-container {
        border-left-color: #ffc107;
    }

    .whatsapp-input-container {
        border-left-color: #25d366;
    }

    .other-input-container {
        border-left-color: #6c757d;
    }

    .answer-option.selected {
        border: 2px solid #fab300;
        background-color: #fef3c7;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
    }

    .answer-option {
        border: 2px solid #eaeaea;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .answer-option:hover {
        border-color: #3b82f6;
        background-color: #dbeafe;
    }

    /* Styling untuk skala options */
    .scale-options-grid {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 5px;
        flex-grow: 1;
    }

    .scale-option-item {
        flex: 1;
        min-width: 60px;
        max-width: 80px;
    }

    .likert-option.selected .likert-value {
        background-color: #0d6efd !important;
        color: white !important;
        border-color: #0d6efd !important;
    }

    .competency-scale {
        display: flex;
        gap: 5px;
        align-items: center;
        flex-wrap: nowrap;
        padding: 5px 0;
    }

    .competency-scale .form-check-inline {
        margin-right: 0;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .competency-item .competency-name {
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .competency-item {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .competency-name {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            margin-bottom: 10px;
        }

        .competency-scale-container {
            width: 100%;
            flex-direction: column;
            align-items: flex-start !important;
        }

        .scale-label-left,
        .scale-label-right {
            min-width: 100% !important;
            text-align: left !important;
            margin: 5px 0 !important;
        }

        .competency-scale {
            width: 100%;
            justify-content: space-between;
            gap: 5px;
        }
    }

    @media (max-width: 768px) {
        .scale-options {
            flex-direction: column;
            gap: 10px;
        }

        .scale-label-left,
        .scale-label-right {
            min-width: 100% !important;
            text-align: center !important;
        }

        .scale-options-grid {
            width: 100%;
            justify-content: space-between;
        }
    }

    /* Char counter styling */
    .char-counter-warning {
        color: #dc3545;
        font-weight: bold;
    }

    .selection-counter-warning {
        color: #dc3545;
        font-weight: bold;
    }

    /* Validation styling */
    .competency-item.unanswered {
        border: 2px solid #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.05) !important;
        animation: pulseWarning 1.5s infinite;
        border-radius: 8px;
        padding: 15px;
    }

    .competency-item.answered {
        border: 2px solid #28a745 !important;
        background-color: rgba(40, 167, 69, 0.05) !important;
        border-radius: 8px;
        padding: 15px;
    }

    @keyframes pulseWarning {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Character counter for text and textarea
        document.querySelectorAll('.text-answer-input, .textarea-answer-input').forEach(input => {
            const maxLength = input.getAttribute('data-max-length');
            if (maxLength) {
                const questionId = input.getAttribute('data-question-id');
                const counter = document.getElementById(`char-counter-${questionId}`);

                if (counter) {
                    // Update counter on input
                    input.addEventListener('input', function() {
                        const currentLength = this.value.length;
                        counter.textContent = currentLength;

                        if (currentLength >= maxLength * 0.9) {
                            counter.classList.add('char-counter-warning');
                        } else {
                            counter.classList.remove('char-counter-warning');
                        }

                        // Prevent exceeding max length
                        if (currentLength > maxLength) {
                            this.value = this.value.substring(0, maxLength);
                            counter.textContent = maxLength;
                        }
                    });

                    // Initialize counter
                    const initialLength = input.value ? input.value.length : 0;
                    counter.textContent = initialLength;
                    if (initialLength >= maxLength * 0.9) {
                        counter.classList.add('char-counter-warning');
                    }
                }
            }
        });

        // Checkbox selection counter
        document.querySelectorAll('.checkbox-grid').forEach(grid => {
            const maxSelections = grid.getAttribute('data-max-selections');
            if (maxSelections) {
                const questionId = grid.closest('.answer-area').id.replace('answer-area-', '');
                const counter = document.getElementById(`selection-counter-${questionId}`);

                const updateCounter = () => {
                    const checkedCount = grid.querySelectorAll('.checkbox-option:checked').length;

                    if (counter) {
                        counter.textContent = `Dipilih: ${checkedCount}/${maxSelections} pilihan`;

                        if (checkedCount >= maxSelections) {
                            counter.classList.add('selection-counter-warning');
                            // Disable unchecked checkboxes
                            grid.querySelectorAll('.checkbox-option:not(:checked)').forEach(cb => {
                                cb.disabled = true;
                            });
                        } else {
                            counter.classList.remove('selection-counter-warning');
                            // Enable all checkboxes
                            grid.querySelectorAll('.checkbox-option').forEach(cb => {
                                cb.disabled = false;
                            });
                        }
                    }
                };

                grid.querySelectorAll('.checkbox-option').forEach(checkbox => {
                    checkbox.addEventListener('change', updateCounter);
                });

                // Initialize counter
                updateCounter();
            }
        });

        // Number input validation
        document.querySelectorAll('.number-answer-input').forEach(input => {
            input.addEventListener('blur', function() {
                const min = this.getAttribute('min');
                const max = this.getAttribute('max');
                const value = this.value ? parseFloat(this.value) : null;

                if (value !== null) {
                    if (min && value < parseFloat(min)) {
                        alert(`Nilai minimum adalah ${min}`);
                        this.value = min;
                        this.focus();
                    }

                    if (max && value > parseFloat(max)) {
                        alert(`Nilai maksimum adalah ${max}`);
                        this.value = max;
                        this.focus();
                    }
                }
            });
        });

        // Function untuk menangani perubahan radio button
        function handleRadioChange(questionId) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return;

            const radioInputs = container.querySelectorAll('input[type="radio"]');

            radioInputs.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selected class from all options
                    container.querySelectorAll('.answer-option').forEach(option => {
                        option.classList.remove('selected');
                    });

                    // Add selected class to current option
                    const answerOption = this.closest('.answer-option');
                    if (answerOption) {
                        answerOption.classList.add('selected');
                    }

                    // Handle input container visibility
                    handleInputContainerVisibilityForRadio(questionId);
                });
            });
        }

        // Function untuk menangani perubahan checkbox
        function handleCheckboxChange(questionId) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return;

            const checkboxInputs = container.querySelectorAll('input[type="checkbox"]');

            checkboxInputs.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    handleInputContainerVisibilityForCheckbox(questionId, this);
                });
            });
        }

        // Function untuk menangani perubahan dropdown
        function handleDropdownChange(questionId) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return;

            const select = container.querySelector('select');
            const otherContainer = container.querySelector('.other-input-container');

            if (select && otherContainer) {
                select.addEventListener('change', function() {
                    const selectedValue = this.value;
                    const isOtherOption = selectedValue.includes('Lainnya');

                    if (isOtherOption) {
                        otherContainer.classList.add('show');
                        otherContainer.querySelector('.other-input').required = true;
                    } else {
                        otherContainer.classList.remove('show');
                        otherContainer.querySelector('.other-input').required = false;
                        otherContainer.querySelector('.other-input').value = '';
                    }
                });

                // Initialize on load
                const initialValue = select.value;
                const isOtherOption = initialValue && initialValue.includes('Lainnya');
                if (isOtherOption) {
                    otherContainer.classList.add('show');
                }
            }
        }

        // Function untuk menangani visibility input container pada radio
        function handleInputContainerVisibilityForRadio(questionId) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return;

            // Sembunyikan semua input container
            container.querySelectorAll(
                    '.email-input-container, .whatsapp-input-container, .other-input-container')
                .forEach(container => {
                    container.classList.remove('show');
                });

            // Cari radio yang terpilih
            const selectedRadio = container.querySelector('input[type="radio"]:checked');
            if (selectedRadio) {
                const answerOption = selectedRadio.closest('.answer-option');
                if (answerOption) {
                    // Tampilkan input container yang sesuai
                    const hasEmail = answerOption.getAttribute('data-has-email') === 'true';
                    const hasWhatsapp = answerOption.getAttribute('data-has-whatsapp') === 'true';
                    const hasOther = answerOption.getAttribute('data-has-other') === 'true';

                    if (hasEmail) {
                        const emailContainer = answerOption.querySelector('.email-input-container');
                        if (emailContainer) emailContainer.classList.add('show');
                    }
                    if (hasWhatsapp) {
                        const whatsappContainer = answerOption.querySelector('.whatsapp-input-container');
                        if (whatsappContainer) whatsappContainer.classList.add('show');
                    }
                    if (hasOther) {
                        const otherContainer = answerOption.querySelector('.other-input-container');
                        if (otherContainer) otherContainer.classList.add('show');
                    }
                }
            }
        }

        // Function untuk menangani visibility input container pada checkbox
        function handleInputContainerVisibilityForCheckbox(questionId, changedCheckbox) {
            const formCheck = changedCheckbox.closest('.form-check');
            if (!formCheck) return;

            const hasEmail = formCheck.getAttribute('data-has-email') === 'true';
            const hasWhatsapp = formCheck.getAttribute('data-has-whatsapp') === 'true';
            const hasOther = formCheck.getAttribute('data-has-other') === 'true';

            const emailContainer = formCheck.querySelector('.email-input-container');
            const whatsappContainer = formCheck.querySelector('.whatsapp-input-container');
            const otherContainer = formCheck.querySelector('.other-input-container');

            if (changedCheckbox.checked) {
                if (hasEmail && emailContainer) emailContainer.classList.add('show');
                if (hasWhatsapp && whatsappContainer) whatsappContainer.classList.add('show');
                if (hasOther && otherContainer) otherContainer.classList.add('show');
            } else {
                if (emailContainer) emailContainer.classList.remove('show');
                if (whatsappContainer) whatsappContainer.classList.remove('show');
                if (otherContainer) otherContainer.classList.remove('show');

                // Clear input values
                if (emailContainer) emailContainer.querySelector('.email-input').value = '';
                if (whatsappContainer) whatsappContainer.querySelector('.whatsapp-input').value = '';
                if (otherContainer) otherContainer.querySelector('.other-input').value = '';
            }
        }

        // Initialize event handlers for all questions
        document.querySelectorAll('.answer-area').forEach(container => {
            const questionId = container.id.replace('answer-area-', '');
            const questionType = container.querySelector('[data-question-type]')?.getAttribute(
                'data-question-type');

            if (questionType === 'radio' || questionType === 'radio_per_row') {
                handleRadioChange(questionId);
                // Initialize visibility on load
                setTimeout(() => handleInputContainerVisibilityForRadio(questionId), 100);
            } else if (questionType === 'checkbox' || questionType === 'checkbox_per_row') {
                handleCheckboxChange(questionId);
                // Initialize visibility on load
                const checkboxes = container.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        handleInputContainerVisibilityForCheckbox(questionId, checkbox);
                    }
                });
            } else if (questionType === 'dropdown') {
                handleDropdownChange(questionId);
            }
        });

        // Likert per row validation
        function validateLikertPerRow(questionId) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return {
                isValid: true,
                unansweredItems: []
            };

            const competencyItems = container.querySelectorAll('.competency-item');
            let allAnswered = true;
            const unansweredItems = [];

            competencyItems.forEach((item, index) => {
                const radios = item.querySelectorAll('input[type="radio"]');
                let answered = false;

                radios.forEach(radio => {
                    if (radio.checked) {
                        answered = true;
                    }
                });

                if (!answered) {
                    allAnswered = false;
                    const competencyName = item.querySelector('.competency-name').textContent.trim();
                    unansweredItems.push(`${index + 1}. ${competencyName}`);
                }
            });

            return {
                isValid: allAnswered,
                unansweredItems: unansweredItems
            };
        }

        // Auto-validate likert per row on radio change
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio' && e.target.name.includes('[') && e.target.name.includes(
                ']')) {
                const competencyItem = e.target.closest('.competency-item');
                if (competencyItem) {
                    const questionId = competencyItem.getAttribute('data-question-id');
                    const validation = validateLikertPerRow(questionId);
                    const validationMessage = document.getElementById(`validation-${questionId}`);

                    if (validationMessage) {
                        if (validation.isValid) {
                            validationMessage.classList.remove('show');
                            competencyItem.classList.remove('unanswered');
                            competencyItem.classList.add('answered');
                        } else {
                            validationMessage.classList.add('show');
                            // Find unanswered items and mark them
                            competencyItems.forEach(item => {
                                const radios = item.querySelectorAll('input[type="radio"]');
                                let answered = false;
                                radios.forEach(radio => {
                                    if (radio.checked) answered = true;
                                });
                                if (!answered) {
                                    item.classList.add('unanswered');
                                    item.classList.remove('answered');
                                } else {
                                    item.classList.remove('unanswered');
                                    item.classList.add('answered');
                                }
                            });
                        }
                    }
                }
            }
        });
    });
</script>
