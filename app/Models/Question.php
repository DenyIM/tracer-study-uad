<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    // Tambahkan mapping tipe pertanyaan
    const TYPE_MAPPING = [
        'text' => 'Teks Singkat',
        'textarea' => 'Teks Panjang',
        'number' => 'Angka',
        'date' => 'Tanggal',
        'radio' => 'Pilihan Tunggal (Radio)',
        'dropdown' => 'Pilihan Dropdown',
        'checkbox' => 'Pilihan Ganda (Checkbox)',
        'likert_per_row' => 'Likert per Baris',
    ];

    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'question_type',
        'description',
        'options',
        'scale_options',
        'scale_information',
        'row_items',
        'scale_label_low',
        'scale_label_high',
        'min_value',
        'max_value',
        'input_type',
        'max_length',
        'rows',
        'is_required',
        'order',
        'points',
        'validation_rules',
        'placeholder',
        'helper_text',
        'has_other_option',
        'has_none_option',
        'is_locked_by_default',
        'allow_multiple_selection',
        'max_selections',
    ];

    protected $casts = [
        'options' => 'array',
        'scale_options' => 'array',
        'scale_information' => 'array',
        'row_items' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        'has_other_option' => 'boolean',
        'has_none_option' => 'boolean',
        'is_locked_by_default' => 'boolean',
        'allow_multiple_selection' => 'boolean',
        'points' => 'integer',
        'min_value' => 'integer',
        'max_value' => 'integer',
        'max_length' => 'integer',
        'rows' => 'integer',
        'max_selections' => 'integer',
    ];

    /**
     * Set the options attribute
     */
    public function setOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['options'] = json_encode($value);
        } elseif (is_string($value) && !empty($value)) {
            $this->attributes['options'] = $value;
        } else {
            $this->attributes['options'] = null;
        }
    }

    /**
     * Set the row_items attribute
     */
    public function setRowItemsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['row_items'] = json_encode($value);
        } elseif (is_string($value) && !empty($value)) {
            $this->attributes['row_items'] = $value;
        } else {
            $this->attributes['row_items'] = null;
        }
    }

    /**
     * Set the scale_options attribute
     */
    public function setScaleOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['scale_options'] = json_encode($value);
        } elseif (is_string($value) && !empty($value)) {
            $this->attributes['scale_options'] = $value;
        } else {
            $this->attributes['scale_options'] = null;
        }
    }

    /**
     * Set the scale_information attribute
     */
    public function setScaleInformationAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['scale_information'] = json_encode($value);
        } elseif (is_string($value) && !empty($value)) {
            $this->attributes['scale_information'] = $value;
        } else {
            $this->attributes['scale_information'] = null;
        }
    }

    /**
     * Get the questionnaire that owns the question
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get all answers for this question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AnswerQuestion::class);
    }

    /**
     * Get answer for a specific alumni
     */
    public function answerForAlumni($alumniId)
    {
        return $this->answers()->where('alumni_id', $alumniId)->first();
    }

    /**
     * Check if question is answered by alumni
     */
    public function isAnsweredBy($alumniId): bool
    {
        return $this->answers()->where('alumni_id', $alumniId)->exists();
    }

    /**
     * Get available options including "other" and "none" if applicable
     */
    public function getAvailableOptionsAttribute(): array
    {
        $options = [];
        
        if ($this->options) {
            // Decode jika string JSON
            if (is_string($this->options)) {
                $decoded = json_decode($this->options, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $item) {
                        if (is_array($item) && isset($item['text'])) {
                            $options[] = $item['text'];
                        } elseif (is_string($item)) {
                            $options[] = $item;
                        }
                    }
                }
            } elseif (is_array($this->options)) {
                // Handle array langsung
                foreach ($this->options as $item) {
                    if (is_array($item) && isset($item['text'])) {
                        $options[] = $item['text'];
                    } elseif (is_string($item)) {
                        $options[] = $item;
                    }
                }
            }
        }
        
        // Tampilkan opsi lain dan tidak ada hanya untuk radio, dropdown, checkbox
        if (in_array($this->question_type, ['radio', 'dropdown', 'checkbox'])) {
            if ($this->has_other_option && !in_array('Lainnya, sebutkan!', $options)) {
                $options[] = 'Lainnya, sebutkan!';
            }
            
            if ($this->has_none_option && !in_array('Tidak Ada', $options)) {
                $options[] = 'Tidak Ada';
            }
        }
        
        return $options;
    }

    /**
     * Check if question type supports multiple selection
     */
    public function getSupportsMultipleAttribute(): bool
    {
        return $this->question_type === 'checkbox';
    }

    /**
     * Get type label for display
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_MAPPING[$this->question_type] ?? $this->question_type;
    }

    /**
     * Check if question type supports other/none options
     */
    public function getSupportsOtherNoneAttribute(): bool
    {
        return in_array($this->question_type, ['radio', 'dropdown', 'checkbox']);
    }

    /**
     * Get available scale options with labels
     */
    public function getScaleOptionsWithLabelsAttribute(): array
    {
        // Jika sudah array langsung (dari seeder)
        if (is_array($this->scale_options)) {
            $options = $this->scale_options;
        } 
        // Jika string (JSON)
        elseif (is_string($this->scale_options)) {
            $options = json_decode($this->scale_options, true) ?? [];
        }
        // Default untuk likert per baris
        else {
            $options = [1, 2, 3, 4, 5];
        }
        
        $labels = [];
        
        // Untuk likert per baris, gunakan angka saja
        if ($this->question_type === 'likert_per_row') {
            foreach ($options as $value) {
                $labels[$value] = (string)$value;
            }
        } else {
            foreach ($options as $value) {
                if ($value == 1) {
                    $labels[$value] = $this->scale_label_low ?? 'Sangat Rendah';
                } elseif ($value == 5) {
                    $labels[$value] = $this->scale_label_high ?? 'Sangat Tinggi';
                } else {
                    $labels[$value] = (string)$value;
                }
            }
        }
        
        return $labels;
    }

    /**
     * Get scale information for display
     */
    public function getScaleInformationArrayAttribute(): array
    {
        if (!$this->scale_information) {
            return [];
        }

        if (is_array($this->scale_information)) {
            return $this->scale_information;
        }

        if (is_string($this->scale_information)) {
            return json_decode($this->scale_information, true) ?? [];
        }

        return [];
    }

    /**
     * Get formatted scale information
     */
    public function getFormattedScaleInformationAttribute(): array
    {
        $information = $this->scale_information_array;
        $formatted = [];

        foreach ($information as $scale => $desc) {
            $formatted[] = "Skala {$scale}: {$desc}";
        }

        return $formatted;
    }

    /**
     * Check if question type is per-row (likert per baris)
     */
    public function getIsPerRowAttribute(): bool
    {
        return $this->question_type === 'likert_per_row';
    }

    /**
     * Get formatted options for display
     */
    public function getFormattedOptionsAttribute()
    {
        if (!$this->options) {
            return [];
        }
        
        $options = is_string($this->options) ? json_decode($this->options, true) : $this->options;
        if (!is_array($options)) {
            return [];
        }
        
        $formatted = [];
        foreach ($options as $option) {
            if (is_array($option) && isset($option['text'])) {
                $formatted[] = $option['text'];
            } else {
                $formatted[] = $option;
            }
        }
        
        return $formatted;
    }

    /**
     * Get row items for display
     */
    public function getFormattedRowItemsAttribute()
    {
        if (!$this->row_items) {
            return [];
        }
        
        // Jika sudah array langsung (dari seeder), kembalikan langsung
        if (is_array($this->row_items)) {
            $formatted = [];
            foreach ($this->row_items as $key => $value) {
                $formatted[] = [
                    'key' => $key,
                    'label' => is_array($value) ? ($value['text'] ?? $value) : $value
                ];
            }
            return $formatted;
        }
        
        // Jika string (JSON), decode
        if (is_string($this->row_items)) {
            $rowItems = json_decode($this->row_items, true) ?? [];
            if (!is_array($rowItems)) {
                return [];
            }
            
            $formatted = [];
            foreach ($rowItems as $key => $value) {
                $formatted[] = [
                    'key' => $key,
                    'label' => is_array($value) ? ($value['text'] ?? $value) : $value
                ];
            }
            
            return $formatted;
        }
        
        return [];
    }

    /**
     * Check if question is numeric input
     */
    public function getIsNumericAttribute(): bool
    {
        return $this->question_type === 'number';
    }

    /**
     * Check if question is date input
     */
    public function getIsDateAttribute(): bool
    {
        return $this->question_type === 'date';
    }

    /**
     * Check if question is textarea
     */
    public function getIsTextareaAttribute(): bool
    {
        return $this->question_type === 'textarea';
    }

    /**
     * Get validation rules as array
     */
    public function getValidationRules(): array
    {
        $rules = [];
        
        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }
        
        switch ($this->question_type) {
            case 'text':
            case 'textarea':
                if ($this->max_length) {
                    $rules[] = 'max:' . $this->max_length;
                }
                $rules[] = 'string';
                break;
                
            case 'number':
                $rules[] = 'numeric';
                if ($this->min_value !== null) {
                    $rules[] = 'min:' . $this->min_value;
                }
                if ($this->max_value !== null) {
                    $rules[] = 'max:' . $this->max_value;
                }
                break;
                
            case 'date':
                $rules[] = 'date';
                break;
                
            case 'checkbox':
                $rules[] = 'array';
                if ($this->max_selections) {
                    $rules[] = 'max:' . $this->max_selections;
                }
                break;
                
            case 'radio':
            case 'dropdown':
                if (is_array($this->available_options) && count($this->available_options) > 0) {
                    $rules[] = 'in:' . implode(',', array_map(function($option) {
                        return is_array($option) && isset($option['text']) ? $option['text'] : $option;
                    }, $this->available_options));
                }
                break;
                
            case 'likert_scale':
            case 'competency_scale':
                $rules[] = 'numeric';
                $rules[] = 'min:1';
                $rules[] = 'max:5';
                break;
                
            case 'likert_per_row':
                $rules[] = 'array';
                if ($this->is_required) {
                    $rules[] = 'required';
                    // Validasi bahwa semua item harus diisi
                    $rules[] = function($attribute, $value, $fail) {
                        $rowItems = $this->row_items;
                        if (is_string($rowItems)) {
                            $rowItems = json_decode($rowItems, true) ?? [];
                        }
                        
                        foreach ($rowItems as $key => $item) {
                            if (!isset($value[$key]) || empty($value[$key])) {
                                $itemText = is_array($item) ? ($item['text'] ?? $item) : $item;
                                $fail("Harap isi skala untuk: {$itemText}");
                            }
                        }
                    };
                }
                break;
        }
        
        return $rules;
    }

    /**
     * Get validation messages for this question
     */
    public function getValidationMessages(): array
    {
        $messages = [
            'required' => 'Pertanyaan ini wajib diisi.',
            'max' => [
                'string' => 'Jawaban tidak boleh lebih dari :max karakter.',
                'numeric' => 'Nilai tidak boleh lebih dari :max.',
                'array' => 'Tidak boleh memilih lebih dari :max pilihan.',
            ],
            'min' => [
                'numeric' => 'Nilai tidak boleh kurang dari :min.',
            ],
            'numeric' => 'Harap masukkan angka yang valid.',
            'date' => 'Harap masukkan tanggal yang valid.',
            'in' => 'Pilihan tidak valid.',
        ];
        
        if ($this->question_type === 'likert_per_row' && $this->is_required) {
            $messages['required'] = 'Harap isi semua skala untuk pertanyaan ini.';
        }
        
        return $messages;
    }
}