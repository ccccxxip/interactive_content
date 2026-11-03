@extends('layouts.app')

@section('title', 'Форма')

@section('content')
<div class="card">
    <h1>Форма отправки данных</h1>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong>Пожалуйста, проверьте ошибки:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('feedback.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="topic">Тема:</label>
            <input type="text" id="topic" name="topic" value="{{ old('topic') }}">
        </div>

        <div class="form-group">
            <label for="message">Сообщение:</label>
            <textarea id="message" name="message" rows="4">{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="btn">Отправить</button>
    </form>
</div>
@endsection
