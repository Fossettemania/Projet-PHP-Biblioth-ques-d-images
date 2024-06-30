<?php
session_start();
include('fonction-fichier.php');//mettre session_start au début

// Gestion du téléchargement d'image
if (isset($_POST['upload']) && isset($_FILES['image'])) {
    $nom_dossier = "img_" . session_id();
    $nom_fichier = $_FILES['image']['name'];
    $nom_tmp = $_FILES['image']['tmp_name'];

    $resultat = enregistrer_image($nom_dossier, $nom_fichier, $nom_tmp);

    if ($resultat == 1) {
        echo "Image téléchargée avec succès.";
    } else {
        echo "Erreur : extension de fichier non autorisée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>New Paper</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <center>
<h1> Lady Whiseldown </h1>
</center>
<!--structure de la page  -->
    <div class="forms">
        <!--div envoyer des fichiers  -->
        <div class="form">
            <form action="index.php" method="post" enctype="multipart/form-data">
                <label for="imageUpload">Télécharger une image :</label>
                <input type="file" name="image" id="imageUpload">
                <button type="submit" name="upload">Télécharger</button>
            </form>
        </div>
        <!--div envoie informations  -->
        <div class="form">
            <form action="index.php" method="post">
                <label for="selectImage">Sélectionner une image :</label>
                <?php echo dossier_en_select("img_" . session_id()); ?>
                <button type="submit" name="select">Sélectionner</button>
            </form>
        </div>
    </div>
    <!--div image  -->
    <div id="image">
        <?php
        if (isset($_POST['select']) && !empty($_POST['image_select'])) {
            $imagePath = "img_" . session_id() . '/' . htmlspecialchars($_POST['image_select']);
            echo '<img src="' . $imagePath . '" alt="Selected Image">';
        }
        ?>
    </div>
</body>
</html>
