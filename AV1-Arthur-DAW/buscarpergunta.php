<?php
$busca = $_POST["termo_busca"];
$linhas = file("perguntamultipla.txt"); 

foreach($linhas as $l){
    $col = explode(";", $l);
    if(stripos($col[0], $busca) !== false){ 
        echo "Resultado: " . $l;
    }
}
?>