<?php

return [
    "reset_password" => [
        'subject' => 'Notificação de redefinição de senha',
        'line_one' => 'Você está recebendo este email porque recebemos uma solicitação de redefinição de senha de sua conta.',
        'action' => 'Redefinir senha',
        'line_two' => 'Este link de redefinição de senha irá expirar em :count minutos.',
        'line_three' => 'Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.',
    ],
    'user_password' => [
        'subject' => 'Senha',
        'password' => 'Senha: ',
        'paragraph' => 'Você foi registrado em nossa plataforma, se você reconhece esta atividade, por favor não compartilhe e altere. Aqui está sua senha temporária para acessar sua conta:',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ],
    'login' => [
        'subject' => 'Tentativa de login',
        'date' => 'Data: ',
        'ip' => 'Endereço de IP: ',
        'paragraph' => 'Novo acesso à nossa plataforma com o seu email, caso não reconheça esta actividade, por favor actualize a sua palavra-passe, desactive a sua conta e contacte-nos imediatamente.',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ],
    'two_factor' => [
        'subject' => 'Atualização de configuração de dois fatores',
        'status' => 'Estado: ',
        'active' => 'Activo',
        'deactivate' => 'Desativo',
        'verification' => 'Verificação: ',
        'paragraph' => 'Você atualizou a configuração de dois fatores de sua conta, caso não reconheça esta actividade, por favor actualize a sua palavra-passe, desactive a sua conta e contacte-nos imediatamente.',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ],
    'cellphone' => [
        'subject' => 'Atualização de celular',
        'paragraph' => 'Você atualizou seu celular, caso não reconheça esta actividade, por favor actualize a sua palavra-passe, desactive a sua conta e contacte-nos imediatamente.',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ],
    'recovery_email' => [
        'subject' => 'Atualização de email',
        'paragraph' => 'Você atualizou seu e-mail, caso não reconheça esta atividade, clique no botão abaixo para recuperar seu e-mail, em seguida ative a autenticação de dois fatores para proteger sua conta.',
        'email' => 'Novo e-mail da sua conta: ',
        'action' => 'Recuperar e-mail',
        'expires' => 'Este link de recuperação de e-mail irá expirar em :count minutos.',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ],
    'password' => [
        'subject' => 'Atualização de senha',
        'paragraph' => 'Você atualizou a sua senha, caso não reconheça esta actividade, por favor recupere a sua palavra-passe e ative a autenticação de dois fatores.',
        'footer' => 'Esta é uma mensagem automática, por favor, não responda.',
    ],
];
