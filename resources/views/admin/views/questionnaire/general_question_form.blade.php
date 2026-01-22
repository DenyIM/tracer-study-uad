{{-- resources/views/admin/views/questionnaire/general_question_form.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', isset($question) ? 'Edit Pertanyaan Umum' : 'Tambah Pertanyaan Umum')
@section('page-title', isset($question) ? 'Edit Pertanyaan Umum' : 'Tambah Pertanyaan Umum')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                {{ isset($question) ? 'Edit' : 'Tambah' }} Pertanyaan Kuesioner Umum
                                <small class="text-muted">- {{ $questionnaire->name }}</small>
                            </h5>
                            <p class="text-muted mb-0">
                                Kategori: {{ $selectedCategory->name }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $selectedCategory->id]) }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-body">
                        @include('admin.views.questionnaire.partials.form', [
                            'question' => $question ?? null,
                            'questions' => $questions,
                            'formType' => 'general',
                            'selectedCategory' => $selectedCategory,
                            'questionnaire' => $questionnaire,
                            'routeStore' => route(
                                'admin.questionnaire.general-questions.store',
                                $questionnaire->id),
                            'routeUpdate' => isset($question)
                                ? route('admin.questionnaire.general-questions.update', [
                                    'questionnaireId' => $questionnaire->id,
                                    'id' => $question->id,
                                ])
                                : null,
                            'routeBack' => route('admin.questionnaire.general-questionnaires', [
                                'category_id' => $selectedCategory->id,
                            ]),
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
