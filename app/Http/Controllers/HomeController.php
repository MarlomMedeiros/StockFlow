<?php

namespace App\Http\Controllers;

use App\Models\Product;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Product::class);

        $lowStockProducts = Product::query()
            ->select('products.*')
            ->selectSub(
                DB::table('stock_movements')
                    ->selectRaw('COALESCE(SUM(CASE WHEN type = "entry" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "exit" THEN quantity ELSE 0 END), 0)')
                    ->whereColumn('product_id', 'products.id'),
                'stock'
            )
            ->whereRaw('
            (SELECT COALESCE(SUM(CASE WHEN type = "entry" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "exit" THEN quantity ELSE 0 END), 0)
            FROM stock_movements WHERE product_id = products.id) < products.minimum_stock
        ')
            ->orderBy('stock', 'asc')
            ->paginate(10, ['*'], 'lowStockProducts');

        $nearExpirationProducts = Product::query()
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '>=', now())
            ->orderBy('expiration_date', 'asc')
            ->paginate(10, ['*'], 'nearExpirationProducts');

        return Inertia::render('Home/Index', [
            'lowStockProducts' => $lowStockProducts,
            'nearExpirationProducts' => $nearExpirationProducts,
        ]);
    }
}
