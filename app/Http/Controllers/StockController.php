<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user();

        $stockBalance = Product::query()
            ->select('products.*')
            ->selectSub(
                DB::table('stock_movements')
                    ->selectRaw('COALESCE(SUM(CASE WHEN type = "entry" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "exit" THEN quantity ELSE 0 END), 0)')
                    ->whereColumn('product_id', 'products.id'),
                'stock'
            )
            ->paginate(10, ['*'], 'stockBalance');

        $stockByCategory = Product::query()
            ->select('categories.name as category', DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "entry" THEN stock_movements.quantity ELSE -stock_movements.quantity END), 0) as total_stock'))
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('stock_movements', 'products.id', '=', 'stock_movements.product_id')
            ->groupBy('categories.name')
            ->paginate(10, ['*'], 'stockByCategory');

        $stockBySupplier = Product::query()
            ->select('suppliers.name as supplier', DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "entry" THEN stock_movements.quantity ELSE -stock_movements.quantity END), 0) as total_stock'))
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->join('stock_movements', 'products.id', '=', 'stock_movements.product_id')
            ->groupBy('suppliers.name')
            ->paginate(10, ['*'], 'stockBySupplier');

        $stockMovements = StockMovement::query()
            ->when($user->role !== 'admin' && $user->role !== 'manager', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('product', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'stockMovements');

        return Inertia::render('StockList/Index', [
            'stockBalance' => $stockBalance,
            'stockByCategory' => $stockByCategory,
            'stockBySupplier' => $stockBySupplier,
            'stockMovements' => $stockMovements,
            'userRole' => $user->role,
        ]);
    }
}
