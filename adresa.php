<!-- adresa.php -->

<?php
session_start(); // Pokreni sesiju

// Provera da li je korisnik prijavljen
if (isset($_SESSION['id_korisnika'])) {
    // Povezivanje sa bazom podataka
    $db = mysqli_connect("localhost", "root", "", "diplomski");

    // Dobijanje ID korisnika iz sesije
    $id_korisnika = $_SESSION['id_korisnika'];

    // Dohvatanje trenutne adrese korisnika iz baze podataka
    $query = "SELECT adresa FROM Korisnik WHERE id_korisnika = $id_korisnika";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $trenutna_adresa = $row['adresa'];
    } else {
        // Neuspešno dohvatanje adrese iz baze
        // Možete postaviti podrazumevane vrednosti ili prikazati poruku o grešci
        $trenutna_adresa = "Nepoznato";
    }
} else {
    // Korisnik nije prijavljen
    // Možete obraditi ovo kako želite
    $trenutna_adresa = "Niste prijavljeni";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nova_adresa'])) {
        // Dobijanje nove adrese iz forme
        $nova_adresa = $_POST['nova_adresa'];

        // Provera da li je korisnik prijavljen
        if (isset($_SESSION['id_korisnika'])) {
            // Ažuriranje adrese u bazi podataka
            $query = "UPDATE Korisnik SET adresa = '$nova_adresa' WHERE id_korisnika = $id_korisnika";
            mysqli_query($db, $query);

            // Ažuriranje trenutne adrese nakon promene
            $trenutna_adresa = $nova_adresa;
            // Nakon uspešne promene šifre
            $_SESSION['obavestenje1'] = "Uspešno ste promenili adresu!";

            // Preusmeravanje na moj-nalog.korisnik.php nakon ažuriranja adrese
            header("Location: moj-nalog.korisnik.php");
            exit();
        } else {
            // Korisnik nije prijavljen, obradi ovo kako želite
            echo "Morate biti prijavljeni da biste promenili adresu.";
        }
    } else {
        // Nijedna nova adresa nije poslata, obradi ovo kako želite
        echo "<div class='alert alert-success text-center'>Molimo vas da unesete novu adresu. </div>";
    }
}
?>
<?php
if (isset($id_korisnika)) {

    // Napravite SQL upit za dohvatanje imena i prezimena korisnika na osnovu id_korisnika
    $sql = "SELECT ime, prezime FROM korisnik WHERE id_korisnika = '$id_korisnika'";

    // Izvršite SQL upit
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Dohvatite rezultat upita
        $row = mysqli_fetch_assoc($result);
        $imeKorisnika = $row['ime'];
        $prezimeKorisnika = $row['prezime'];
    }
  }
    // Zatvorite vezu sa bazom podataka
    mysqli_close($db); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> | Promena adrese</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="stilovi.css" rel="stylesheet">
<link rel="icon" href="slike/logo.jpg" type="image/jpg">

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
<nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(5px);">
    <div class="container-fluid">
  <a class="navbar-brand" href="index.php"><img src="slike/logo.jpg" height="65" width="65" alt="Logo"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="header-link collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
         
        </ul>
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <div class="dropdown">
          <?php

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
  <div class="container py-4">
  <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(1px);"">
  <div class="container-fluid py-5 text-center"> 

    <h1 class="  display-10 fw-bold" style="color:black; text-align:center;">PROMENA ADRESE</h1>  <br> <br> 

    <form method="POST" action="adresa.php">
        <div class="form-group">
            <label for="trenutna_adresa" style="font-size:20px;"><b>Vaša trenutna adresa:</b></label>
            <input type="text" class="form-control" id="trenutna_adresa" name="trenutna_adresa" value="<?php echo $trenutna_adresa; ?>" readonly>
        </div> <br>
        <div class="form-group">
            <label for="nova_adresa" style="font-size:20px;"><b>Nova adresa:</b></label>
            <input type="text" class="form-control" id="nova_adresa" name="nova_adresa" required>
        </div><br>
        <button type="submit" class="btn btn-dark">Promeni adresu</button>
    </form>
    </div>
    </div>
</div>
    <footer>
    <div class="py-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(5px);">
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
              <li><a class="footer-link" href="obnova.php">Obnova sezonskih karata</a></li>
              <li><a class="footer-link" href="kupovina.php">Kupovina sezonskih karata</a></li>
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
