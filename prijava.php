<?php
session_start();
if (isset($_SESSION['id_korisnika'])) {
  // Korisnik je već prijavljen
  // Prikazivanje obaveštenja i preusmeravanje na drugu stranicu (na primer, na početnu stranicu)
 
  header("Location: index.php"); // Promenite "pocetna.php" sa željenom stranicom
  exit; // Prekinite izvršavanje skripte
}


?>
  <?php 
          if(isset($_POST['dugme'])){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Prikupljanje podataka iz forme za prijavu
          $email = $_POST["email"];
          $sifra = $_POST["sifra"];
      
          // Povezivanje sa bazom podataka (izmenite sa odgovarajućim podacima)
          $conn = mysqli_connect("localhost", "root", "", "diplomski");
      
          // Provera konekcije
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }
      
          // Provera korisnika u bazi
          $sql = "SELECT * FROM korisnik WHERE email = '$email'";
          
          $result = $conn->query($sql);
      
          if ($result->num_rows > 0) {
            
            $row = $result->fetch_assoc();
            if(password_verify($sifra,$row['sifra'])){
              $_SESSION['id_korisnika'] = $row['id_korisnika'];
            }            
              // Korisnik je uspešno prijavljen, možete preusmeriti na željenu stranicu      
              // Dobijanje reda rezultata kao asocijativnog niza
              
      
              // Postavljanje sesijskih promenljivih
              $_SESSION['email'] = $email;
              $_SESSION['id_korisnika'] = $row['id_korisnika'];
      
             
      
              // Dohvatanje naziva tipa korisnika
              $id_tipa = $row['id_tipa'];
              $sql = "SELECT naziv_tipa FROM tip_korisnika WHERE id_tipa = $id_tipa";
              $result_tip = $conn->query($sql);
      
              if ($result_tip->num_rows > 0) {
                  $row_tip = $result_tip->fetch_assoc();
                  $naziv_korisnika = $row_tip['naziv_tipa'];
      
                  // Preusmeravanje na odgovarajuću stranicu na osnovu naziva tipa korisnika
                  if ($naziv_korisnika == "korisnik") {
                      header("Location: moj-nalog.korisnik.php");
                  } elseif ($naziv_korisnika == "administrator") {
                      header("Location: moj-nalog.administrator.php");
                  } else {
                      // Nepoznat tip korisnika, obradi po potrebi
                      echo "Nepoznat tip korisnika.";
                  }
                  exit;
              } else {
                  // Nepoznat tip korisnika
                  echo "Nepoznat tip korisnika.";
              }
          } else {
              // Pogrešan email ili šifra
              $obavestenje = "Pogrešan emaiil ili šifra!"; // Postavi obaveštenje ako postoje prazna polja

          }
      
          // Zatvaranje konekcije
          $conn->close();
      }
    }
        ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>| Prijava</title>

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
              
              <button class="btn btn-dark btn-floating dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i>
              </button>
              <ul style="background: rgba(174, 220, 239, 0.9); backdrop-filter: blur(1px);" class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"> 
                <li><a class="dropdown-item" href="prijava.php" style="color:rgb(7, 122, 166);"><b>Prijavi se</b></a></li> 
                <li><a class="dropdown-item" href="registracija.php"  style="color:rgb(7, 122, 166);" ><b>Registruj se</b></a></li>
              </ul>
            </div>
          </ul>
          
          
        
        </div>
      
      </div>
    </nav>
    <section id="naslov">
      <div class="container py-5" >
        <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(1px);">
          <div class="container-fluid py-5 text-center"> 
          <h1 class="  display-10 fw-bold" style="color:black;">PRIJAVA</h1>  <br> <br>
          <form action="prijava.php" method="POST">
          <div class="mb-3">
              <label for="email" class="form-label" style="font-size:20px;"><b>Email</b></label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Unesite Vaš email" required>
          </div>
          <div class="mb-3" >
              <label for="sifra" class="form-label" style="font-size:20px;"><b>Šifra</b></label>
              <input type="password" class="form-control" id="sifra" name="sifra" placeholder="Unesite Vašu šifru" required>
          </div>
        
          <button type="submit" class="btn btn-dark" name="dugme">Prijavi se</button>
          </form>
          <br> <b>
          <p><i>Ukoliko nemaš nalog,</i> <a href="registracija.php">REGISTRUJ SE</a> <i> vrlo lako!</i> </p></b>
          
          </div>
        
         <?php
    if (isset($_SESSION['obavestenje1'])) {
    echo '<div class="alert alert-success text-center" style="color: green; font-size:20px;">' . $_SESSION['obavestenje1'] . '</div>';
    // Očistite obaveštenje iz sesije kako se ne bi prikazivalo ponovo nakon osvežavanja stranice
    unset($_SESSION['obavestenje1']);
    }
    ?><?php
    // Proverite da li postoji obaveštenje i prikažite ga ako postoji
    if (isset($obavestenje)) {
        echo '<div class="alert alert-danger text-center" style="color:font-size: 25px;"><b>' . $obavestenje . '</b></div>';
    }
    ?>

        </div>
       
        </div>
        
      </div>
    </section> 

    </section> 
    <br> <br> <br>
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