<?php
$arquivo = "perguntamultipla.txt";
$pergunta_deletar = $_POST["pergunta"];

$linhas = file($arquivo);
$final = "";
foreach($linhas as $l){
    $col = explode(";", $l);
    if(trim($col[0]) != $pergunta_deletar){
        $final .= $l;
    }
}
file_put_contents($arquivo, $final);
?>