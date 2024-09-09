<?php

use App\Models\Category;

function getCategories()
{
    return Category::orderBy('name', 'ASC')
        ->with('sub_category')
        ->where('status', 1)
        ->where('showHome', 'Yes')
        ->orderBy('id', 'DESC')
        ->get();
}