<form name="gerador" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="gerador">
    <label for="conteudo">Insira o conteúdo que o Qr-Code irá armazenar </label>
    <textarea id="conteudo" name="conteudo" rows="5" cols="80"><?php echo ((empty($_POST['conteudo'])) ? false : $_POST['conteudo'])?></textarea>
    <label for="tamanho">Tamanho da Imagem </label>
    <select name="tamanho" id="tamanho">
        <option value="3">Pequeno - 99x99 px</option>
        <option value="6">Médio - 198x198 px</option>
        <option value="12">Grande - 396x396 px</option>
        <option value="22">Gigante - 726x726 px</option>
    </select>
    <label for="image">Formato da Imagem </label>
    <select name="formato" id="image">
        <option value="png">PNG</option>
        <option value="jpeg">JPEG</option>
    </select>
    <label for="output">Quero que retorne um objeto GD e não a imagem</label>
    <input type="checkbox" name="output" id="output" value="1" />
    <label for="nivel">Nível de correção de erro <img src="img/question_blue.png" title="Dúvida ? Clique Aqui" alt="Dúvida ? Clique Aqui" class="duvida nivel_duvida"/></label>
    <select name="nivel" id="nivel">
        <option value="7">7%</option>
        <option value="15">15%</option>
        <option value="25">25%</option>
        <option value="30">30%</option>
    </select>
    <label for="versao">Versão <img src="img/question_blue.png" title="Dúvida ? Clique Aqui" alt="Dúvida ? Clique Aqui" class="duvida versao_duvida"/></label>
    <select name="versao" id="versao">
        <?php
        for($x = 1; $x <= 40; $x++) :
            if (!empty($_POST['versao'])) {
                $selected = $_POST['versao'];
            } else {
                $selected = 5;
            }
            ?>
        <option value="<?php echo $x ?>" <?php echo (($x == $selected) ? 'selected' : false) ?>><?php echo $x ?></option>
        <?php
        endfor;
        ?>
    </select>
    <br />
    <input type="submit" class="submit" name="gerar" value="Gerar código e Qr-code" />
    <input type="reset" class="limpar" name="limpar" value="Limpar Campos" />
</form>