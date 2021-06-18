<?php

$caminho = __DIR__;
$diretorio = dir($caminho);

/**
 * cria um array com os arquivos do diretório
 */
$i = 0;
while($arquivo = $diretorio -> read()){
    if ($i > 1){
        $arquivos[] = $arquivo;
    }
    $i++;
}

// rearranja os arquivos
sort($arquivos, SORT_NATURAL);

$tamanho = count($arquivos);

unset($arquivos[$tamanho-1]);

$unificado = [];

/**
 * varre cada arquivo e adiciona ao array geral cada linha de cada arquivo
 */

foreach ($arquivos as $arquivo) {
    
    $arquivoAtual = fopen($arquivo, 'r');
    
    // atribui a primeira linha fora do loop para ela não entrar no array principal e evitar um loop desnecessário
    $dados = fgetcsv($arquivoAtual, 255, ';');
    $cabecalho = $dados;
    while ($dados = fgetcsv($arquivoAtual, 255, ';')) {
        $unificado[] = $dados;
    }
    
    fclose($arquivoAtual);
}


array_unshift($unificado, $cabecalho);

$arquivo = fopen('merge.csv', 'w');

try {
    foreach ($unificado as $linha) {
        fputcsv($arquivo, $linha);
        echo "Seu arquivo foi gerado com sucesso com o nome de 'merge.csv'";
    }
} catch (Exception $e) {
        echo "Houve algum erro durante o processo. Pode ser que o arquivo 'merge.csv' tenha sido gerado, mas em branco. <br />
        Aguarde um pouco e tente novamente." . $e ? " Mensagem de erro, se houver: ".$e : "";
}
            
fclose($arquivo);
$diretorio -> close();
