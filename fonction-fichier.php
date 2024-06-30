<?php
function enregistrer_image($nom_dossier, $nom_fichier, $nom_tmp, $extensions = array("jpeg", "jpg", "png")) {
    // Vérifie si le dossier existe, sinon le créer
    if (!is_dir($nom_dossier)) {
        mkdir($nom_dossier, 0777, true);
    }

    // Récupère l'extension du fichier
    $extension = pathinfo($nom_fichier, PATHINFO_EXTENSION);

    // Vérifie si l'extension est autorisée
    if (in_array($extension, $extensions)) {
        // Déplace le fichier téléchargé dans le dossier
        move_uploaded_file($nom_tmp, "$nom_dossier/$nom_fichier");
        return 1;
    } else {
        return 0;
    }
}

function dossier_en_select($nom_dossier, $extensions = array("jpeg", "jpg", "png")) {
    $html = '<select name="image_select" id="selectImage">';
    if (is_dir($nom_dossier)) {
        $fichiers = scandir($nom_dossier);
        foreach ($fichiers as $fichier) {
            $extension = pathinfo($fichier, PATHINFO_EXTENSION);//retourne des informations sur le chemin path
            if (in_array($extension, $extensions)) {
                $html .= '<option value="' . htmlspecialchars($fichier) . '">' . htmlspecialchars($fichier) . '</option>';
            }
        }
    }
    $html .= '</select>';
    return $html;
}
?>
