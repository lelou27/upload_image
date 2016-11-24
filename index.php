<?php
$error = array();
if (!empty($_POST['submit']) && !empty($_FILES['image'])) {

  if ($_FILES['image']['error'] > 0) {

    if ($_FILES['image']['error'] == 4) {
      $error['error'] = "Aucun fichier n'a été téléchargé.";
    } elseif ($_FILES['image']['error'] == 1) {
      $error['error'] = "Le fichier est trop gros.";
    }

  } else {
    $name = $_FILES['image']['name'];
    $type = $_FILES['image']['type'];
    $size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];

    if (!empty($size)) {
      if (($size > 2000000) || (filesize($file_tmp) > 2000000)) {
        $error['size'] = "Le fichier est trop gros";
      } else {
        $extensions = array('jpg', 'jpeg', 'png');
        $ext = new SplFileInfo($name);
        $extVrai = $ext->getExtension();
        if (in_array($extVrai, $extensions)) {

          $finfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime
          $mime = array('image/png', 'image/jpg', 'image/jpeg');
          /* Récupère le mime-type d'un fichier spécifique */
          $filename = $file_tmp;
          $obj = finfo_file($finfo, $filename);

          if (in_array(finfo_file($finfo, $file_tmp), $mime)) {
            if (count($error) == 0) {
              // renomer et upload fichier
              $annee = date('Y');
              $mois = date('M');
              if ( !is_dir($annee . '/' . $mois) ) {
                mkdir($annee);
                mkdir($annee . '/' . $mois);
              }
              move_uploaded_file($file_tmp, $annee . '/' . $mois . '/' . time() . $ext);

            }
          } else {
            $error['ext'] = "L'extention n'est pas bonne";
          }

        } else {
          $error['ext'] = "L'extention n'est pas bonne";
        }


      }

    }

  }

  echo '<pre>';
  print_r($_FILES['image']);
  echo '</pre>';

}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

    <form action="#" method="POST" enctype="multipart/form-data">
      <input type="file" name="image">
      <span><?php if (!empty($error['error'])) { echo $error['error']; } ?></span>
      <span><?php if (!empty($error['size'])) { echo $error['size']; } ?></span>
      <span><?php if (!empty($error['ext'])) { echo $error['ext']; } ?></span>


      <input type="submit" name="submit" value="Envoyer">

    </form>

  </body>
</html>
