<?php
require_once "brain.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel='stylesheet' href='css/estilo.css' type='text/css' media='all' />
        <link rel='stylesheet' href='css/smoothness/jquery-ui-1.8.1.custom.css' type='text/css' media='all' />
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.8.1.custom.min.js" type="text/javascript"></script>
        <script src="js/jquery.validate.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(function() {
                $("input:submit", "#formfeedback").button();

                $(".feedback").click(function (){
                    $("#formfeedback").dialog({
                        width: 560
                    });
                })

                $("#gerador").validate({
                    rules: {
                        conteudo: "required" 
                    },
                    messages: {
                        conteudo: "Você precisa informar o conteúdo para gerar o Qr-Code"
                    }
                });

                $(".nivel_duvida").click(function () {
                    $("#nivel_ajuda").dialog({
                        width: 400
                    });
                });

                 $(".versao_duvida").click(function () {
                    $("#versao_ajuda").dialog({
                        width: 400
                    });
                });


            });
        </script>
        <title>PHP QRCode Generator - Gerador de código PHP que gera Qrcode - Código de Barras 2D !</title>        
    </head>
    <body>
        <a href="javascript:void{0}" class="feedback">Feedback :)</a>
        <?php require_once "formfeedback.php" ?>
        <div id="content">
            <h1>PHP QRCode Generator</h1>
            <h2>O serviço não só gera o QrCode, como o código que foi usado para sua geração.</h2>            
            <div id="formulario">

                <div class="aviso">
                    <div class="step">1</div> Configure no formulário abaixo as opções para geração do seu QRCode
                </div>
                <div class="novalinha"></div>

                <?php require_once "formulario.php" ?>

            </div>
            <div id="seuqrcode" <?php echo (($show) ? false : 'class="escondido"')?>>

                <div class="aviso">
                    <div class="step">2</div> Esse é o seu QrCode, agora é só testa-lo. Não sabe como testar? <a href="http://www.porkaria.com.br/2010/05/31/o-que-eu-preciso-para-testar-um-qr-code/" target="_blank" title="O que eu preciso para testar um Qr Code">Leia esse artigo</a>.
                </div>
                <div class="novalinha"></div>

                <?php require_once "seuqrcode.php" ?>

            </div>
            <div id="instrucoes" <?php echo (($show) ? false : 'class="escondido"')?>>
                <div class="aviso">
                    <div class="step">3</div> O Qrcode foi gerado a partir da classe Image_QrCode, um pacote da PEAR. Você mesmo pode criar o seu, <a href="http://www.porkaria.com.br/2010/04/24/gerando-qr-code-com-php/" target="_blank">saiba como</a>.
                </div>
                <div class="novalinha"></div>
                <ul>
                    <li>Para baixar a classe, acesse: <a href="http://pear.php.net/package/Image_QRCode/download" target="_blank">http://pear.php.net/package/Image_QRCode/download</a></li>
                    <li>Essa classe está licenciada sob a licença <a href="http://www.gnu.org/licenses/lgpl.html" target="_blank">LGPL</a></li>
                </ul>                
            </div>
            <div id="codigo" <?php echo (($show) ? false : 'class="escondido"')?>>
                <div class="aviso">
                    <div class="step">4</div> Opa! Se você preferir, abaixo está o código usado para a geração do seu QrCode!
                </div>
                <div class="novalinha"></div>

                <?php require_once "codigo.php" ?>

            </div>            
            <div id="publicidade" <?php echo (($show) ? false : 'class="escondido"')?>>
                <div class="aviso">
                    <div class="step">5</div> Gostou ? Não Gostou? Dê a sua opinião, clicando no link "FeedBack" a direita.
                </div>
                <div class="novalinha"></div>
                <ul>
                    <li>Está com problemas na geração do QrCode ?</li>
                    <li>Dúvidas sobre como funciona ?</li>                    
                    <li>Acesse o <a href="http://www.phpmobile.com.br/forum/php-qr-code-generator/" target="_blank">fórum</a> do projeto PHP Mobile e deixe a sua dúvida.</li>
                    <li>Quer saber mais sobre o autor desse serviço? Acesse o <a href="http://www.porkaria.com.br/sobre/" target="_blank">blog</a> dele.</li>
                </ul>

            </div>
            <div id="divulgacao">
<script type="text/javascript"><!--
google_ad_client = "pub-9487137028359570";
/* phpqrcode - 728x90,  criado 28/05/10 */
google_ad_slot = "4456223671";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
            </div>
                    <div id="nivel_ajuda" class="escondido" title="Nível de restauração de erro">
                        QR Code tem capacidade de correção de erros para restaurar os dados se o código está sujo ou danificado. Quatro níveis de correção de erros estão disponíveis para os usuários escolherem de acordo com o ambiente operacional. <br /><br />
                        Level L - Aprox. 7% <br />
                        Level M  - Aprox. 15% <br />
                        Level Q - Aprox. 25% <br />
                        Level H - Aprox. 30%
                    </div>
                    <div id="versao_ajuda" class="escondido" title="Nível de restauração de erro">
                        De acordo com a versão, é o tamanho que o Qr Code suporta armazenar. Acesse a tabela da <a href="http://www.denso-wave.com/qrcode/vertable1-e.html" target="_blank">Denso-Wave</a> para saber mais. <br /><br />
                    </div>
        </div>
        <div id="footer">
            <a href="http://www.phpmobile.com.br/" target="_blank"><img src="img/logo_phpmobile.png" class="duvida" title="Projeto PHP Mobile" /></a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="http://www.porkaria.com.br/" target="_blank"><img src="img/logo_blog_porkaria.png" class="duvida" title="Blog do Bruno PorKaria" /></a>
        </div>
<script type="text/javascript">

  var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-16750110-1']);
      _gaq.push(['_trackPageview']);

        (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		      })();

		      </script>
    </body>
</html>
