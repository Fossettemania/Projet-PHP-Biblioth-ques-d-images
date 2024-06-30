# Compte rendu : Bibliothèque d'images

Dans un premier temps,dans index.php ,on doit retrouver comme tout premier code PHP pour les sessions, sinon notre code ne marchera pas.
Dans un deuxième temps nous allons appeler le fichier "fonction-fichier". 
Dans ce fichier la fonction enregistrer_image , on va vérifier si le dossier existe, sinon le créer
```PHP
	function enregistrer_image($nom_dossier, $nom_fichier, $nom_tmp, $extensions = array("jpeg", "jpg", "png")) {
    // Vérifie si le dossier existe, sinon le créer
    if (!is_dir($nom_dossier)) {
        mkdir($nom_dossier, 0777, true);
    }

 ```
Puis nous allons récupèrer l'extension du fichier et enfin vérifier si l'extension est autorisée
Ensuite nous repartons dans le fichier index.php
Nous allons nous occuper du gestion du téléchargement d'image .On va créer un formulaire en méthode post qui permet d'envoyer un fichier.Pour récupérer les fichiers envoyés par l'utilisateur, on va utiliser la superglobale $_FILES qui fonctionne de la même façon que les autres superglobales comme $_POST ou $_GET. Si le fichier est conforme on a  "Image téléchargée avec succès." sinon on a "Erreur : extension de fichier non autorisée."
Dans le fichier index.php, nous mettons la partie html: on a bien le head dans lequel on met la ligne de code qui nous dirigera faire le fichier style.css. Puis nous avons le body, dans lequel on retrouve le titre, puis la structure de la page dans laquelle on a _div envoyer des fichiers_, _div envoie informations_ et _div image_. 
```PHP
<div class="form">
            <form action="index.php" method="post">
                <label for="selectImage">Sélectionner une image :</label>
                <?php echo dossier_en_select("img_" . session_id()); ?>
                <button type="submit" name="select">Sélectionner</button>
            </form>
        </div>
 ```
La fonction dossier_en_select se retrouve dans le fichier fonction-fichier.php où on a:
 ```PHP
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
 ```
 La fonction dossier_en_select crée un menu déroulant HTML avec des options pour chaque fichier d'un dossier spécifique, si et seulement si les fichiers ont des extensions autorisées.


La fonction effectue les étapes suivantes:
-Vérifie si le dossier existe.
-Parcourt tous les fichiers du dossier.
-Filtre les fichiers par extension.
-Génère les options du menu déroulant pour les fichiers autorisés.
-Retourne le menu déroulant complet sous forme de chaîne HTML.

_if (is_dir($nom_dossier))_ { ->Vérifie si $nom_dossier est un dossier valide.
is_dir est une fonction PHP qui retourne true si le chemin spécifié est un dossier, false sinon.
_$fichiers = scandir($nom_dossier)_ ->Utilise la fonction scandir pour obtenir un tableau de tous les fichiers et dossiers dans $nom_dossier.
scandir retourne un tableau contenant les noms de tous les fichiers et dossiers du répertoire spécifié.
_foreach ($fichiers as $fichier) {_ -> Boucle à travers chaque élément dans le tableau $fichiers.

_$extension = pathinfo($fichier, PATHINFO_EXTENSION)_ ->Utilise la fonction pathinfo pour obtenir des informations sur le chemin du fichier.
_PATHINFO_EXTENSION_ retourne l'extension du fichier

_if (in_array($extension, $extensions)) {_->Vérifie si l'extension du fichier est dans le tableau $extensions.
in_array est une fonction PHP qui retourne true si une valeur donnée existe dans un tableau, false sinon.

_$html .= '<option value="' . htmlspecialchars($fichier) . '">' . htmlspecialchars($fichier) . '</option>'_
-> Si l'extension est autorisée, ajoute une balise <option> à la chaîne $html.
-htmlspecialchars($fichier) est utilisé pour convertir les caractères spéciaux en entités HTML
_value="' . htmlspecialchars($fichier) . '"'_  
->définit la valeur de l'option. Cette valeur sera envoyée lorsque l'utilisateur soumettra le formulaire.
_">' . htmlspecialchars($fichier) . '</option>' _: définit le texte affiché de l'option, qui est le nom du fichier.

Puis pour div image nous avons:
```PHP
<div id="image">
        <?php
        if (isset($_POST['select']) && !empty($_POST['image_select'])) {
            $imagePath = "img_" . session_id() . '/' . htmlspecialchars($_POST['image_select']);
            echo '<img src="' . $imagePath . '" alt="Selected Image">';
        }
        ?>
    </div>
    ```
```PHP
<div id="image">
 ```
->Crée une division (<div>) avec l'identifiant id="image". Cette division contiendra l'image sélectionnée.
```PHP
 if (isset($_POST['select']) && !empty($_POST['image_select'])) {
```
-> Vérifie si le formulaire a été soumis et si un fichier a été sélectionné.
_isset($_POST['select'])_ : vérifie si le bouton "Sélectionner" du formulaire a été cliqué. $_POST est une superglobale en PHP qui contient les données du formulaire envoyées via la méthode POST.
_!empty($_POST['image_select']) _: vérifie si une image a été sélectionnée dans le menu déroulant 
_$imagePath = "img_" . session_id() . '/' . htmlspecialchars($_POST['image_select']);_
->Construit le chemin de l'image sélectionnée.
->session_id() : récupère l'ID de session actuel. Cela garantit que l'image est stockée dans un dossier unique pour chaque session utilisateur.
_htmlspecialchars($_POST['image_select'])_: convertit les caractères spéciaux en entités HTML pour éviter les problèmes de sécurité (comme les attaques XSS).

###Le chemin de l'image est donc construit en concaténant le dossier de l'image (basé sur l'ID de session) et le nom de l'image sélectionnée.