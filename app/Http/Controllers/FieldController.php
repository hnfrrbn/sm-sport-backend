<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Models\ActivityLog;
use App\Models\Field;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FieldController extends Controller
{
    /**
     * Display a listing of fields
     */
    public function index(): View
    {
        $fields = Field::paginate(10);
        return view('fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new field
     */
    public function create(): View
    {
        return view('fields.create');
    }

    /**
     * Store a newly created field
     */
    public function store(StoreFieldRequest $request)
    {
        $field = Field::create($request->validated());

        ActivityLog::log(
            Auth::user(),
            'create',
            'Field',
            $field->id,
            'Created field: ' . $field->name
        );

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan');
    }

    /**
     * Display a field
     */
    public function show(Field $field): View
    {
        return view('fields.show', compact('field'));
    }

    /**
     * Show the form for editing a field
     */
    public function edit(Field $field): View
    {
        return view('fields.edit', compact('field'));
    }

    /**
     * Update the specified field
     */
    public function update(StoreFieldRequest $request, Field $field)
    {
        $oldValues = $field->toArray();
        $field->update($request->validated());

        ActivityLog::log(
            Auth::user(),
            'update',
            'Field',
            $field->id,
            'Updated field: ' . $field->name,
            $oldValues,
            $field->toArray()
        );

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil diubah');
    }

    /**
     * Delete the specified field
     */
    public function destroy(Field $field)
    {
        $name = $field->name;
        $field->delete();

        ActivityLog::log(
            Auth::user(),
            'delete',
            'Field',
            $field->id,
            'Deleted field: ' . $name
        );

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil dihapus');
    }
}
