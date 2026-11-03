@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="card">
    <h1>Главная страница</h1>
    <p>Простое веб-приложение на Laravel с сохранением данных в формате JSON.</p>
    <div class="actions">
        <a href="{{ route('feedback.create') }}" class="btn">Перейти к форме</a>
        <a href="{{ route('feedback.index') }}" class="btn btn-secondary">Таблица данных</a>
    </div>
</div>
@endsection
