<?php
// Definir o cabeçalho para permitir requisições de qualquer origem e para especificar que o conteúdo retornado será JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Verificar se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ler o conteúdo bruto da requisição
    $input = file_get_contents('php://input');
    
    // Tentar decodificar o JSON recebido
    $data = json_decode($input, true);
    
    if (is_array($data) && !empty($data)) {
        $totalProbabilidade = 0;
        $nomes = [];
        $probabilidades = [];
        
        // Validar cada entrada do JSON
        foreach ($data as $nome => $probabilidade) {
            if (!is_numeric($probabilidade) || $probabilidade < 0) {
                http_response_code(400);
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => "A probabilidade para o nome '{$nome}' deve ser um número não negativo."
                ]);
                exit;
            }
            $totalProbabilidade += $probabilidade;
            $nomes[] = $nome;
            $probabilidades[] = $probabilidade;
        }
        
        // Verificar se a soma das probabilidades é igual a 100
        if ($totalProbabilidade !== 100) {
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => "A soma das probabilidades deve ser igual a 100%. Atualmente é {$totalProbabilidade}%."
            ]);
            exit;
        }
        
        // Realizar o sorteio baseado nas probabilidades
        $random = mt_rand(1, 100);
        $cumulative = 0;
        $selecionado = null;
        
        foreach ($probabilidades as $index => $probabilidade) {
            $cumulative += $probabilidade;
            if ($random <= $cumulative) {
                $selecionado = $nomes[$index];
                break;
            }
        }
        
        // Preparar a resposta
        $response = [
            'sucesso' => true,
            'sorteado' => $selecionado,
            'numero_sorteado' => $random
        ];
        
        // Enviar a resposta como JSON
        echo json_encode($response);
        
    } else {
        // Caso o JSON não seja válido ou esteja vazio
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'JSON inválido ou vazio. Envie um objeto com nomes e probabilidades.'
        ]);
    }
    
} else {
    // Caso o método da requisição não seja POST
    http_response_code(405); // Método não permitido
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido. Use POST.'
    ]);
}
?>