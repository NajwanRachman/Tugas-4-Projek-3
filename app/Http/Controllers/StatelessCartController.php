<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class StatelessCartController extends Controller
{
    public function index()
    {
        $products = Barang::all();

        return view('stateless-shop', compact('products'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'cart' => ['present', 'array'],
            'cart.*' => ['integer', 'min:1', 'max:99'],
            'product_id' => ['required', 'integer', 'exists:barang,id'],
        ]);

        $cart = $data['cart'];
        $id = (int) $data['product_id'];

        $cart[$id] = min(99, ($cart[$id] ?? 0) + 1);

        return response()->json($this->calculate($cart));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'cart' => ['required', 'array'],
            'cart.*' => ['integer', 'min:1', 'max:99'],
        ]);

        return response()->json(
            $this->calculate($data['cart'])
        );
    }

    private function calculate(array $cart): array
    {
        $items = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $barang = Barang::find($id);

            if (!$barang) {
                continue;
            }

            $subtotal = $barang->harga * $quantity;

            $items[] = [
                'id' => (int) $id,
                'name' => $barang->nama,
                'price' => $barang->harga,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }
}
