<?php
// Definir o cabeçalho para permitir requisições de qualquer origem e especificar que o conteúdo retornado será JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Verificar se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ler o conteúdo bruto da requisição
    $input = file_get_contents('php://input');
    
    // Tentar decodificar o JSON recebido
    $data = json_decode($input, true);
    
    // Verificar se o JSON foi decodificado corretamente e identificar o campo 'telefone'
    if (json_last_error() === JSON_ERROR_NONE) {
        
        // Extrair o telefone, considerando os dois possíveis formatos
        if (isset($data['customData']['telefone'])) {
            $telefone = preg_replace('/\D/', '', $data['customData']['telefone']);
        } elseif (isset($data['telefone'])) {
            $telefone = preg_replace('/\D/', '', $data['telefone']);
        } else {
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Número de telefone não fornecido.'
            ]);
            exit;
        }

        // Adicionar o prefixo +55 caso esteja ausente
        if (strpos($telefone, '55') !== 0) {
            $telefone = '55' . $telefone;
        }

        // Verifica se o telefone tem 12 dígitos (sem +55) e adiciona o dígito "9" após o código do país
        if (strlen($telefone) === 12) {
            $telefone = '55' . substr($telefone, 2, 2) . '9' . substr($telefone, 4);
        }

        // Verifica se o telefone ajustado agora tem 13 dígitos
        if (strlen($telefone) === 13) {
            // Preparar a resposta com o número formatado
            $telefoneComMais = '+' . $telefone;
            $response = [
                'sucesso' => true,
                'telefone' => $telefoneComMais
            ];
            echo json_encode($response);
        } else {
            // Responde com erro se o número não for válido
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Número de telefone inválido. Certifique-se de que ele tenha 12 ou 13 dígitos sem o +55.'
            ]);
        }

    } else {
        // JSON inválido
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'JSON inválido.'
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
