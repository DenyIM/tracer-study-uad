{{-- resources/views/questionnaire/answers/partials/answer-input.blade.php --}}
@php
    // Ambil nilai jawaban
    $answerValue = null;
    $selectedOptions = [];
    $scaleValue = null;

    if ($answer) {
        $answerValue = $answer->answer;
        $selectedOptions = $answer->selected_options ? json_decode($answer->selected_options, true) : [];
        $scaleValue = $answer->scale_value;
    }

    // Extract values untuk input tambahan
    $emailValue = '';
    $whatsappValue = '';
    $otherValue = '';

    // Helper untuk extract value dari string
    $extractValue = function ($text, $keyword) {
        if (is_string($text) && strpos($text, $keyword) !== false) {
            $parts = explode(':', $text, 2);
            return count($parts) > 1 ? trim($parts[1]) : '';
        }
        return '';
    };

    // Parse dari answer
    if (is_string($answerValue)) {
        // Cek email
        if (strpos($answerValue, 'email:') !== false) {
            $emailValue = $extractValue($answerValue, 'email:');
        } elseif (strpos($answerValue, 'Email:') !== false) {
            $emailValue = $extractValue($answerValue, 'Email:');
        } elseif (strpos($answerValue, 'Ya,') !== false && strpos($answerValue, ':') !== false) {
            $parts = explode(':', $answerValue, 2);
            $emailValue = count($parts) > 1 ? trim($parts[1]) : '';
        }

        // Cek WhatsApp
        if (strpos($answerValue, 'WhatsApp:') !== false) {
            $whatsappValue = $extractValue($answerValue, 'WhatsApp:');
        } elseif (strpos($answerValue, 'nomor WhatsApp:') !== false) {
            $whatsappValue = $extractValue($answerValue, 'nomor WhatsApp:');
        } elseif (strpos($answerValue, 'nomor WA:') !== false) {
            $whatsappValue = $extractValue($answerValue, 'nomor WA:');
        }

        // Cek Lainnya
        if (strpos($answerValue, 'Lainnya:') === 0) {
            $parts = explode(':', $answerValue, 2);
            $otherValue = count($parts) > 1 ? trim($parts[1]) : '';
        }
    }

    // Parse dari selected options (checkbox)
    if (is_array($selectedOptions)) {
        foreach ($selectedOptions as $selected) {
            if (is_string($selected)) {
                // Handle email
                if (strpos($selected, 'email:') !== false) {
                    $emailValue = $extractValue($selected, 'email:');
                } elseif (strpos($selected, 'Email:') !== false) {
                    $emailValue = $extractValue($selected, 'Email:');
                } elseif (strpos($selected, 'Ya,') !== false && strpos($selected, ':') !== false) {
                    $parts = explode(':', $selected, 2);
                    $emailValue = count($parts) > 1 ? trim($parts[1]) : '';
                }

                // Handle WhatsApp
                if (strpos($selected, 'WhatsApp:') !== false) {
                    $whatsappValue = $extractValue($selected, 'WhatsApp:');
                } elseif (strpos($selected, 'nomor WhatsApp:') !== false) {
                    $whatsappValue = $extractValue($selected, 'nomor WhatsApp:');
                } elseif (strpos($selected, 'nomor WA:') !== false) {
                    $whatsappValue = $extractValue($selected, 'nomor WA:');
                }

                // Handle Lainnya
                if (strpos($selected, 'Lainnya:') === 0) {
                    $parts = explode(':', $selected, 2);
                    $otherValue = count($parts) > 1 ? trim($parts[1]) : '';
                }
            }
        }
    }

    // Parse row items
    $rowItems = [];
    if ($question->row_items) {
        if (is_string($question->row_items)) {
            $rowItems = json_decode($question->row_items, true) ?? [];
        } else {
            $rowItems = $question->row_items;
        }
    }

    // Parse scale options
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
@endphp

@switch($question->question_type)
    @case('text')
        <div class="text-option">
            <input type="text" class="form-control text-answer-input" name="answers[{{ $question->id }}]"
                value="{{ $answerValue ?? '' }}" placeholder="{{ $question->placeholder ?? 'Tulis jawaban...' }}"
                {{ $question->is_required ? 'required' : '' }}
                {{ $question->max_length ? 'maxlength=' . $question->max_length : '' }}>
        </div>
    @break

    @case('textarea')
        <div class="textarea-option">
            <textarea class="form-control textarea-answer-input" name="answers[{{ $question->id }}]" rows="{{ $question->rows ?? 4 }}"
                placeholder="{{ $question->placeholder ?? 'Tulis jawaban...' }}" {{ $question->is_required ? 'required' : '' }}
                {{ $question->max_length ? 'maxlength=' . $question->max_length : '' }}>{{ $answerValue ?? '' }}</textarea>
        </div>
    @break

    @case('number')
        <div class="number-option">
            <input type="number" class="form-control number-answer-input" name="answers[{{ $question->id }}]"
                value="{{ $answerValue ?? '' }}" min="{{ $question->min_value ?? '' }}"
                max="{{ $question->max_value ?? '' }}" placeholder="{{ $question->placeholder ?? 'Masukkan angka...' }}"
                {{ $question->is_required ? 'required' : '' }}>
        </div>
    @break

    @case('date')
        <div class="date-option">
            <input type="date" class="form-control date-answer-input" name="answers[{{ $question->id }}]"
                value="{{ $answerValue ?? '' }}" {{ $question->is_required ? 'required' : '' }}>
        </div>
    @break

    @case('radio')
        <div class="radio-options">
            @if (is_array($question->available_options) && count($question->available_options) > 0)
                @foreach ($question->available_options as $index => $option)
                    @php
                        // Ambil teks opsi
                        $optionText = $option;
                        if (is_array($option) && isset($option['text'])) {
                            $optionText = $option['text'];
                        }

                        // Tentukan jenis input
                        $hasEmailInput = false;
                        $hasWhatsAppInput = false;
                        $hasOtherInput = false;

                        $lowerOption = strtolower($optionText);

                        // Deteksi email input (case-sensitive untuk "Ya,")
                        if (
                            strpos($optionText, 'Ya, email') !== false ||
                            strpos($optionText, 'Ya, Email') !== false ||
                            (strpos($optionText, 'Ya,') !== false && strpos($lowerOption, 'email') !== false)
                        ) {
                            $hasEmailInput = true;
                        }
                        // Deteksi WhatsApp input
                        elseif (
                            strpos($optionText, 'nomor WhatsApp') !== false ||
                            strpos($optionText, 'nomor WA') !== false ||
                            strpos($optionText, 'WhatsApp') !== false
                        ) {
                            $hasWhatsAppInput = true;
                        }
                        // Deteksi Lainnya input
                        elseif ($optionText === 'Lainnya, sebutkan!' || $optionText === 'Lainnya') {
                            $hasOtherInput = true;
                        }

                        // Cek apakah dipilih
                        $isSelected = false;
                        $inputValue = '';

                        if (is_string($answerValue)) {
                            if (strpos($answerValue, $optionText) === 0) {
                                $isSelected = true;
                                if ($hasEmailInput) {
                                    $inputValue = $emailValue;
                                } elseif ($hasWhatsAppInput) {
                                    $inputValue = $whatsappValue;
                                } elseif ($hasOtherInput) {
                                    $inputValue = $otherValue;
                                }
                            } elseif ($hasOtherInput && strpos($answerValue, 'Lainnya:') === 0) {
                                $isSelected = true;
                                $inputValue = $otherValue;
                            }
                        }
                    @endphp

                    <div class="answer-option {{ $isSelected ? 'selected' : '' }}" data-option="{{ $optionText }}"
                        data-has-email="{{ $hasEmailInput ? 'true' : 'false' }}"
                        data-has-whatsapp="{{ $hasWhatsAppInput ? 'true' : 'false' }}"
                        data-has-other="{{ $hasOtherInput ? 'true' : 'false' }}">

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                id="radio_{{ $question->id }}_{{ $index }}" value="{{ $optionText }}"
                                {{ $isSelected ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}>

                            <label class="form-check-label fw-medium" for="radio_{{ $question->id }}_{{ $index }}">
                                {{ $optionText }}
                            </label>
                        </div>

                        @if ($hasEmailInput)
                            <div class="email-input-container mt-2 {{ $isSelected ? 'show' : '' }}">
                                <label for="email_{{ $question->id }}_{{ $index }}" class="form-label small">
                                    <i class="fas fa-envelope me-1"></i> Masukkan email:
                                </label>
                                <input type="email" class="form-control form-control-sm email-input"
                                    id="email_{{ $question->id }}_{{ $index }}" placeholder="contoh@email.com"
                                    value="{{ $inputValue }}" style="max-width: 300px;">
                            </div>
                        @endif

                        @if ($hasWhatsAppInput)
                            <div class="whatsapp-input-container mt-2 {{ $isSelected ? 'show' : '' }}">
                                <label for="whatsapp_{{ $question->id }}_{{ $index }}" class="form-label small">
                                    <i class="fab fa-whatsapp me-1"></i> Masukkan nomor WhatsApp:
                                </label>
                                <input type="tel" class="form-control form-control-sm whatsapp-input"
                                    id="whatsapp_{{ $question->id }}_{{ $index }}" placeholder="08123456789"
                                    value="{{ $inputValue }}" style="max-width: 300px;">
                            </div>
                        @endif

                        @if ($hasOtherInput)
                            <div class="other-input-container mt-2 {{ $isSelected ? 'show' : '' }}">
                                <label for="other_{{ $question->id }}_{{ $index }}" class="form-label small">
                                    <i class="fas fa-pen me-1"></i> Sebutkan:
                                </label>
                                <input type="text" class="form-control form-control-sm other-input"
                                    id="other_{{ $question->id }}_{{ $index }}" placeholder="Tuliskan jawaban Anda..."
                                    value="{{ $inputValue }}" style="max-width: 300px;">
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-muted">Tidak ada opsi tersedia</p>
            @endif
        </div>
    @break

    @case('dropdown')
        <div class="dropdown-option">
            <select class="form-select dropdown-answer-select" name="answers[{{ $question->id }}]"
                id="dropdown_{{ $question->id }}" {{ $question->is_required ? 'required' : '' }}>
                <option value="" disabled {{ !$answerValue ? 'selected' : '' }}>Pilih opsi...</option>

                @if (is_array($question->available_options) && count($question->available_options) > 0)
                    @foreach ($question->available_options as $option)
                        @php
                            $optionText = $option;
                            if (is_array($option) && isset($option['text'])) {
                                $optionText = $option['text'];
                            }

                            $isSelected = false;

                            if (is_string($answerValue)) {
                                $isSelected =
                                    strpos($answerValue, $optionText) === 0 ||
                                    ($optionText === 'Lainnya, sebutkan!' && strpos($answerValue, 'Lainnya:') === 0);
                            }
                        @endphp
                        <option value="{{ $optionText }}" {{ $isSelected ? 'selected' : '' }}>
                            {{ $optionText }}
                        </option>
                    @endforeach
                @endif
            </select>

            @if ($question->has_other_option)
                <div class="other-input-container mt-2 {{ strpos($answerValue ?? '', 'Lainnya:') === 0 ? 'show' : '' }}">
                    <label for="other_dropdown_{{ $question->id }}" class="form-label small">
                        <i class="fas fa-pen me-1"></i> Sebutkan:
                    </label>
                    <input type="text" class="form-control form-control-sm other-input"
                        id="other_dropdown_{{ $question->id }}" placeholder="Tuliskan jawaban Anda..."
                        value="{{ $otherValue }}" style="max-width: 300px;">
                </div>
            @endif
        </div>
    @break

    @case('checkbox')
        <div class="checkbox-options">
            @if (is_array($question->available_options) && count($question->available_options) > 0)
                <div class="checkbox-grid">
                    @foreach ($question->available_options as $index => $option)
                        @php
                            // Ambil teks opsi
                            $optionText = $option;
                            if (is_array($option) && isset($option['text'])) {
                                $optionText = $option['text'];
                            }

                            // Tentukan jenis input
                            $hasEmailInput = false;
                            $hasWhatsAppInput = false;
                            $hasOtherInput = false;

                            $lowerOption = strtolower($optionText);

                            // Deteksi email input (case-sensitive untuk "Ya,")
                            if (
                                strpos($optionText, 'Ya, email') !== false ||
                                strpos($optionText, 'Ya, Email') !== false ||
                                (strpos($optionText, 'Ya,') !== false && strpos($lowerOption, 'email') !== false)
                            ) {
                                $hasEmailInput = true;
                            }
                            // Deteksi WhatsApp input
                            elseif (
                                strpos($optionText, 'nomor WhatsApp') !== false ||
                                strpos($optionText, 'nomor WA') !== false ||
                                strpos($optionText, 'WhatsApp') !== false
                            ) {
                                $hasWhatsAppInput = true;
                            }
                            // Deteksi Lainnya input
                            elseif ($optionText === 'Lainnya, sebutkan!' || $optionText === 'Lainnya') {
                                $hasOtherInput = true;
                            }

                            // Cek apakah dipilih (checkbox)
                            $isChecked = false;
                            $inputValue = '';

                            if (is_array($selectedOptions)) {
                                foreach ($selectedOptions as $selected) {
                                    if (is_string($selected)) {
                                        // Cek jika selected dimulai dengan optionText
                                        if (strpos($selected, $optionText) === 0) {
                                            $isChecked = true;
                                            if ($hasEmailInput || $hasWhatsAppInput || $hasOtherInput) {
                                                // Extract value dari format "Opsi: nilai"
                                                $parts = explode(':', $selected, 2);
                                                $inputValue = count($parts) > 1 ? trim($parts[1]) : '';
                                            }
                                            break;
                                        }
                                        // Cek khusus untuk Lainnya
                                        elseif ($hasOtherInput && strpos($selected, 'Lainnya:') === 0) {
                                            $isChecked = true;
                                            $parts = explode(':', $selected, 2);
                                            $inputValue = count($parts) > 1 ? trim($parts[1]) : '';
                                            break;
                                        }
                                    }
                                }
                            }
                        @endphp

                        <div class="form-check mb-3" data-has-email="{{ $hasEmailInput ? 'true' : 'false' }}"
                            data-has-whatsapp="{{ $hasWhatsAppInput ? 'true' : 'false' }}"
                            data-has-other="{{ $hasOtherInput ? 'true' : 'false' }}">

                            <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]"
                                id="checkbox_{{ $question->id }}_{{ $index }}" value="{{ $optionText }}"
                                {{ $isChecked ? 'checked' : '' }}>

                            <label class="form-check-label" for="checkbox_{{ $question->id }}_{{ $index }}">
                                {{ $optionText }}
                            </label>

                            @if ($hasEmailInput)
                                <div class="email-input-container mt-2 {{ $isChecked ? 'show' : '' }}">
                                    <label for="email_checkbox_{{ $question->id }}_{{ $index }}"
                                        class="form-label small">
                                        <i class="fas fa-envelope me-1"></i> Masukkan email:
                                    </label>
                                    <input type="email" class="form-control form-control-sm email-input"
                                        id="email_checkbox_{{ $question->id }}_{{ $index }}"
                                        placeholder="contoh@email.com" value="{{ $inputValue }}"
                                        style="max-width: 250px;">
                                </div>
                            @endif

                            @if ($hasWhatsAppInput)
                                <div class="whatsapp-input-container mt-2 {{ $isChecked ? 'show' : '' }}">
                                    <label for="whatsapp_checkbox_{{ $question->id }}_{{ $index }}"
                                        class="form-label small">
                                        <i class="fab fa-whatsapp me-1"></i> Masukkan nomor WhatsApp:
                                    </label>
                                    <input type="tel" class="form-control form-control-sm whatsapp-input"
                                        id="whatsapp_checkbox_{{ $question->id }}_{{ $index }}"
                                        placeholder="08123456789" value="{{ $inputValue }}" style="max-width: 250px;">
                                </div>
                            @endif

                            @if ($hasOtherInput)
                                <div class="other-input-container mt-2 {{ $isChecked ? 'show' : '' }}">
                                    <label for="other_checkbox_{{ $question->id }}_{{ $index }}"
                                        class="form-label small">
                                        <i class="fas fa-pen me-1"></i> Sebutkan:
                                    </label>
                                    <input type="text" class="form-control form-control-sm other-input"
                                        id="other_checkbox_{{ $question->id }}_{{ $index }}"
                                        placeholder="Tuliskan jawaban Anda..." value="{{ $inputValue }}"
                                        style="max-width: 250px;">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Tidak ada opsi tersedia</p>
            @endif
        </div>
    @break

    @case('likert_scale')
    @case('competency_scale')
        <div class="scale-options">
            <div class="scale-options-grid">
                @foreach ($scaleOptions as $scaleValue)
                    <div class="scale-option-item text-center">
                        <div class="likert-option">
                            <input type="radio" name="answers[{{ $question->id }}]"
                                id="scale_{{ $question->id }}_{{ $scaleValue }}" value="{{ $scaleValue }}"
                                {{ ($scaleValue ?? 0) == $scaleValue ? 'checked' : '' }}
                                {{ $question->is_required ? 'required' : '' }}>
                            <div class="likert-label">{{ $scaleValue }}</div>
                            @if ($scaleValue == 1 && $question->scale_label_low)
                                <div class="likert-label small">{{ $question->scale_label_low }}</div>
                            @elseif($scaleValue == max($scaleOptions) && $question->scale_label_high)
                                <div class="likert-label small">{{ $question->scale_label_high }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @break

    @case('radio_per_row')
        @if (count($rowItems) > 0)
            <div class="row-item-grid">
                @foreach ($rowItems as $key => $item)
                    @php
                        $itemText = is_array($item) ? $item['text'] ?? $item : $item;
                        $itemValue = is_array($item) ? $item['value'] ?? $key : $key;

                        // Parse answer untuk row ini
                        $rowAnswer = null;
                        if (is_array($answerValue)) {
                            $rowAnswer = $answerValue[$key] ?? null;
                        }
                    @endphp

                    <div class="radio-per-row-item">
                        <div class="radio-per-row-label">
                            {{ $itemText }}
                        </div>
                        <div class="row-item-options">
                            @if (is_array($question->available_options) && count($question->available_options) > 0)
                                @foreach ($question->available_options as $optionIndex => $option)
                                    @php
                                        $optionText = $option;
                                        if (is_array($option) && isset($option['text'])) {
                                            $optionText = $option['text'];
                                        }

                                        $isSelected = $rowAnswer === $optionText;
                                    @endphp

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="answers[{{ $question->id }}][{{ $key }}]"
                                            id="row_radio_{{ $question->id }}_{{ $key }}_{{ $optionIndex }}"
                                            value="{{ $optionText }}" {{ $isSelected ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="row_radio_{{ $question->id }}_{{ $key }}_{{ $optionIndex }}">
                                            {{ $optionText }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">Tidak ada item baris tersedia</p>
        @endif
    @break

    @case('checkbox_per_row')
        @if (count($rowItems) > 0)
            <div class="row-item-grid">
                @foreach ($rowItems as $key => $item)
                    @php
                        $itemText = is_array($item) ? $item['text'] ?? $item : $item;
                        $itemValue = is_array($item) ? $item['value'] ?? $key : $key;

                        // Parse answer untuk row ini (array of selected options)
                        $rowAnswers = [];
                        if (is_array($answerValue)) {
                            $rowAnswers = $answerValue[$key] ?? [];
                        } elseif (is_string($answerValue)) {
                            $rowAnswers = json_decode($answerValue, true)[$key] ?? [];
                        }
                        if (!is_array($rowAnswers)) {
                            $rowAnswers = [$rowAnswers];
                        }
                    @endphp

                    <div class="checkbox-per-row-item">
                        <div class="checkbox-per-row-label">
                            {{ $itemText }}
                        </div>
                        <div class="checkbox-per-row-options">
                            @if (is_array($question->available_options) && count($question->available_options) > 0)
                                @foreach ($question->available_options as $optionIndex => $option)
                                    @php
                                        $optionText = $option;
                                        if (is_array($option) && isset($option['text'])) {
                                            $optionText = $option['text'];
                                        }

                                        $isChecked = in_array($optionText, $rowAnswers);
                                    @endphp

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="answers[{{ $question->id }}][{{ $key }}][]"
                                            id="row_checkbox_{{ $question->id }}_{{ $key }}_{{ $optionIndex }}"
                                            value="{{ $optionText }}" {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="row_checkbox_{{ $question->id }}_{{ $key }}_{{ $optionIndex }}">
                                            {{ $optionText }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">Tidak ada item baris tersedia</p>
        @endif
    @break

    @case('likert_per_row')
        @if (count($rowItems) > 0)
            <div class="likert-per-row-container">
                <div class="competency-grid" id="competency-grid-{{ $question->id }}">
                    @foreach ($rowItems as $key => $item)
                        @php
                            $itemText = is_array($item) ? $item['text'] ?? $item : $item;
                            $currentValue = null;

                            // Parse answer values
                            $answerValues = [];
                            if (is_string($answerValue)) {
                                $answerValues = json_decode($answerValue, true) ?? [];
                            } elseif (is_array($answerValue)) {
                                $answerValues = $answerValue;
                            }

                            $currentValue = $answerValues[$key] ?? null;
                            $isAnswered = $currentValue !== null && $currentValue !== '';
                        @endphp

                        <div class="competency-item {{ $isAnswered ? 'answered' : 'unanswered' }}"
                            data-competency-key="{{ $key }}" data-question-id="{{ $question->id }}">
                            <div class="competency-name">{{ $itemText }}</div>
                            <div class="competency-scale">
                                @foreach ($scaleOptions as $scaleValue)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="answers[{{ $question->id }}][{{ $key }}]"
                                            id="row_{{ $question->id }}_{{ $key }}_{{ $scaleValue }}"
                                            value="{{ $scaleValue }}"
                                            {{ $currentValue == $scaleValue ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="row_{{ $question->id }}_{{ $key }}_{{ $scaleValue }}">
                                            {{ $scaleValue }}
                                        </label>
                                    </div>
                                @endforeach
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
            <input type="text" class="form-control" name="answers[{{ $question->id }}]"
                value="{{ $answerValue ?? '' }}" placeholder="Jawaban..." {{ $question->is_required ? 'required' : '' }}>
        </div>
@endswitch
