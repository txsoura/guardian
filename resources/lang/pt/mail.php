<?php

return [
    'two_factor_code' => [
        'subject' => 'Verificação de dois fatores',
        'code' => 'Código: ',
        'paragraph' => 'Alguém está tentando usar sua conta, se você reconhece esta atividade, confirme com o código de ativação. Aqui está o código de ativação da sua conta:',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ],
    "verify_email" => [
        'subject' => 'Verificação de email',
        'line_one' => 'Clique no botão abaixo para verificar o seu email.',
        'action' => 'Verificação de email',
        'line_two' => 'Se você não criou uma conta, nenhuma ação adicional é necessária.',
    ],
    "reset_password" => [
        'subject' => 'Notificação de redefinição de senha',
        'line_one' => 'Você está recebendo este email porque recebemos uma solicitação de redefinição de senha de sua conta.',
        'action' => 'Redefinir senha',
        'line_two' => 'Este link de redefinição de senha irá expirar em :count minutos.',
        'line_three' => 'Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.',
    ],
    'password' => [
        'subject' => 'Senha',
        'password' => 'Senha: ',
        'paragraph' => 'Você foi registrado em nossa plataforma, se você reconhece esta atividade, por favor não compartilhe e altere. Aqui está sua senha temporária para acessar sua conta:',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ]
];
