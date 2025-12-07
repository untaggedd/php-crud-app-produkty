<?php
session_start();
// jeśli nie ma sesji - przekierujemy na login.html
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Aplikacja CRUD - Produkty</title>

<style>
    :root {
        --bg-color: #ffffff;
        --text-color: #333333;
        --header-bg: #f8f9fa;
        --container-bg: #f4f4f4;
        --element-bg: #ffffff;
        --border-color: #ddd;
        --input-text: #000000;
        --shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    body.dark-mode {
        --bg-color: #121212;
        --text-color: #e0e0e0;
        --header-bg: #1e1e1e;
        --container-bg: #1e1e1e;
        --element-bg: #2c2c2c;
        --border-color: #444;
        --input-text: #ffffff;
        --shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    body {
        font-family: sans-serif;
        margin: 0;
        padding: 0;
        width: 100%;
        background-color: var(--bg-color);
        color: var(--text-color);
        transition: background-color 0.3s, color 0.3s;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 0 10px;
    }

    .user-header {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 15px 30px;
        background-color: var(--header-bg);
        border-bottom: 1px solid var(--border-color);
        width: 100%;
        box-sizing: border-box;
    }

    .user-name {
        margin-right: 15px;
        font-size: 16px;
    }

    .theme-btn {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-color);
        cursor: pointer;
        margin-right: 15px;
        font-size: 1.2em;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .theme-btn:hover {
        background-color: var(--element-bg);
    }

    form {
        background: var(--container-bg);
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
    }

    input[type="text"],
    input[type="number"],
    input[type="password"],
    textarea {
        width: calc(100% - 22px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background-color: var(--element-bg);
        color: var(--input-text);
    }

    input[type="checkbox"] {
        width: auto;
        margin-right: 10px;
        transform: scale(1.2);
        cursor: pointer;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        cursor: pointer;
    }

    button.action-btn {
        padding: 10px 15px;
        border: none;
        background-color: #007BFF;
        color: white;
        cursor: pointer;
        border-radius: 4px;
    }
    button.action-btn:hover { background-color: #0056b3; }

    .delete-btn { background-color: #dc3545; margin-left: 5px; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;}
    .delete-btn:hover { background-color: #c82333;}

    .logout-btn {
        padding: 8px 15px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    li {
        list-style-type: none;
        border: 1px solid var(--border-color);
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--element-bg);
    }
    ul { padding: 0; }
        
        
        /* bar kursu walut */
   .currency-bar {
        font-weight: bold;
        font-size: 0.95em;
        color: var(--text-color); /* theme adapt */
        background-color: transparent;
        border: none;
        padding: 0;
        margin: 0;
        box-shadow: none;
    }

    /*darm mode adapt*/
    body.dark-mode .currency-bar {
        color: #d4edda;            
        border-color: #155724;
    }
        
        
        .user-header {
        display: flex;              
        justify-content: space-between; /* waluta z lewa, przyciski z prawa */
        align-items: center;        
        padding: 15px 30px;         
        background-color: var(--header-bg); 
        border-bottom: 1px solid var(--border-color);
        width: 100%;
        box-sizing: border-box;
    }
        
        
        .user-controls {
        display: flex;
        align-items: center;
    }
        
</style>

</head>
<body>

    <div id="app" class="container">

        <div style="text-align: right; margin-bottom: 20px;">

            <div class="user-header">
                <div id="currency-bar" class="currency-bar">
                    Ładowanie kursów...
                </div>

                <div class="user-controls">
                    <button class="theme-btn" onclick="toggleTheme()" title="Zmień motyw">
                        🌗
                    </button>

                    <span class="user-name" style="margin-right: 15px;">
                        Zalogowany jako: <strong id="current-username">...</strong>
                    </span>

                    <button class="logout-btn" onclick="logout()">Wyloguj</button>
                </div>
            </div>
                
                
            <form id="product-form">
            <h3>Dodaj / Edytuj produkt</h3>
            <input type="hidden" id="product-id">
            <input type="text" id="name" placeholder="Nazwa produktu" required>
            <textarea id="description" placeholder="Opis produktu"></textarea>
            <input type="number" id="price" placeholder="Cena" step="0.01" required>
            <input type="text" id="sku" placeholder="SKU (np. TSH-BLK-XL)" oninput="this.value = this.value.toUpperCase().replace(/\s/g, '-')">
            
            <div class="checkbox-group">
            <input type="checkbox" id="is_available" checked>
            <label for="is_available" style="cursor: pointer;">Produkt jest dostępny w sprzedaży</label>
            </div>

            <button type="submit" class="action-btn">Zapisz</button>
            </form>

            <div id="products-list">
            <h3>Lista produktów</h3>
            <ul id="list"></ul>
            </div>

    </div>  

    <script>
        // droga do api
        const apiUrl = 'api/entities.php';
        
        const productForm = document.getElementById('product-form');
        const productList = document.getElementById('list');
        const productIdField = document.getElementById('product-id');

        // --- 1. Pobieranie produktów ---
        async function fetchProducts() {
            try {
                // Используем переменную apiUrl
                const response = await fetch(apiUrl);
                
                if (response.status === 401) {
                    window.location.href = 'login.html'; 
                    return;
                }
                
                // sprawdzamy czy serwer nie zwrocil HTML zamiast JSON
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                   throw new Error("Serwer nie zwrócił JSON (prawdopodobnie błąd PHP)");
                }

                const products = await response.json();
                productList.innerHTML = '';
                
                // jeśli nie ma produktow
                if (!Array.isArray(products)) {
                    return; 
                }

                products.forEach(product => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <div>
                            <strong>${product.name}</strong> - ${product.price} PLN
                            <p>${product.description || ''}</p>
                            <p style="color: grey; font-size: 0.9em;">
                                SKU: ${product.sku || 'Brak'} | 
                                Status: ${product.is_available == 1 ? 'Dostępny' : 'Niedostępny'}
                            </p>
                        </div>
                        <div>
                            <button class="action-btn" onclick="editProduct(${product.id}, '${product.name}', '${product.description || ''}', ${product.price}, '${product.sku || ''}', ${product.is_available})">Edytuj</button>
                            <button class="delete-btn" onclick="deleteProduct(${product.id})">Usuń</button>
                        </div>`;
                    productList.appendChild(li);
                });
            } catch (error) {
                console.error("Błąd fetchProducts:", error);
            }
        }

        // --- 2. Obsługa formularza (Dodawanie / Edycja) ---
        productForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const id = productIdField.value;
            
            const productData = {
                id: id ? id : null, // ID wewnętrz JSON
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                sku: document.getElementById('sku').value,
                is_available: document.getElementById('is_available').checked ? 1 : 0
            };

            // jesli ma ID - zaktualizujemy (PUT), jeśli nie - tworzymy (POST)
            const method = id ? 'PUT' : 'POST';

            await fetch(apiUrl, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(productData)
            });
            
            productForm.reset();
            productIdField.value = '';
            fetchProducts();
        });

        // --- 3. Wypełnianie formularza do edycji ---
        function editProduct(id, name, description, price, sku, is_available) {
            productIdField.value = id;
            document.getElementById('name').value = name;
            document.getElementById('description').value = description;
            document.getElementById('price').value = price;
            document.getElementById('sku').value = sku;
            document.getElementById('is_available').checked = (is_available == 1);
            
            // scroll do góry formy
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // --- 4. Usuwanie produktu ---
        async function deleteProduct(id) {
            if (confirm('Czy na pewno chcesz usunąć ten produkt?')) {
                // Отправляем запрос DELETE с ID в теле JSON
                await fetch(apiUrl, { 
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                fetchProducts();
            }
        }

        // --- 5. Wylogowanie ---
        async function logout() {
            await fetch('api/logout.php'); 
            window.location.href = 'login.html';
        }

        // --- 6. Dark Mode ---
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }

        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        }

        // --- 7. Pobranie nazwy użytkownika ---
        async function fetchUserName() {
            try {
                const response = await fetch('api/get_user.php');
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('current-username').innerText = data.username;
                } else {
                     document.getElementById('current-username').innerText = 'Gość';
                }
            } catch (error) {
                console.error('Bląd otrzymania imienia:', error);
            }
        }

            // --- 8. Kursy Walut (Bezpośrednio z przeglądarki) ---
        async function fetchCurrency() {
            const bar = document.getElementById('currency-bar');
            
            // api banku
            const nbpUrl = 'https://api.nbp.pl/api/exchangerates/tables/A/?format=json';

            try {
                const response = await fetch(nbpUrl);
                
                if (!response.ok) throw new Error('Błąd połączenia z NBP');
                
                const dataArray = await response.json();
                const data = dataArray[0]; // pierwsza tabela
                const rates = data.rates;
                const date = data.effectiveDate;

                // znalezimy potrzebującę waluty
                let usd = '???';
                let eur = '???';

                rates.forEach(rate => {
                    if (rate.code === 'USD') usd = rate.mid;
                    if (rate.code === 'EUR') eur = rate.mid;
                });
                
                // malujemy
                bar.innerHTML = `
                    <span style="color: #28a745; margin-right: 10px;">🏦 NBP (${date}):</span> 
                    <span style="margin-right: 15px;">EUR: <b>${eur}</b></span> 
                    <span>USD: <b>${usd}</b></span>
                `;
            } catch (error) {
                console.error("Ошибка валют:", error);
                // jeśli nie ma polączenia - piszemy bląd 
                bar.innerHTML = '<span style="color: grey; font-size: 0.8em;">Kursy niedostępne</span>';
            }
        }
            
        // start 
        fetchProducts();
        fetchUserName();
        fetchCurrency();

    </script>

</body>
</html>