<?php
session_start();

// Provjerite jeste li prijavljeni
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

    // Provera da li je forma poslata
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> | Dodavanje novih karata</title>
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
    <div class="p-5 mb-4 rounded-3" style="background: rgba(174, 220, 239, 0.6); backdrop-filter: blur(1px);">
        <div class="container-fluid py-5 text-center"> 
            <h1 class="  display-10 fw-bold" style="color:black; text-align:center;">DODAVANJE NOVIH KARATA </h1>  <br> <br> 
            <form class="row g-3" action="dodavanje-karata.php" method="POST">
                <div class="col-md-6">
                    <label for="tip" style="font-size:20px;"><b>Tip karte</b></label>
                    <select style="text-align:center;" class="form-select" id="tip" name="tip">
                        <option selected disabled><b>Izaberite kartu</b></option>    
                        <?php
                        // Prikaz svih tipova karata iz baze u select elementu
                        // Povezivanje sa bazom podataka
                        $conn = mysqli_connect("localhost", "root", "", "diplomski");

                        // Provera konekcije
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Upit za dohvat svih tipova karata iz tabele kategorija_karte
                        $sql = "SELECT id_kategorije, naziv_kategorije from kategorija_karte";

                        $result = $conn->query($sql);
                        $id_kategorije = $_POST['tip'];
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id_kategorije'] . '">' . $row['naziv_kategorije'] . '</option>';
                            }
                        }
                        ?>    
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="cena" style="font-size:20px;"><b>Cena karte</b></label>
                    <input type="number" style="text-align:center;" class="form-control" id="cena" name="cena">
                </div>
                <div class="col-md-6">
                    <label for="sezona" style="font-size:20px;"><b>Sezona</b></label>
                    <select style="text-align:center;"  class="form-select" id="sezona" name="sezona">
                        <option selected disabled><b>Izaberite sezonu</b></option>
                        <?php
                        // Prikaz svih tipova karata iz baze u select elementu
                        // Povezivanje sa bazom podataka
                        $conn = mysqli_connect("localhost", "root", "", "diplomski");

                        // Provera konekcije
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Upit za dohvat svih tipova karata iz tabele kategorija_karte
                        $sql = "SELECT id_sezone, naziv_sezone from sezona";

                        $result = $conn->query($sql);
                        
                        $id_sezone = $_POST['sezona'];

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id_sezone'] . '">' . $row['naziv_sezone'] . '</option>';
                            }
                        }
                        ?>  
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="kolicina" style="font-size:20px;"><b>Količina</b></label>
                    <input style="text-align:center;" type="number" class="form-control" id="kolicina" name="kolicina" min="0">
                </div>
                <br>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-dark" name="dodaj" style="width:200px;">Dodaj kartu</button>
                </div>
            </form> <br>
                            <?php

                // Provjerite jeste li prijavljeni
                if (isset($_SESSION['id_korisnika'])) {
                    $id_korisnika = $_SESSION['id_korisnika'];

                    // Povežite se sa bazom podataka (koristite svoje podatke za pristup)
                    $conn = mysqli_connect("localhost", "root", "", "diplomski");

                    // Proverite da li je veza uspostavljena
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Provera da li je forma poslata
                    if (isset($_POST['dodaj'])) {
                        // Dohvatite vrednosti iz forme
                        
                        $cena = $_POST['cena'];
                        $kolicina = $_POST['kolicina'];

                        // Provera da li su sva polja popunjena
                        if (empty($id_sezone) || empty($id_kategorije) || empty($cena) || empty($kolicina)) {
                            echo " <div class='alert alert-danger' role='alert'> Sva polja moraju biti popunjena. </div>";
                        } else {
                            // Napravite SQL upit za dodavanje nove karte u tabelu Karta
                            $sql = "INSERT INTO Karta (id_sezone, id_kategorije, cena, kolicina) 
                                    VALUES ('$id_sezone', '$id_kategorije', '$cena', '$kolicina')";

                            // Izvršite SQL upit
                            if (mysqli_query($conn, $sql)) {
                                echo " <div class='alert alert-success' role='alert'>Nova karta je uspešno dodata! </div>";
                            } else {
                                echo "Greška pri dodavanju karte: " . mysqli_error($conn);
                            }
                        }
                    }
                } }
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
