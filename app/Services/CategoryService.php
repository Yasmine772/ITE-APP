<?php
namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }

    public function createCategory(array $data)
    {
          if (isset($data['cover_image'])) {
            $data['cover_image'] = $data['cover_image']->store('category_covers', 'public');
        }
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data)
    {
          if (isset($data['cover_image'])) {
            if ($category->cover_image && Storage::disk('public')->exists($category->cover_image)) {
                Storage::disk('public')->delete($category->cover_image);
            }

            $data['cover_image'] = $data['cover_image']->store('category_covers', 'public');
        }
        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category)
    {
        
        if ($category->cover_image && Storage::disk('public')->exists($category->cover_image)) {
            Storage::disk('public')->delete($category->cover_image);
        }
        return $category->delete();
    }

    public function filterCategories(array $filters)
    {
        $query = Category::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        return $query->get();
    }
}
