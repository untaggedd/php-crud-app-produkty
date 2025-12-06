# Aplikacja CRUD - Zarządzanie produktami (PL)

Jest to aplikacja end-to-end, która pozwala zarządzać listą produktów.

## Jak uruchomić projekt

### Uruchomienie lokalne (z XAMPP)

1.  Uruchom XAMPP (moduły Apache i MySQL).
2.  Skopiuj folder projektu do katalogu `htdocs`.
3.  Otwórz `http://localhost/phpmyadmin/` i stwórz nową bazę danych o nazwie `produkty_db`.
4.  Wykonaj zapytanie z pliku `migracja.sql`, aby stworzyć tabelę `products`.
5.  Otwórz w przeglądarce adres `http://localhost/nazwa_folderu_projektu/`.

## Opis endpointów (API)

*   **`GET /api/entities`**: Pobiera listę wszystkich produktów.
*   **`GET /api/entities/{id}`**: Pobiera jeden produkt o podanym ID.
*   **`POST /api/entities`**: Dodaje nowy produkt. Wymagane ciało żądania w JSON: `{"name": "...", "description": "...", "price": ..., "sku": "...", "is_available": 1}`.
*   **`PUT /api/entities/{id}`**: Aktualizuje dane produktu o podanym ID. Ciało żądania jest takie samo jak przy metodzie POST.
*   **`DELETE /api/entities/{id}`**: Usuwa produkt o podanym ID.

## Rozszerzenie encji o nowe pola (Zadanie B)

Moduł został rozszerzony o dwa dodatkowe pola:

*   **`sku` (VARCHAR)**: Unikalny identyfikator produktu (artykuł). Może być pusty.
*   **`is_available` (BOOLEAN)**: Status dostępności produktu. Przyjmuje wartość `1` (dostępny) lub `0` (niedostępny).