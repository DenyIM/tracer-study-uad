<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\AnswerQuestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class QuestionnaireExport implements WithMultipleSheets
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        if ($this->categoryId) {
            // Export single category
            $category = Category::find($this->categoryId);
            if ($category) {
                $sheets[] = new CategorySheet($category);
            }
        } else {
            // Export all categories
            $categories = Category::with('questionnaires.questions')->get();
            foreach ($categories as $category) {
                $sheets[] = new CategorySheet($category);
            }
        }
        
        // Add summary sheet
        $sheets[] = new SummarySheet($this->categoryId);
        
        return $sheets;
    }
}

class CategorySheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $category;
    
    public function __construct($category)
    {
        $this->category = $category;
    }
    
    public function collection()
    {
        $data = [];
        
        foreach ($this->category->questionnaires as $questionnaire) {
            foreach ($questionnaire->questions as $question) {
                $answers = AnswerQuestion::where('question_id', $question->id)->get();
                
                if ($answers->isEmpty()) {
                    $data[] = [
                        'category' => $this->category->name,
                        'questionnaire' => $questionnaire->name,
                        'question' => $question->question_text,
                        'type' => $question->question_type,
                        'answer' => '-',
                        'alumni' => '-',
                        'answered_at' => '-',
                    ];
                } else {
                    foreach ($answers as $answer) {
                        $data[] = [
                            'category' => $this->category->name,
                            'questionnaire' => $questionnaire->name,
                            'question' => $question->question_text,
                            'type' => $question->question_type,
                            'answer' => $this->getFormattedAnswer($answer),
                            'alumni' => $answer->alumni->fullname ?? '-',
                            'answered_at' => $answer->answered_at ? $answer->answered_at->format('Y-m-d H:i:s') : '-',
                        ];
                    }
                }
            }
        }
        
        return collect($data);
    }
    
    private function getFormattedAnswer($answer): string
    {
        if ($answer->scale_value !== null) {
            return "Skala: {$answer->scale_value}";
        }

        if (!empty($answer->selected_options)) {
            if (is_array($answer->selected_options)) {
                return implode(', ', $answer->selected_options);
            }
            return $answer->selected_options;
        }

        return $answer->answer ?? '-';
    }
    
    public function headings(): array
    {
        return [
            'Kategori',
            'Bagian Kuesioner',
            'Pertanyaan',
            'Tipe Pertanyaan',
            'Jawaban',
            'Nama Alumni',
            'Tanggal Dijawab'
        ];
    }
    
    public function map($row): array
    {
        return [
            $row['category'],
            $row['questionnaire'],
            $row['question'],
            $row['type'],
            $row['answer'],
            $row['alumni'],
            $row['answered_at']
        ];
    }
    
    public function title(): string
    {
        // Excel sheet name max 31 chars
        return mb_substr($this->category->name, 0, 31, 'UTF-8');
    }
}

class SummarySheet implements FromCollection, WithHeadings, WithTitle
{
    protected $categoryId;
    
    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }
    
    public function collection()
    {
        $data = [];
        
        $query = Category::query();
        if ($this->categoryId) {
            $query->where('id', $this->categoryId);
        }
        
        $categories = $query->withCount(['questionnaires', 'alumniStatuses'])->get();
        
        foreach ($categories as $category) {
            $completed = $category->alumniStatuses()->where('status', 'completed')->count();
            $totalAlumni = $category->alumniStatuses()->count();
            $completionRate = $totalAlumni > 0 ? round(($completed / $totalAlumni) * 100, 2) : 0;
            
            $data[] = [
                'Kategori' => $category->name,
                'Total Kuesioner' => $category->questionnaires_count,
                'Total Pertanyaan' => $category->total_questions,
                'Alumni Terdaftar' => $totalAlumni,
                'Alumni Selesai' => $completed,
                'Persentase Penyelesaian' => $completionRate . '%',
                'Status' => $category->is_active ? 'Aktif' : 'Nonaktif',
            ];
        }
        
        return collect($data);
    }
    
    public function headings(): array
    {
        return [
            'Kategori',
            'Total Kuesioner',
            'Total Pertanyaan',
            'Alumni Terdaftar',
            'Alumni Selesai',
            'Persentase Penyelesaian',
            'Status'
        ];
    }
    
    public function title(): string
    {
        return 'Ringkasan';
    }
}