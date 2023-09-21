<?php
session_start();
    if (isset($_SESSION['id_korisnika'])) {
        $id_korisnika = $_SESSION['id_korisnika'];
    
        // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
        $conn = mysqli_connect("localhost", "root", "", "diplomski");
    
        // Proverite da li je veza uspostavljena
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
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
    <title> | Pregled računa</title>
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
                    if (isset($_SESSION['id_korisnika'])) {
                        // Korisnik je prijavljen, prikažite opciju "Moj nalog" i "Odjavi se"
                        echo '<button class="btn btn-dark btn-floating dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i> ' . $imeKorisnika . ' ' . $prezimeKorisnika . '
                        </button>
                            <ul style="background: rgba(174, 220, 239, 0.9); backdrop-filter: blur(1px);" class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"> 
                                <li><a class="dropdown-item" href="moj-nalog.administrator.php" style="color:rgb(7, 122, 166);"><b>Moj nalog</b></a></li> 
                                <li><a class="dropdown-item" href="odjava.php" style="color:rgb(7, 122, 166);"><b>Odjavi se</b></a></li>
                            </ul>';
                    } else {
                        // Korisnik nije prijavljen, prikažite opcije "Prijavi se" i "Registruj se"
                        echo '<button class="btn btn-primary btn-floating dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
  <div class="container py-5">
  <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.9); backdrop-filter: blur(4px);">
  <div class="container-fluid py-5 text-center"> 

    <h1 class="  display-10 fw-bold" style="color:black; text-align:center;">PREGLED SVIH RAČUNA </h1>  <br> <br> 
    <?php
if (isset($_SESSION['id_korisnika'])) {
    // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
    $conn = mysqli_connect("localhost", "root", "", "diplomski");

    // Proverite da li je veza uspostavljena
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Napravite SQL upit za dohvatanje računa sa svim potrebnim informacijama
    $sql = "SELECT K.ime, K.prezime, K.email, K.adresa, K.telefon, KK.naziv_kategorije, KR.broj_karata, R.datum_kupovine, R.ukupan_iznos
            FROM Korisnik K
            INNER JOIN Korpa KR ON K.id_korisnika = KR.id_korisnika
            INNER JOIN Racun R ON KR.id_korpe = R.id_korpe
            INNER JOIN Karta KA ON KR.id_karte = KA.id_karte
            INNER JOIN Kategorija_karte KK ON KA.id_kategorije = KK.id_kategorije";

    // Izvršite SQL upit
    $result = mysqli_query($conn, $sql);

    // Proverite da li ima rezultata
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table table-striped">';
        echo '<thead style="background-color:#5F9EA0;">';
        echo '<tr>';
        echo '<th>Ime</th>';
        echo '<th>Prezime</th>';
        echo '<th>Email</th>';
        echo '<th>Adresa</th>';
        echo '<th>Telefon</th>';
        echo '<th>Naziv kategorije</th>';
        echo '<th>Broj karata</th>';
        echo '<th>Datum kupovine</th>';
        echo '<th>Ukupan iznos</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Prikazivanje podataka o računima u tabeli
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['ime'] . '</td>';
            echo '<td>' . $row['prezime'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['adresa'] . '</td>';
            echo '<td>' . $row['telefon'] . '</td>';
            echo '<td>' . $row['naziv_kategorije'] . '</td>';
            echo '<td>' . $row['broj_karata'] . '</td>';
            echo '<td>' . $row['datum_kupovine'] . '</td>';
            echo '<td>' . $row['ukupan_iznos'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'Nema računa u bazi.';
    }

    // Zatvorite vezu sa bazom podataka
    mysqli_close($conn);
} else {
    // Korisnik nije prijavljen, možete ga preusmeriti ili prikazati poruku
    header("Location: prijava.php"); // Preusmeravanje na prijavu
    exit;
}

?>


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
