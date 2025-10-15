# Aplikacja CRUD - Zarządzanie produktami

Jest to aplikacja end-to-end, która pozwala zarządzać listą produktów.

## Jak uruchomić projekt

### Uruchomienie lokalne (z XAMPP)

1.  Uruchom XAMPP (moduły Apache i MySQL).
2.  Skopiuj folder projektu `crud-produkty` do katalogu `htdocs`.
3.  Otwórz `http://localhost/phpmyadmin/` i stwórz nową bazę danych o nazwie `produkty_db`.
4.  Wykonaj zapytanie z pliku `migracja.sql`, aby stworzyć tabelę `products`.
5.  Otwórz w przeglądarce adres `http://localhost/crud-produkty/`.

## Opis endpointów (API)

*   **`GET /api/entities`**: Pobiera listę wszystkich produktów.
*   **`GET /api/entities/{id}`**: Pobiera jeden produkt o podanym ID.
*   **`POST /api/entities`**: Dodaje nowy produkt. Wymagane ciało żądania w JSON: `{"name": "...", "description": "...", "price": ...}`.
*   **`PUT /api/entities/{id}`**: Aktualizuje dane produktu o podanym ID.
*   **`DELETE /api/entities/{id}`**: Usuwa produkt o podanym ID.