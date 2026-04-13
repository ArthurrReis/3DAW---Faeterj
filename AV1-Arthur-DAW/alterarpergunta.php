<?php

$arquivo_db = "perguntamultipla.txt"; 
$msg = "";
$campos = ["pergunta" => "", "opcoes" => "", "gabarito" => ""];

if(isset($_POST["btn_alterar"])){
    $pergunta_original = $_POST["pergunta_old"];
    $nova_pergunta = $_POST["pergunta"];
    $novas_opcoes = $_POST["opcoes"];
    $novo_gabarito = $_POST["gabarito"];
    
    $linhas = file($arquivo_db);
    $novo_conteudo = "";
    foreach($linhas as $l){
        $col = explode(";", $l);
        if(trim($col[0]) == $pergunta_original){
        
            $l = "{$nova_pergunta};{$novas_opcoes};{$novo_gabarito}\n";
            $msg = "Alterado!";
        }
        $novo_conteudo .= $l;
    }
    file_put_contents($arquivo_db, $novo_conteudo);
}
?>