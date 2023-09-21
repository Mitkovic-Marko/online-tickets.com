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
} else {
    // Korisnik nije prijavljen, postavite $ukupanBrojProizvoda na 0 ili neku podrazumevanu vrednost
    $ukupanBrojProizvoda = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>| Kupovina sezonskih karata</title>
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
            <a class="nav-link active"  aria-current="page"  href="kupovina.php" >Kupovina sezonskih karata</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="cene.php">Cene karata</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="uputstva.php">Uputstva</a>
          </li>
        </ul>
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <a class="nav-link active" href="korpa.php">
                <i class="bi bi-cart" style="width: 30px; height: 30px;"></i>
                <span id="broj-u-korpi">(<?php echo $ukupanBrojProizvoda; ?>)</span>
        </a>
</li>
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

  <section>
    <div class="container py-5" >
      <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.5); backdrop-filter: blur(1px);">
        <div class="container-fluid py-5 text-center"> 
        <h1 class="  display-10 fw-bold" style="color:black">KUPOVINA SEZONSKIH KARATA</h1>  <br> <br>
        <form class="row g-3 needs-validation" method="POST" >

        <div class="col-md-6">
            <label for="Tipkarte" class="form-label" style="font-size:20px;"><b>Tip karte</b></label>
            <select style="text-align:center;" class="form-select" id="tip" name="tip" onchange="updatePrice(this)">
    <option selected disabled>Izaberite sezonsku kartu</option>
    <?php
    // Prikaz svih tipova karata iz baze u select elementu
    // Povezivanje sa bazom podataka
    $conn = mysqli_connect("localhost", "root", "", "diplomski");

    // Provera konekcije
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Upit za dohvat svih tipova karata iz tabele kategorija_karte
    $sql = "SELECT karta.id_karte, karta.cena, kategorija_karte.naziv_kategorije
            FROM karta
            JOIN kategorija_karte ON karta.id_kategorije = kategorija_karte.id_kategorije";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id_karte'] . '" data-price="' . $row['cena'] . '">' . $row['naziv_kategorije'] . '</option>';
        }
    }
    ?>
</select>
          </div>
          <div class="col-md-6">
                <label for="cena" style="font-size:25px;"><b>Cena</b></label> 
                <input style="text-align:center;" type="text" class="form-control" id="cena" name="cena" readonly>
            </div>

        <div class="col-md-6">
                <label for="Sezona" style="font-size:20px;"><b>Sezona</b></label>
                <input type="text" style="text-align:center;" class="form-control" id="sezona" name="sezona" value="2023/24" readonly>
            </div>
            <div class="col-md-6">
                <label for="Kod" style="font-size:20px;"><b>Kolicina</b></label>
                <input type="number" class="form-control" id="kolicina" name="kolicina" min="0">
            </div>
            <div class="col-12">
                <button class="btn btn-dark m-auto mb-3" type="submit" style="width:170px;" name="dugme">Dodaj u korpu</button>
            </div>
            <?php
if (isset($_POST['dugme'])) {
  // Provera da li je korisnik prijavljen
  if (isset($_SESSION['id_korisnika'])) {
      // Provera da li su svi potrebni podaci poslati iz forme
      if (isset($_POST['tip']) && isset($_POST['kolicina'])) {
          $id_korisnika = $_SESSION['id_korisnika'];
          $id_karte = $_POST['tip']; // Dohvatite izabrani tip karte iz POST-a
          $kolicina = $_POST['kolicina']; // Dohvatite količinu iz POST-a

          // Ovde treba da postavite datum na trenutni trenutak, možete koristiti PHP funkciju time()
          $datum = date("Y-m-d H:i:s", time());

          // Sada možete izvršiti unos u tabelu "Korpa"
          $conn = mysqli_connect("localhost", "root", "", "diplomski");

          // Proverite da li je veza uspostavljena
          if (!$conn) {
              die("Connection failed: " . mysqli_connect_error());
          }

          // Napravite SQL upit za unos u tabelu "Korpa"
          $sql = "INSERT INTO korpa (id_korisnika, id_karte, datum, broj_karata) VALUES ($id_korisnika, $id_karte, '$datum', $kolicina)";

          // Izvršite SQL upit
          if (mysqli_query($conn, $sql)) {
              echo '<div class="alert alert-success" style="font-size:20px;">Karta je uspešno dodata u korpu.</div>';
          } else {
              echo '<div class="alert alert-danger" style="font-size:20px;">Greška prilikom dodavanja karte u korpu: ' . mysqli_error($conn) . '</div>';
          }

          // Zatvorite vezu sa bazom podataka
          mysqli_close($conn);
      } else {
          echo '<div class="alert alert-danger" style="font-size:20px;">Morate popuniti sve podatke da biste dodali kartu u korpu.</div>';
      }
  } else {
      echo '<div class="alert alert-danger" style="font-size:20px;">Morate biti ulogovani da biste izvršili dodavanje!</div>';
  }
}

?>
        </form>
        <br> 
  </section> 
  
  </div>
  </div>
  
</div>

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
<script>
 function updatePrice(select) {
    var selectedOption = select.options[select.selectedIndex];
    var cena = selectedOption.getAttribute('data-price');
    document.getElementById('cena').value = cena;
}
    </script>

