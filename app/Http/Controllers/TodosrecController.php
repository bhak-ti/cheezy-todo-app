<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todosrec;
use Carbon\Carbon;

class TodosrecController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $todos = Todosrec::orderBy('TODODEADLINEDATE')->get();

        // Format data untuk view
        $todos->transform(function ($todo) {
            $todo->formatted_deadline = $todo->TODODEADLINEDATE
                ? Carbon::parse($todo->TODODEADLINEDATE)->locale('id')->isoFormat('D MMMM Y HH:mm')
                : '-';

            $todo->formatted_finish = $todo->TODOFINISHTIMESTAMP
                ? Carbon::parse($todo->TODOFINISHTIMESTAMP)->locale('id')->isoFormat('D MMMM Y HH:mm')
                : '-';

            $todo->formatted_created = $todo->created_at
                ? Carbon::parse($todo->created_at)->locale('id')->isoFormat('D MMMM Y HH:mm')
                : '-';

            return $todo;
        });

        // Group berdasarkan tanggal deadline (yang diformat untuk key tampilan)
        $groupedTodos = $todos->groupBy(function ($item) {
            return $item->TODODEADLINEDATE
                ? Carbon::parse($item->TODODEADLINEDATE)->locale('id')->isoFormat('dddd, D MMMM Y')
                : 'Tanpa Deadline';
        });

        return view('todos.index', [
            'todos' => $groupedTodos
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'TODODESC' => 'required|string|max:255',
            'TODOFINISHTIMESTAMP' => 'nullable|date',
            'TODODEADLINEDATE' => 'nullable|date',
        ]);

        // Tambahkan default nilai untuk TODOISDONE
        $validatedData['TODOISDONE'] = 0;

        // Laravel otomatis handle created_at dan updated_at, jadi jangan perlu set manual

        Todosrec::create($validatedData);

        return redirect()->route('todos.index')->with('success', 'Todo berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
