<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StockMovementController extends Controller
{

    public function index(Request $request): Response
    {
        $query = StockMovement::with('product');

        if ($request->has('product_id') && $request->query('product_id')) {
            $query->where('product_id', $request->query('product_id'));
        }

        $movements = $query->latest()
            ->paginate(10)
            ->through(fn($movement) => [
                'id' => $movement->id,
                'product' => $movement->product->name,
                'type' => $movement->type,
                'quantity' => $movement->quantity,
                'reason' => $movement->reason,
                'created_at' => $movement->created_at->format('Y-m-d H:i:s'),
            ]);

        return Inertia::render('Stocks/Index', [
            'movements' => $movements,
            'products' => Product::query()->select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entry,exit,adjustment',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $quantity = $request->type === 'exit' ? -$request->quantity : $request->quantity;

        $movement = StockMovement::query()->create([
            'product_id' => $request->product_id,
            'type' => $request->type,
            'quantity' => $quantity,
            'reason' => $request->reason,
            'user_id' => auth()->user()->id,
        ]);

        $product = Product::query()->find($request->product_id);
        $product->save();

        return redirect()->route('stocks.index')->with('success', 'Movimentação de estoque registrada com sucesso!');
    }
}
