<?php
$error_msg = false;
$gerar_img = false;
$show = false;

if (filter_input(INPUT_POST, 'gerar',FILTER_SANITIZE_STRING)) {

    if (empty($_POST['conteudo']) AND ($_POST['tamanho'] < 30) AND empty($_POST['formato'])) {
        $error_msg = 'Ops... Você precisa preencher os campos obrigatórios';
    } else {

        // Gerando o QR-Code
        require_once("qrcode/Image/QRCode.php");

        if ($_POST['nivel'] != "7") {

            if ($_POST['nivel'] == '15') {
                $error_level = 'M';
            } else if ($_POST['nivel'] == '25') {
                $error_level = 'Q';
            } else if ($_POST['nivel'] == '30') {
                $error_level = 'H';
            }

        } else {
            $error_level = 'H';
        }

        $options = array(
                "image_type" => $_POST['formato'],
                "module_size" => $_POST['tamanho'],
                "error_correct" => $error_level,
                "version" => $_POST['versao'],
                "output_type" => "return"
        );

        $qr = new Image_QRCode();
        $qrcode = $qr->makeCode($_POST['conteudo'],$options);

        $gerar_img = true;
        $show = true;

        if (isset($_POST['output']) AND $_POST['output'] == 1) {
            $returnvalue = 'return';
        } else {
            $returnvalue = "display";
        }

        // Gerando o Código
        $codigo = <<<EOD
   <?php
       require_once("Image/QRCode.php");

       \$options = array(
        "image_type" => "{$_POST['formato']}",
        "module_size" => {$_POST['tamanho']},
        "error_correct" => "$error_level",
        "version" => {$_POST['versao']},
        "output_type" => "$returnvalue"
       );

       \$qr = new Image_QRCode();
       \$qrcode = \$qr->makeCode("{$_POST['conteudo']}",\$options);
   ?>
EOD;

    }
}

if (filter_input(INPUT_POST, 'enviarfeedback',FILTER_SANITIZE_STRING)) {

    require_once('phpmailer/class.phpmailer.php');
    require_once 'config.php';

    $mail             = new PHPMailer();

    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host       = FB_HOST; // SMTP server
    $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username   = FB_USERNAME; // SMTP account username
    $mail->Password   = FB_PASSWORD;        // SMTP account password

    $mail->SetFrom(FB_EMAIL, 'Robocop');

    $mail->Subject    =  '[PHPQRcode] FeedBack';

    $mail->AltBody    = 'Avaliação: ' . $_POST['avaliacao'] . '   - Mensagem: ' . $_POST['observacao'];

    $mail->MsgHTML('Avaliação: ' . $_POST['avaliacao'] . ' <br /> Mensagem: ' . $_POST['observacao']);

    $mail->AddAddress(FB_MYEMAIL, 'Robocop');

    if(!$mail->Send()) {
        echo '<h3>Erro ao enviar Feedback =[ Tente novamente ? </h3>';
    } else {
        echo '<h3>Feedback enviado! obrigado :)</h3>';
    }
}

?>
