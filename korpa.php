<?php
session_start(); // Otvorite sesiju na vrhu svake stranice

// Inicijalizujte promenljivu $korpaPodaci kao prazan niz
$korpaPodaci = array();

// Proverite da li je korisnik prijavljen
if (isset($_SESSION['id_korisnika'])) {
    $id_korisnika = $_SESSION['id_korisnika'];

    // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
    $conn = mysqli_connect("localhost", "root", "", "diplomski");

    // Proverite da li je veza uspostavljena
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Napravite SQL upit za dohvatanje poslednje dodate korpe za trenutno prijavljenog korisnika
    $sql = "SELECT k.*, kk.naziv_kategorije, ka.cena
            FROM korpa k
            JOIN karta ka ON k.id_karte = ka.id_karte
            JOIN kategorija_karte kk ON ka.id_kategorije = kk.id_kategorije
            WHERE k.id_korisnika = $id_korisnika
            ORDER BY k.datum DESC
            LIMIT 1";

    // Izvršite SQL upit
    $result = mysqli_query($conn, $sql);

    // Inicijalizujte promenljivu za čuvanje podataka
    $korpaPodaci = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $korpaPodaci[] = $row;
        }
    }

    // Zatvorite vezu sa bazom podataka
} else {
    // Ako korisnik nije prijavljen, možete preusmeriti na prijavu ili prikazati poruku
    header("Location: prijava.php");
    exit;
}
?>

<?php

// Ovde dodajte kod za ažuriranje korpe ako je pritisnuto dugme "Azuriraj korpu"
if (isset($_POST['azuriranje'])) {
    // Dohvatite novu količinu iz forme
    $novaKolicina = (int)$_POST['kolicina'];
    
    // Ovde dodajte kod za ažuriranje korpe u bazi podataka
    // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
    $conn = mysqli_connect("localhost", "root", "", "diplomski");

    // Proverite da li je veza uspostavljena
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Dohvatite id_korisnika iz sesije
    $id_korisnika = $_SESSION['id_korisnika'];

    // Napravite SQL upit za ažuriranje količine u korpi za određenog korisnika
    $sql = "UPDATE korpa SET broj_karata = $novaKolicina WHERE id_korisnika = $id_korisnika";

    // Izvršite SQL upit
    if (mysqli_query($conn, $sql)) {
        // Uspesno ažurirano, možete prikazati poruku korisniku ili ih preusmeriti nazad na korpu
        header("Location: korpa.php");
        exit;
    } else {
        echo "Greška pri ažuriranju korpe: " . mysqli_error($conn);
    }

    // Zatvorite vezu sa bazom podataka
}
?>
 <?php   //PHP kod za broj karata u korpi
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
    
    // Zatvorite vezu sa bazom podataka
    
    
?>
<?php
// ...

if (isset($_POST['dugme'])) {
    // Dohvatite id_korisnika iz sesije
    $id_korisnika = $_SESSION['id_korisnika'];

    // Dohvatite ukupnu cenu iz forme
    $ukupnaCena = (float)$_POST['ucena'];

    // Dohvatite adresu dostave iz forme
    $adresaDostave = $_POST['adresa_dostave'];

    // Dodajte provjeru da li je količina veća od 0
    $kolicina = (int)$_POST['kolicina'];
    if ($kolicina <= 0) {
        echo "<div>Količina mora biti veća od 0.</div>";
        // Dodajte i eventualno dodatne poruke ili logiku ako je potrebno.
    } else {
        // Generišite vremenski pečat za datum kupovine
        $datumKupovine = date('Y-m-d H:i:s'); // Format: "YYYY-MM-DD HH:MM:SS"

        // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
        $conn = mysqli_connect("localhost", "root", "", "diplomski");

        // Proverite da li je veza uspostavljena
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Počnite transakciju
        mysqli_begin_transaction($conn);

        // Ovdje dodajte kod za dohvatanje količine dostupnih karata iz baze za određenu kartu
        $id_karte = $_POST['id_karte']; // Dodajte input polje u formu za slanje ID-a karte
        $sqlDostupnaKolicina = "SELECT kolicina FROM karta WHERE id_karte = $id_karte";
        $resultDostupnaKolicina = mysqli_query($conn, $sqlDostupnaKolicina);
        
        if ($resultDostupnaKolicina) {
            $rowDostupnaKolicina = mysqli_fetch_assoc($resultDostupnaKolicina);
            $dostupnaKolicina = (int)$rowDostupnaKolicina['kolicina'];

            // Proverite da li ima dovoljno karata za kupovinu
            if ($kolicina <= $dostupnaKolicina) {
                // Ažurirajte tabelu Korisnik ako je uneta adresa dostave
                if (!empty($adresaDostave)) {
                    $sqlAdresa = "UPDATE korisnik SET adresa_dostave = '$adresaDostave' WHERE id_korisnika = $id_korisnika";

                    if (!mysqli_query($conn, $sqlAdresa)) {
                        // Ako se desi greška pri ažuriranju adrese dostave, poništite transakciju
                        mysqli_rollback($conn);
                        echo "<div>Greška pri ažuriranju adrese dostave: " . mysqli_error($conn) . "</div>";
                    }
                }

                // Napravite SQL upit za dodavanje kupovine u tabelu racun
                $sql = "INSERT INTO racun (id_korpe, datum_kupovine, ukupan_iznos) VALUES ((SELECT MAX(id_korpe) FROM korpa WHERE id_korisnika = $id_korisnika), '$datumKupovine', $ukupnaCena)";

                // Izvršite SQL upit
                if (mysqli_query($conn, $sql)) {
                    // Ako je uspešno dodato u tabelu racun, potvrdite transakciju
                    mysqli_commit($conn);

                    // Smanjite dostupnu količinu karata u bazi
                    $novaDostupnaKolicina = $dostupnaKolicina - $kolicina;
                    $sqlAzurirajKolicinu = "UPDATE karta SET kolicina = $novaDostupnaKolicina WHERE id_karte = $id_karte";
                    mysqli_query($conn, $sqlAzurirajKolicinu);

                    // Očistite korpu nakon uspešne kupovine (možete koristiti svoju funkciju za brisanje korpe)
                    // ...

                    // Prikazivanje poruke o uspehu
                    echo "<div>Kupovina je uspešno obavljena!</div>";
                    $_SESSION['obavestenje'] = "Uspešno se obavili kupovinu!";

                    header("Location: racun.php");
                    exit;
                    // Možete dodati kod za slanje potvrde o kupovini putem e-pošte ili neke druge radnje koje želite da izvršite nakon uspešne kupovine.

                } else {
                    // Ako se desi greška pri dodavanju u tabelu racun, poništite transakciju
                    mysqli_rollback($conn);

                    echo "<div>Greška pri obavljanju kupovine: " . mysqli_error($conn) . "</div>";
                }
            } else {
                // Nema dovoljno karata za kupovinu, ispišite obaveštenje
                echo "<div>Nema dovoljno karata za izabrani proizvod.</div>";
            }
        } else {
            // Greška pri dohvatanju dostupne količine, poništite transakciju
            mysqli_rollback($conn);
            echo "<div>Greška pri dohvatanju dostupne količine: " . mysqli_error($conn) . "</div>";
        }

        // Zatvorite vezu sa bazom podataka
        mysqli_close($conn);
    }
}

// ...

?>

<?php
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>| Korpa</title>
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
        <li class="nav-item">
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
 <!-- Ispis obaveštenja o korpi -->
<div class="container py-5">
    <div class="p-5 mb-4 rounded-3 text-center" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(1px);">
    <div class="container-fluid py-5 text-center"> 

        <?php if (empty($korpaPodaci)) { ?>
            <h1 class="display-10 fw-bold text-center" style="color:black;">VAŠA KORPA JE TRENUTNO PRAZNA</h1><br><br>
        <?php } else { ?>
            <h1 class="display-10 fw-bold text-center" style="color:black;">VAŠA KORPA</h1><br><br>

            <form action="korpa.php" method="POST" class="row g-3">
    <?php foreach ($korpaPodaci as $red) { ?>
        <div class="col-md-4">
            <label for="tip" style="font-size:20px;"><b>Tip karte</b></label>
            <input style="text-align:center;" type="text" class="form-control" id="tip" name="tip" readonly value="<?php echo $red['naziv_kategorije']; ?>">
        </div>
        <div class="col-md-4">
            <label for="kolicina" style="font-size:20px;"><b>Količina</b></label>
            <input style="text-align:center;" type="number" class="form-control kolicina" name="kolicina" min="0" value="<?php echo $red['broj_karata']; ?>" data-cena="<?php echo $red['cena']; ?>">
        </div>
        <div class="col-md-4">
            <label for="ucena" style="font-size:20px;"><b>Ukupna cena</b></label>
            <input style="text-align:center;" type="text" class="form-control ukupna-cena" name="ucena" readonly value="<?php echo $red['cena'] * $red['broj_karata']; ?>">
        </div>
        <div class="col-md-12">
            <label for="dostava" style="font-size:20px;"><b>Adresa dostave</b></label>
            <input style="text-align:center;" type="text" class="form-control" name="adresa_dostave" placeholder="Unesite adresu dostave ukoliko ne želite da vam sezonaska karta bude dostavljena na adresu koju ste uneli prilikom registracije!">
        </div>

        <!-- Dodajte input polje za id_karte kao skriveno polje -->
        <input type="hidden" name="id_karte" value="<?php echo $red['id_karte']; ?>">
    <?php } ?>
    <div class="col-12"> </div>
    <div class="col-6">
        <button class="btn btn-dark" type="submit" style="width:170px;" name="azuriranje">Ažuriraj korpu</button>
    </div> 
    <div class="col-6">
        <button class="btn btn-dark" type="submit" style="width:170px;" name="dugme">Obavi kupovinu</button>
    </div>
</form>

<?php } ?>
    </div>
    </div>
</div>
<?php
?>


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
<script>
    // Funkcija za ažuriranje ukupne cene na osnovu količine
    function azurirajUkupnuCenu() {
        $('.kolicina').each(function () {
            var kolicina = parseInt($(this).val());
            var cena = parseFloat($(this).data('cena'));
            var ukupnaCena = kolicina * cena;
            $(this).closest('.row').find('.ukupna-cena').val(ukupnaCena.toFixed(2)); // Podešavanje na dve decimale
        });
    }

    // Primeni funkciju za ažuriranje ukupne cene kada se promeni količina
    $('.kolicina').on('input', function () {
        azurirajUkupnuCenu();
    });

</script>

