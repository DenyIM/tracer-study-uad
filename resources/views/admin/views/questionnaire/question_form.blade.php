{{-- resources/views/admin/views/questionnaire/question_form.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', isset($question) ? 'Edit Pertanyaan' : 'Tambah Pertanyaan')
@section('page-title', isset($question) ? 'Edit Pertanyaan' : 'Tambah Pertanyaan')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                {{ isset($question) ? 'Edit' : 'Tambah' }} Pertanyaan
                                <small class="text-muted">- {{ $questionnaire->name }}</small>
                            </h5>
                            <p class="text-muted mb-0">
                                Kategori: {{ $category->name }} | Kuesioner: {{ $questionnaire->name }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.questionnaire.questions', [$category->id, $questionnaire->id]) }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        @include('admin.views.questionnaire.partials.form', [
                            'question' => $question ?? null,
                            'questions' => $questions,
                            'formType' => 'specific',
                            'category' => $category,
                            'questionnaire' => $questionnaire,
                            'routeStore' => route('admin.questionnaire.questions.store', [
                                $category->id,
                                $questionnaire->id,
                            ]),
                            'routeUpdate' => isset($question)
                                ? route('admin.questionnaire.questions.update', [
                                    $category->id,
                                    $questionnaire->id,
                                    $question->id,
                                ])
                                : null,
                            'routeBack' => route('admin.questionnaire.questions', [
                                $category->id,
                                $questionnaire->id,
                            ]),
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
