# HXPHP Facebook Social Login
Login e Cadastro com Facebook no seu projeto HXPHP 2.

## Instalação
+ Instale as dependências via **Composer** com o seguinte comando: `composer require facebook/graph-sdk`;
+ Adicione o módulo de configuração `Facebook`, deste repositório, ao seu projeto;
+ Registre o módulo de configuração no `RegisterModules.php`, e;
+ Copie a pasta `Facebook`, deste repositório, para a pasta de módulos do seu projeto.

## Uso
+ Crie um aplicativo no Facebook;
+ Adicione o produto `Login do Facebook`;
+ Configure o `Login do Facebook` e informe as `URIs de redirecionamento do OAuth válidos`;
+ Liste as permissões necessárias;
+ Revise o aplicativo (e envie uma análise se precisar de outras permissões) e torne ele `público`;
+ Configurando:
```php
$configs->env->production->facebook->setConfigs(
    'app_id',
    'app_secret',
    [
        'public_profile',
        'user_friends',
        'email',
        'user_about_me',
        'user_birthday',
        'user_education_history',
        'user_hometown',
        'user_location',
        'user_relationships',
        'user_work_history'
    ],
    [
        'id',
        'name',
        'picture.height(300).width(300)',
        'age_range',
        'birthday',
        'cover',
        'education',
        'email',
        'first_name',
        'gender',
        'hometown',
        'is_verified',
        'last_name',
        'location',
        'relationship_status',
        'work'
    ]
);
```