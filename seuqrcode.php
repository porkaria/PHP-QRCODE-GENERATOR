<?php
if ($gerar_img) {

    $img_qrcode = uniqid() . '.' . $_POST['formato'];
    
    if ($_POST['formato'] == 'jpeg') {
        imagejpeg($qrcode, 'tmp/' . $img_qrcode);
    } else {
        imagepng($qrcode, 'tmp/' . $img_qrcode);
    }

    echo '<img src="tmp/' . $img_qrcode . '" />';

    unset($qrcode);
}
?>
