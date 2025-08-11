<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // Web
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
                'cover_image'=>'required|image'
            ]);

            $this->categoryService->createCategory($validated);

            return redirect()->route('categories.index')->with('success', 'Category created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return view('categories.edit', compact('category'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('categories.index')->with('error', 'Category not found');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($id)],
                'cover_image'=>'nullable|image'

            ]);

            $this->categoryService->updateCategory($category, $validated);

            return redirect()->route('categories.index')->with('success', 'Category updated successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('categories.index')->with('error', 'Category not found');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            $this->categoryService->deleteCategory($category);

            return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('categories.index')->with('error', 'Category not found');
        }
    }

    // API
    public function apiIndex()
    {
        $categories = $this->categoryService->getAllCategories();
        return $this->successResponse($categories, 'Categories retrieved successfully');
    }

    public function apiShow($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return $this->successResponse($category, 'Category retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Category not found', null, 404);
        }
    }

    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
                'cover_image'=>'required|image'

            ]);

            $category = $this->categoryService->createCategory($validated);
            return $this->successResponse($category, 'Category created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($id)],
                'cover_image'=>'nullable|image'

            ]);

            $updated = $this->categoryService->updateCategory($category, $validated);
            return $this->successResponse($updated, 'Category updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Category not found', null, 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        }
    }

    public function apiSearch(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
            ]);

            $filters = $validated;
            $categories = $this->categoryService->filterCategories($filters);
            return $this->successResponse($categories, 'Categories retrieved successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        }
    }

    public function apiDestroy($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            $this->categoryService->deleteCategory($category);
            return $this->successResponse(null, 'Category deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Category not found', null, 404);
        }
    }
}
