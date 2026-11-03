@extends('layouts.app')

@section('title', 'Таблица')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Таблица данных</h1>
        <a href="{{ route('feedback.create') }}" class="btn">Добавить запись</a>
    </div>

    @if (count($records) > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Дата</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Тема</th>
                        <th>Сообщение</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $item)
                        <tr>
                            <td><code>{{ $item['_filename'] }}</code></td>
                            <td>{{ $item['created_at'] ?? '' }}</td>
                            <td>{{ $item['name'] ?? '' }}</td>
                            <td><a href="mailto:{{ $item['email'] ?? '' }}">{{ $item['email'] ?? '' }}</a></td>
                            <td>{{ $item['topic'] ?? '' }}</td>
                            <td>{{ $item['message'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="empty-message">Данных пока нет.</p>
    @endif
</div>
@endsection
