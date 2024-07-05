<?php
  function GetBaes64($files){
    switch ($files) {
      case "data:image/jpeg;base64":
        $imageFileType = "jpeg";
        break;
      case "data:image/png;base64":
        $imageFileType = "png";
        break;
      case "data:application/pdf;base64":
          $imageFileType = "pdf";
          break;
      default:
          $imageFileType = "notype";
          break;
    }
    return $imageFileType;
  }
?>

