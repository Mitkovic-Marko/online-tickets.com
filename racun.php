<?php  
require_once('pdf/tcpdf.php');

session_start();
//PHP kod za broj karata u korpi
    // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
    $conn = mysqli_connect("localhost", "root", "", "diplomski");
    
    // Proverite da li je veza uspostavljena
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Dohvatite id_korisnika iz sesije
    $id_korisnika = $_SESSION['id_korisnika'];
    
    // Napravite SQL upit za dohvatanje trenutnog broja proizvoda u korpi za trenutno prijavljenog korisnika
    $sql = "SELECT broj_karata FROM korpa WHERE id_korisnika = $id_korisnika";
    
    // Izvršite SQL upit
    $result = mysqli_query($conn, $sql);
    
    // Inicijalizujte promenljivu za čuvanje broja proizvoda u korpi
    $ukupanBrojProizvoda = 0;
    
    if (mysqli_num_rows($result) > 0) {
        // Dohvatite rezultat upita
        $row = mysqli_fetch_assoc($result);
        $ukupanBrojProizvoda = (int)$row['broj_karata'];
    }
    if (isset($_POST['dugme'])) {
        // Kreirajte novu instancu TCPDF
        $pdf = new TCPDF();
    
        // Postavite izgled dokumenta
        $pdf->SetCreator('Your Name');
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Račun');
        $pdf->SetSubject('Račun');
    
        // Dodajte stranicu
        $pdf->AddPage();
    
        // Dodajte sadržaj PDF-a
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Ime: ' . $_POST['ime'], 0, 1);
        $pdf->Cell(0, 10, 'Prezime: ' . $_POST['prezime'], 0, 1);

        $pdf->Cell(0, 10, 'Email: ' . $_POST['email'], 0, 1);
        $pdf->Cell(0, 10, 'Adresa: ' . $_POST['adresa'], 0, 1);
        $pdf->Cell(0, 10, 'Telefon: ' . $_POST['telefon'], 0, 1);
        $pdf->Cell(0, 10, 'Kategorija karte: ' . $_POST['kategorija'], 0, 1);
        $pdf->Cell(0, 10, 'Broj karata: ' . $_POST['broj'], 0, 1);
        $pdf->Cell(0, 10, 'Datum kupovine: ' . $_POST['datum'], 0, 1);
        $pdf->Cell(0, 10, 'Ukupan iznos: ' . $_POST['ukupan'], 0, 1);
        // Dodajte ostale informacije o računu...
    
        // Generišite PDF fajl
        ob_end_clean(); // Očistite bilo kakav prethodni output
        $pdf->Output('racun.pdf', 'D'); // 'D' znači da će se fajl preuzeti odmah
        exit; // Odmah prekinite izvršenje skripte nakon što ste poslali PDF
    }
    if (isset($id_korisnika)) {

        // Napravite SQL upit za dohvatanje imena i prezimena korisnika na osnovu id_korisnika
        $sql = "SELECT ime, prezime FROM korisnik WHERE id_korisnika = '$id_korisnika'";
      
        // Izvršite SQL upit
        $result = mysqli_query($conn, $sql);
      
        if (mysqli_num_rows($result) > 0) {
            // Dohvatite rezultat upita
            $row = mysqli_fetch_assoc($result);
            $imeKorisnika = $row['ime'];
            $prezimeKorisnika = $row['prezime'];
        }
    // Zatvorite vezu sa bazom podataka
    mysqli_close($conn);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>| Račun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="stilovi.css" rel="stylesheet">
<link rel="icon" href="slike/logo.jpg" type="image/jpg">  
</head>
<body>
<style>
    body{
    
    background-image: url('slike/slika.jpg');
            background-size: cover; /* Da se slika razvuče preko celog ekrana */
            background-repeat: no-repeat;       
}
nav{
    border-bottom: 6px solid rgb(23, 126, 167);
}
footer{
    border-top: 6px solid rgb(23, 126, 167);
}
</style>
<nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(5px);" >
    <div class="container-fluid">
  <a class="navbar-brand" href="index.php"><img src="slike/logo.jpg" height="65" width="65" alt="Logo"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span> 
      </button>
      
      <div class="header-link collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li>
            <a class="nav-link" aria-current="page" href="index.php">Početna</a>
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="kupovina.php" >Kupovina sezonskih karata</a>
          </li>
          <li class="nav-item">
          <a class="nav-link" href="cene.php">Cene karata</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="uputstva.php">Uputstva</a>
          </li>
        </ul>
        
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
       
        <div class="dropdown">
        <?php
   // Otvorite sesiju na vrhu svake stranice

   // Proverite da li je korisnik prijavljen
   if (isset($_SESSION['id_korisnika'])) {
       // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
   
       $conn = mysqli_connect("localhost", "root", "", "diplomski");
   
       // Proverite da li je veza uspostavljena
       if (!$conn) {
           die("Connection failed: " . mysqli_connect_error());
       }
   
       // Dohvatite id_korisnika iz sesije
       $id_korisnika = $_SESSION['id_korisnika'];
   
       // Napravite SQL upit za dohvatanje tipa korisnika na osnovu id_korisnika
       $sql = "SELECT t.naziv_tipa FROM korisnik k
               JOIN tip_korisnika t ON k.id_tipa = t.id_tipa
               WHERE k.id_korisnika = $id_korisnika";
   
       // Izvršite SQL upit
       $result = mysqli_query($conn, $sql);
   
       if (mysqli_num_rows($result) > 0) {
           // Dohvatite tip korisnika
           $row = mysqli_fetch_assoc($result);
           $tipKorisnika = $row['naziv_tipa'];
   
           // Kreirajte promenljivu za ciljani URL za "Moj nalog"
           $mojNalogURL = '';
   
           // Postavite URL u zavisnosti od tipa korisnika
           if ($tipKorisnika == 'korisnik') {
               $mojNalogURL = 'moj-nalog.korisnik.php';
           } elseif ($tipKorisnika == 'administrator') {
               $mojNalogURL = 'moj-nalog.administrator.php';
           }
           // Prikazivanje opcija na osnovu tipa korisnika
           echo '<button class="btn btn-dark m-auto mb-3 btn-floating dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
           <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i> ' . $imeKorisnika . ' ' . $prezimeKorisnika . '
           </button> 
               <ul style="background: rgba(174, 220, 239, 0.9); backdrop-filter: blur(1px);" class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"> 
                   <li><a class="dropdown-item" href="' . $mojNalogURL . '" style="color:rgb(7, 122, 166);"><b>Moj nalog</b></a></li> 
                   <li><a class="dropdown-item" href="odjava.php" style="color:rgb(7, 122, 166);"><b>Odjavi se</b></a></li>
               </ul>';
       } else {
           // Ako nema rezultata ili se desi greška pri upitu, možete postaviti neku podrazumevanu vrednost ili raditi nešto drugo po vašem izboru
           // Na primer:
           // echo "Nije moguće dohvatiti tip korisnika.";
       }
       // Zatvorite vezu sa bazom podataka
       mysqli_close($conn);
   } else {
       // Korisnik nije prijavljen, prikažite opcije "Prijavi se" i "Registruj se"
       echo '<button class="btn btn-dark m-auto mb-3 btn-floating dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
               <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i>
           </button>
           <ul style="background: rgba(174, 220, 239, 0.9); backdrop-filter: blur(1px);" class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"> 
               <li><a class="dropdown-item" href="prijava.php" style="color:rgb(7, 122, 166);"><b>Prijavi se</b></a></li> 
               <li><a class="dropdown-item" href="registracija.php" style="color:rgb(7, 122, 166);"><b>Registruj se</b></a></li>
           </ul>';
   }
    ?>
</div>
      </ul>
     
      </div>
    
    </div>
  </nav>

      <div class="container py-5" >
        <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(1px);">
          <div class="container-fluid py-5 text-center"> 
          <h1 class="  display-10 fw-bold" style="color:black;">Podaci o vašem računu</h1>  <br> <br>
          <form action="racun.php" method="POST">
    <div class="row mb-3">
        <label for="ime" class="col-md-6 col-form-label" style="font-size:20px;"><b>Ime:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="ime" name="ime" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="prezime" class="col-md-6 col-form-label" style="font-size:20px;"><b>Prezime:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="prezime" name="prezime" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="email" class="col-md-6 col-form-label" style="font-size:20px;"><b>Email:</b></label>
        <div class="col-md-3">
            <input type="email" class="form-control" id="email" name="email" readonly>
        </div>
    </div>
   <!-- ... Ostatak vašeg HTML-a ... -->

   <div class="row mb-3">
        <label for="adresa" class="col-md-6 col-form-label" style="font-size:20px;"><b>Adresa:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="adresa" name="adresa" readonly>
        </div>
    </div>


<!-- ... Ostatak vašeg HTML-a ... -->
 
    <div class="row mb-3">
        <label for="telefon" class="col-md-6 col-form-label" style="font-size:20px;"><b>Telefon:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="telefon" name="telefon" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="kategorija" class="col-md-6 col-form-label" style="font-size:20px;"><b>Kategorija karte:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="kategorija" name="kategorija" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="broj" class="col-md-6 col-form-label" style="font-size:20px;"><b>Broj karata:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="broj" name="broj" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="datum" class="col-md-6 col-form-label" style="font-size:20px;"><b>Datum kupovine:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="datum" name="datum" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="ukupan" class="col-md-6 col-form-label" style="font-size:20px;"><b>Ukupan iznos:</b></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="ukupan" name="ukupan" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
        <button class="btn btn-dark" type="submit" style="width:170px;" name="dugme">Preuzmi racun</button>
        </div> 
</div>

</form>

          <?php

// Proverite da li je korisnik prijavljen
if (!isset($_SESSION['id_korisnika'])) {
    // Ako korisnik nije prijavljen, možete preusmeriti na stranicu za prijavu ili obraditi na odgovarajući način.
    header("Location: prijava.php");
    exit;
}

// Povežite se sa bazom podataka (koristite svoje podatke za pristup)
$conn = mysqli_connect("localhost", "root", "", "diplomski");

// Proverite da li je veza uspostavljena
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Dohvatite id_korisnika iz sesije
$id_korisnika = $_SESSION['id_korisnika'];

// Napravite SQL upit za dohvatanje podataka o poslednjem računu korisnika
$sql = "SELECT k.ime, k.prezime, k.email, k.adresa, k.telefon, kk.naziv_kategorije, korpa.broj_karata, racun.datum_kupovine, racun.ukupan_iznos
        FROM korisnik k
        JOIN korpa ON k.id_korisnika = korpa.id_korisnika
        JOIN karta karta ON korpa.id_karte = karta.id_karte
        JOIN kategorija_karte kk ON karta.id_kategorije = kk.id_kategorije
        JOIN racun ON korpa.id_korpe = racun.id_korpe
        WHERE k.id_korisnika = $id_korisnika
        ORDER BY racun.datum_kupovine DESC
        LIMIT 1";

// Izvršite SQL upit
$result = mysqli_query($conn, $sql);

// Proverite da li postoji rezultat
if (mysqli_num_rows($result) > 0) {
    // Dohvatite podatke o računu
    $row = mysqli_fetch_assoc($result);

    // Dohvatite vrednosti iz rezultata
    $ime = $row['ime'];
    $prezime = $row['prezime'];
    $email = $row['email'];
    $adresa = $row['adresa'];
    $telefon = $row['telefon'];
    $naziv_kategorije = $row['naziv_kategorije'];
    $broj_karata = $row['broj_karata'];
    $datum_kupovine = $row['datum_kupovine'];
    $ukupan_iznos = $row['ukupan_iznos'];

    $id_korisnika = $_SESSION['id_korisnika'];

// Napravite SQL upit za dohvatanje adrese_dostave iz korisnika
$sqlAdresa = "SELECT adresa_dostave FROM korisnik WHERE id_korisnika = $id_korisnika";

// Izvršite SQL upit za adresu dostave
$resultAdresa = mysqli_query($conn, $sqlAdresa);

// Inicijalizujte promenljivu za čuvanje adrese


if (mysqli_num_rows($resultAdresa) > 0) {
    // Dohvatite rezultat upita za adresu dostave
    $rowAdresa = mysqli_fetch_assoc($resultAdresa);
    $adresa_dostave = $rowAdresa['adresa_dostave'];

    // Postavite vrednost adrese u zavisnosti od toga da li je adresa dostave prazna
    if (!empty($adresa_dostave)) {
        $adresa = $adresa_dostave;
    } else {
        $adresa = $adresa; // Ako je adresa dostave prazna, koristi vrednost adrese iz korisnika
    }
}
    // Postavite vrednosti polja u formi
    echo '<script>';
    echo 'document.getElementById("ime").value = "' . $ime . '";';
    echo 'document.getElementById("prezime").value = "' . $prezime . '";';
    echo 'document.getElementById("email").value = "' . $email . '";';
    echo 'document.getElementById("adresa").value = "' . $adresa . '";';
    echo 'document.getElementById("telefon").value = "' . $telefon . '";';
    echo 'document.getElementById("kategorija").value = "' . $naziv_kategorije . '";';
    echo 'document.getElementById("broj").value = "' . $broj_karata . '";';
    echo 'document.getElementById("datum").value = "' . $datum_kupovine . '";';
    echo 'document.getElementById("ukupan").value = "' . $ukupan_iznos . '";';
    echo '</script>';
} else {
    // Ako nema rezultata, možete prikazati poruku da korisnik nema prethodnih kupovina ili obraditi na drugi način.
    echo "Nema podataka o prethodnim kupovinama.";
}
// Zatvorite vezu sa bazom podataka
mysqli_close($conn);
?>

          </div>
          <?php
    if (isset($_SESSION['obavestenje'])) {
    echo '<div class="alert alert-success text-center" style="color: green; font-size:20px;">' . $_SESSION['obavestenje'] . '</div>';
    // Očistite obaveštenje iz sesije kako se ne bi prikazivalo ponovo nakon osvežavanja stranice
    unset($_SESSION['obavestenje']);
    }
    ?>
        </div>
      </div>

 
<footer>
    <div class="py-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(5px);" >
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-12 f1">
            <ul class="list-unstyled text-center">
              <h4 class="text-center" style="color: rgb(7, 122, 166);"><b>Kontakt</b></h4>
              <li><i style="color: rgb(7, 122, 166);" class="fas fa-map-marker-alt"></i> Adresa: <span>Vojvode Stepe 158, Beograd</span></li>
              <li><i style="color: rgb(7, 122, 166);" class="fas fa-phone"></i> Telefon: <span><a class="footer-link" href="tel:+11-2351-333">011/2351-333</a></span></li>
              <li><i style="color: rgb(7, 122, 166);" class="fas fa-envelope"></i> E-mail: <span><a class="footer-link" href="mailto:ticketsvukovi@gmail.rs">ticketsvukovi@gmail.rs</a></span></li>
            </ul>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12">
            <ul class="list-unstyled text-center ">
              <h4 style="color: rgb(7, 122, 166);" ><b>Moj nalog</b></h4>
              <li><a class="footer-link" href="prijava.php">Prijavi se</a></li>
              <li><a class="footer-link" href="registracija.php">Registruj se</a></li>
                        </ul>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 f2">
            <ul class="list-unstyled text-center">
              <h4 style="color: rgb(7, 122, 166)"><b>Usluge</b></h4>
              <li><a class="footer-link" href="kupovina.php">Kupovina sezonskih karata</a></li>
              <li><a class="footer-link" href="cene.php">Cene karata</a></li>
              <li><a class="footer-link" href="uputstva.php">Uputstva kupovine</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
<script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/all.min.js"></script> 
</body>
</html>