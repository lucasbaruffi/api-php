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
    
    // Verificar se o número de telefone foi enviado e é uma string
    if (isset($data['telefone']) && is_string($data['telefone'])) {
        $telefone = preg_replace('/\D/', '', $data['telefone']); // Remove todos os caracteres não numéricos
        
        // Verifica se o telefone tem 12 ou 13 dígitos
        if (strlen($telefone) === 12) {
            // Adiciona o dígito "9" após o código do país e antes do código de área
            $telefone = substr($telefone, 0, 4) . '9' . substr($telefone, 4);
        }
        
        // Verifica se o telefone ajustado agora tem 13 dígitos
        if (strlen($telefone) === 13) {
            // Preparar a resposta com o número formatado
            $response = [
                'sucesso' => true,
                'telefone' => $telefone
            ];
            echo json_encode($response);
        } else {
            // Responde com erro se o número não for válido
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Número de telefone inválido. Certifique-se de que ele tenha 12 ou 13 dígitos.'
            ]);
        }
        
    } else {
        // Caso o JSON não contenha o telefone ou seja inválido
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'JSON inválido ou número de telefone não fornecido.'
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
