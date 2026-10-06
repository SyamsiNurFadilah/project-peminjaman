<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request): View
    {
        $categories = ['Lab', 'Perpustakaan', 'Alat Himpunan'];

        $query = Item::query();

        if ($request->filled('q')) {
            $search = $request->string('q')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && in_array($request->input('category'), $categories, true)) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['available', 'empty', 'maintenance'], true)) {
            $query->where('status', $request->input('status'));
        }

        $items = $query->orderBy('name')->paginate(9)->withQueryString();

        $stats = [
            'total_items' => Item::count(),
            'available_count' => Item::where('status', 'available')->where('available_stock', '>', 0)->count(),
            'categories_count' => count($categories),
        ];

        return view('catalog.index', [
            'items' => $items,
            'categories' => $categories,
            'selectedCategory' => $request->input('category', ''),
            'selectedStatus' => $request->input('status', ''),
            'searchQuery' => $request->input('q', ''),
            'stats' => $stats,
        ]);
    }

    /**
     * Display the specified inventory item.
     */
    public function show(Item $item): View
    {
        $item->load(['borrowings' => function ($query) {
            $query->latest()->limit(5);
        }]);

        return view('catalog.show', [
            'item' => $item,
        ]);
    }
}
