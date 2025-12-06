<?php
session_start(); // Musimy wznowić sesję, żeby ją zniszczyć

// Usuwamy wszystkie zmienne sesyjne
$_SESSION = [];

// Niszczymy sesję
session_destroy();

header("Content-Type: application/json");
echo json_encode(["message" => "Wylogowano"]);
?>