<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stateless Shopping Cart</title>
</head>
<body>
    <h1>Toko Online</h1>

    <h2>Daftar Produk</h2>

    @foreach ($products as $product)
        <button type="button"
                onclick="add({{ $product->id }})">
            {{ $product->nama }}
            Rp{{ number_format($product->harga, 0, ',', '.') }}
        </button>
        <br><br>
    @endforeach

    <h2>Keranjang Belanja</h2>

    <div id="items">Keranjang kosong.</div>

    <h3 id="total">Total: Rp0</h3>

    <button type="button" onclick="recalculate()">
        Hitung ulang
    </button>

    <button type="button" onclick="clearCart()">
        Kosongkan Keranjang
    </button>

    <h3>Respons Laravel</h3>
    <pre id="response">Belum ada request.</pre>

    <script>
        let cart = {};

        async function post(url, payload) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Request gagal.'
                );
            }

            return data;
        }

        async function add(id) {
            try {
                const data = await post(
                    '/stateless-cart/add',
                    {
                        cart: cart,
                        product_id: id
                    }
                );

                cart = {};

                data.items.forEach(item => {
                    cart[item.id] = item.quantity;
                });

                render(data);
            } catch (error) {
                alert(error.message);
            }
        }

        async function recalculate() {
            try {
                const data = await post(
                    '/stateless-cart/update',
                    { cart: cart }
                );

                render(data);
            } catch (error) {
                alert(error.message);
            }
        }

        function render(data) {
            const items = document.getElementById('items');
            items.replaceChildren();

            if (data.items.length === 0) {
                items.textContent = 'Keranjang kosong.';
            } else {
                data.items.forEach(item => {
                    const row = document.createElement('p');

                    row.textContent =
                        item.name + ' x ' + item.quantity +
                        ' = Rp' +
                        item.subtotal.toLocaleString('id-ID');

                    items.appendChild(row);
                });
            }

            document.getElementById('total').textContent =
                'Total: Rp' +
                data.total.toLocaleString('id-ID');

            document.getElementById('response').textContent =
                JSON.stringify(data, null, 2);
        }

        function clearCart() {
            cart = {};
            render({ items: [], total: 0 });
        }
    </script>
</body>
</html>
