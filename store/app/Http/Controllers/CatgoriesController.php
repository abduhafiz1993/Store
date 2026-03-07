<?php

namespace App\Http\Controllers;
use App\Models\Catgories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request\StoreCatgoriesRequest;
use Illuminate\Http\Request\UpdateCatgoriesRequest;


use Illuminate\Http\Request;

class CatgoriesController extends Controller
{
    //
 public function index(): View
    {
        $categories = Catgories::latest()->paginate(15);
        return view('Categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('Categories.create');
    }

    public function store(StoreCatgoriesRequest $request): RedirectResponse
    {
        Catgories::create($request->validated());
        return redirect()->route('Categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Catgories $category): View
    {
        return view('Categories.show', compact('category'));
    }
    public function edit(Catgories $category): View
    {
        return view('Categories.edit', compact('category'));
    }
    public function update(UpdateCatgoriesRequest $request, Catgories $category): RedirectResponse
    {
        $category->update($request->validated());
        return redirect()->route('Categories.index')
            ->with('success', 'Category updated successfully.');
    }
    public function destroy(Catgories $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('Categories.index')
            ->with('success', 'Category deleted successfully.');
    }

}
