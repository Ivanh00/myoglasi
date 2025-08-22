<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;

class HomeController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $popularCategories = $this->categoryService->getPopularCategories();

        return view('livewire.home', compact('popularCategories'));
    }
}
