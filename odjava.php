<?php
session_start(); // Otvorite sesiju

// Proverite da li je korisnik prijavljen
if (isset($_SESSION['id_korisnika'])) {
    // Ako je korisnik prijavljen, obrisi sesiju da se izloguje
    session_destroy();
}

// Preusmeri korisnika na početnu stranicu ili neku drugu stranicu nakon odjave
header("Location: prijava.php"); // Možete promeniti ovu putanju prema vašim potrebama
?>