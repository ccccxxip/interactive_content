<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'topic' => 'required',
            'message' => 'required',
        ], [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Поле "Email" должно содержать корректный адрес электронной почты.',
            'topic.required' => 'Поле "Тема" обязательно для заполнения.',
            'message.required' => 'Поле "Сообщение" обязательно для заполнения.',
        ]);

        $validated['created_at'] = date('Y-m-d H:i:s');

        $fileName = 'data_' . uniqid() . '.json';

        Storage::disk('local')->put('submissions/' . $fileName, json_encode($validated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect('/feedback')->with('success', 'Данные успешно сохранены в файл: ' . $fileName);
    }

    public function index()
    {
        $files = Storage::disk('local')->files('submissions');
        $records = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                $content = Storage::disk('local')->get($file);
                $data = json_decode($content, true);

                if ($data) {
                    $data['_filename'] = basename($file);
                    $records[] = $data;
                }
            }
        }

        return view('feedback.index', compact('records'));
    }
}
