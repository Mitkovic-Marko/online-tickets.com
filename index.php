<?php  
session_start();

// PHP kod za broj karata u korpi
// Povežite se sa bazom podataka (koristite svoje podatke za pristup)
$conn = mysqli_connect("localhost", "root", "", "diplomski");

// Proverite da li je veza uspostavljena
if (!$conn) {
    die("Neuspešna konekcija: " . mysqli_connect_error());
}

// Proverite da li je korisnik prijavljen
if (isset($_SESSION['id_korisnika'])) {
    $id_korisnika = $_SESSION['id_korisnika'];

    // Napravite SQL upit za dohvatanje trenutnog broja proizvoda u korpi za trenutno prijavljenog korisnika
    $sql = "SELECT broj_karata FROM korpa WHERE id_korisnika = '$id_korisnika'";

    // Izvršite SQL upit
    $result = mysqli_query($conn, $sql);

    // Inicijalizujte promenljivu za čuvanje broja proizvoda u korpi
    $ukupanBrojProizvoda = 0;

    if (mysqli_num_rows($result) > 0) {
        // Dohvatite rezultat upita
        $row = mysqli_fetch_assoc($result);
        $ukupanBrojProizvoda = (int)$row['broj_karata'];
    }

    // Zatvorite vezu sa bazom podataka
} else {
    // Korisnik nije prijavljen, postavite $ukupanBrojProizvoda na 0 ili neku podrazumevanu vrednost
    $ukupanBrojProizvoda = 0;
}
?>
<?php
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
  }
    // Zatvorite vezu sa bazom podataka
    mysqli_close($conn); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>| Početna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="slike/logo.jpg" type="image/jpg">
    <link href="stilovi.css" rel="stylesheet">
    
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
            <a class="nav-link active" aria-current="page" href="index.php">Početna</a>
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
        <li class="nav-item">
        <a class="nav-link active" href="korpa.php">
                <i class="bi bi-cart" style="width: 30px; height: 30px;"></i>
                <span id="broj-u-korpi">(<?php echo $ukupanBrojProizvoda; ?>)</span>
        </a>
        </li>
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
  <section id="naslov">
    <div class="container py-5">
      <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(1px);">
        <div class="container-fluid py-5 text-center">
          <h1 class="display-5 fw-bold" >KK VUKOVI</h1>
            <p class="col-md-12 fs-4">
              <strong> KK Vukovi </strong> je vodeći košarkaški klub na ovim prostorima proteklih nekoliko godina i uvek je 
            uzbudljivo biti navijač našeg kluba i gledati utakmice iz prvih redova. Podrška nam je uvek neophodna kako bi 
            tim pravio dobre rezultate, zato uzmite svoje sezonske karte i budite deo našeg kluba tokom čitave sezone! 
            
            </p>
              <hr>
                
        </div>
      </div>
    </div>
  </section>
    

   
  <section class="thumbnails mt-4 mb-4">
    <div class="container">
      <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-12" >
            <div class="card mb-1 center text-center" style="background: rgba(174, 220, 239, 0.2); backdrop-filter: blur(3px);">
            <div class="card" style="background: rgba(174, 220, 239, 0.2); backdrop-filter: blur(3px);"> 
                <br>
                <svg class="card-img-top" xmlns="http://www.w3.org/2000/svg" width="143" height="143" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
              </svg>
                <h5 class="card-title mt-2" style="color:black;"><b>CENE SEZONSKIH KARATA</b></h5>
                <p class="card-text"><b><i>Pogledajte našu ponudu cena za sezonske ulaznice i pažljivo odaberite! </b></i></p>
                <a href="cene.php" class="btn btn-dark m-auto mb-3">CENE</a>
          </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card mb-1" style="background: rgba(174, 220, 239, 0.2); backdrop-filter: blur(3px);">
            <div class="card" style="background: rgba(174, 220, 239, 0.2); backdrop-filter: blur(3px);"> <br>
              <svg class="card-img-top" xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z"/>
                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
              </svg> <br>
              <h5 class="card-title text-center mt-2" style="color:black;"><b>KUPOVINA SEZONSKE KARTE</b></h5>
              <p class="card-text text-center"><b><i>Želite da budete uz naš tim tokom čitave sezone? Kupite sezonsku kartu i postanite deo nas! </b></i></p>
              <a href="kupovina.php" class="btn btn-dark m-auto mb-3">KUPI SEZONSKU  </a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card mb-1" style="background: rgba(174, 220, 239, 0.2); backdrop-filter: blur(3px);">
            <div class="card" style="background: rgba(174, 220, 239, 0.2); backdrop-filter: blur(3px);"> <br> 
              <svg class="card-img-top" xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" class="bi bi-question-square" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg> <br>
              <h5 class="card-title text-center mt-2" style="color:black;"><b>UPUTSVA KUPOVINE</b></h5>
              <p class="card-text text-center"><b><i>Uputstva vezana za obnovu i kupovinu sezonskih karata i ostalih detalja oko kupovine!</i></b></p>
              <a href="uputstva.php" class="btn btn-dark m-auto mb-3"> UPUTSTVA KUPOVINE</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
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
              <h4 style="color: rgb(7, 122, 166);" ><b>Mog nalog</b></h4>
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
