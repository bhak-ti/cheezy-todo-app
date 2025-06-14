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
        $todos = Todosrec::orderBy('TODODEADLINEDATE')
            ->get()
            ->groupBy(function ($item) {
                return $item->TODODEADLINEDATE
                    ? Carbon::parse($item->TODODEADLINEDATE)->format('Y-m-d')
                    : 'Tanpa Deadline';
            });

        return view('todos.index', compact('todos'));
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
