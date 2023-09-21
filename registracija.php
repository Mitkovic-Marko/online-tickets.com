<?php
session_start(); // Otvorite sesiju na vrhu svake stranice

// Proverite da li je korisnik prijavljen
if (isset($_SESSION['id_korisnika'])) {
    // Korisnik je već prijavljen
    // Prikazivanje obaveštenja i preusmeravanje na drugu stranicu (na primer, na početnu stranicu)
    header("Location: index.php"); // Promenite "pocetna.php" sa željenom stranicom
    exit; // Prekinite izvršavanje skripte
}

// Nastavite sa uspostavljanjem veze sa bazom podataka i ostatak koda za registraciju...
?>
<?php
// Povezivanje sa bazom podataka (izmenite sa odgovarajućim podacima)
$conn = mysqli_connect("localhost", "root", "", "diplomski");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST['dugme'])){
if (isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['email']) && isset($_POST['sifra']) && isset($_POST['telefon']) && isset($_POST['adresa'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];
   $sifra = $_POST['sifra'];
    $telefon = $_POST['telefon'];
    $adresa = $_POST['adresa'];
    $tip_korisnika = $_POST["tip_korisnika"];

    if ($ime != "" && $prezime != "" && $email != "" && $sifra != "" && $telefon != "" && $adresa != "") {
        // SQL upit za unos podataka u tabelu Tip_korisnika
        $sql_tip = "INSERT INTO Tip_korisnika (naziv_tipa) VALUES ('$tip_korisnika')";

        if ($conn->query($sql_tip) === TRUE) {
            // Uspešno unet tip korisnika
            // Sada možemo dohvatiti ID unetog tipa korisnika
            $tip_korisnika_id = $conn->insert_id;
            $hash_password=password_hash($sifra, PASSWORD_DEFAULT);
            // SQL upit za unos podataka u tabelu Korisnik
            $sql_korisnik = "INSERT INTO Korisnik (id_tipa, ime, prezime, email, sifra, telefon, adresa)
                            VALUES ('$tip_korisnika_id', '$ime', '$prezime', '$email', '$hash_password', '$telefon', '$adresa')";

            if ($conn->query($sql_korisnik) === TRUE) {
                // Uspešno unet korisnik
                // Preusmeravanje na prijava.php
                header("Location: prijava.php");
                $_SESSION['obavestenje1'] = "Uspešno ste se registrovali!";
                exit;
            }
        }
    } else {
        $obavestenje = "Popunite sva polja"; // Postavi obaveštenje ako postoje prazna polja
    }
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>| Registracija</title>
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
  <nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(5px);">
    <div class="container-fluid">
  <a class="navbar-brand" href="index.php"><img src="slike/logo.jpg" height="65" width="65" alt="Logo"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="header-link collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li>
            <a class="nav-link " href="index.php">Početna</a>
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
           $sql = "SELECT ime, prezime FROM korisnik WHERE id_korisnika = '$id_korisnika'";

           // Izvršite SQL upit
           $result = mysqli_query($conn, $sql);
       
           if (mysqli_num_rows($result) > 0) {
               // Dohvatite rezultat upita
               $row = mysqli_fetch_assoc($result);
               $imeKorisnika = $row['ime'];
               $prezimeKorisnika = $row['prezime'];
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
    <div class="container py-5">
      <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(1px);">
        <div class="container-fluid py-5 text-center">
          <h1 class="  display-10 fw-bold" style="color:black;">REGISTRACIJA</h1>  <br> <br>
          <form  action="registracija.php" method="POST" class="row g-3 needs-validation" novalidate onsubmit="return validateForm();" id="registration-form">
            <div class="col-md-4">
                <label for="ime" class="form-label" style="font-size:20px;"><b>*Ime</b></label>
                <input type="text" class="form-control" id="ime" name="ime" placeholder="Unesite Vaše ime" required>
            </div>
            <div class="col-md-4">
                <label for="prezime" class="form-label" style="font-size:20px;"><b>*Prezime</b></label>
                <input type="text" class="form-control" id="prezime" name="prezime" placeholder="Unesite Vaše prezime" required>
            </div>
            <div class="col-md-4">
                <label for="email" class="form-label" style="font-size:20px;"><b>*Email</b></label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Unesite Vaš email" required>
            </div>
            <div class="col-md-4">
                <label for="sifra" class="form-label" style="font-size:20px;"><b>*Šifra</b></label>
                <input type="password" class="form-control" id="sifra" name="sifra" placeholder="Unesite Vašu Šifru" required>
            </div>
            <div class="col-md-4">
                <label for="adresa" class="form-label" style="font-size:20px;"><b>*Telefon</b></label>
                <input type="text" class="form-control" id="telefon" name="telefon" placeholder="Unesite Vaš broj telefona" required>

            </div>
            
           
            <div class="col-md-4">
                <label for="brtel" class="form-label" style="font-size:20px;"><b>*Adresa</b></label>
                <input type="text" class="form-control" id="adresa" name="adresa" placeholder="Unesite Vašu adresu " required>
               
            </div>
            
            <div class="col-12">
                <button  id="registrujSeBtn" class="btn btn-dark" type="submit" name="dugme">Registruj se</button>
            </div>
            <div class="col-12">
                <!-- Obaveštenje će biti prikazano ovde -->
                <?php
                // Proverite da li postoji obaveštenje i prikažite ga ako postoji
                if (isset($obavestenje)) {
                    echo '<div class="alert alert-danger text-center" style="font-size: 25px;"><b>' . $obavestenje . '</b></div>';
                }
                ?>
            </div>
            <input type="hidden" name="tip_korisnika" value="korisnik">
            <div class="col-12">

            </div>
          </form>     
        </div>
      </div>
    </div>
  

  <br> <br> <br>
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
<script>
        // Funkcija za validaciju forme
        function validateForm() {
            var ime = document.getElementById('ime').value;
            var prezime = document.getElementById('prezime').value;
            var email = document.getElementById('email').value;
            var sifra = document.getElementById('sifra').value;
            var telefon = document.getElementById('telefon').value;
            var adresa = document.getElementById('adresa').value;

            if (ime === '' || prezime === '' || email === '' || sifra === '' || telefon === '' || adresa === '') {
                // Ako nisu popunjena sva polja, prikaži obaveštenje
                document.getElementById('obavestenje').innerHTML = '<div class="alert alert-danger text-center" font-size: 25px;"><b>Popunite sva polja!</b></div>';
                return false; // Zaustavi slanje forme
            }
            return true; // Nastavi slanje forme ako su sva polja popunjena
        }
    </script>
