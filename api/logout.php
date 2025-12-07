<?php
session_start(); // musimy wznowić sesję, żeby ją zniszczyć

// usuwamy wszystkie zmienne sesyjne
$_SESSION = [];

// niszczymy sesję
session_destroy();

header("Content-Type: application/json");
echo json_encode(["message" => "Wylogowano"]);
?>