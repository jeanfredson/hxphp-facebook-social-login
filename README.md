# HXPHP Facebook Social Login
Cadastro e Login com Facebook no seu projeto **HXPHP 2**.

## Instalação
+ Instale as dependências via **Composer** com o seguinte comando: `composer require facebook/graph-sdk`;
+ Adicione o módulo de configuração `Facebook`, deste repositório, ao seu projeto;
+ Registre o módulo de configuração no `RegisterModules.php`, e;
+ Copie a pasta `Facebook`, deste repositório, para a pasta de módulos do seu projeto.

## Configuração
+ Crie um aplicativo no Facebook;
+ Adicione o produto `Login do Facebook`;
+ Configure o `Login do Facebook` e informe as `URIs de redirecionamento do OAuth válidos`;
+ Liste as [permissões necessárias](https://developers.facebook.com/docs/facebook-login/permissions);
+ Revise o aplicativo (e envie uma análise se precisar de outras permissões) e torne ele `público`;
+ Defina as configurações, de acordo com o ambiente, no arquivo `app/config.php`. Os parâmetros são: `app_id`, `app_secret`, `permissions` e `fields`, respectivamente. Os valores informados no exemplo são ilustrativos, portanto, utilize apenas o que é necessário para a sua aplicação. Geralmente os dados fornecidos sem análise já são suficientes.
```php
$configs->env->development->facebook->setConfigs(
    'app_id',
    'app_secret',
    [
        'public_profile',
        'user_friends',
        'email',
        'user_about_me', // Esta permissão exige análise
        'user_birthday', // Esta permissão exige análise
        'user_education_history', // Esta permissão exige análise
        'user_hometown', // Esta permissão exige análise
        'user_location', // Esta permissão exige análise
        'user_relationships', // Esta permissão exige análise
        'user_work_history' // Esta permissão exige análise
    ],
    [
        'id',
        'name',
        'first_name',
        'last_name',
        'email',
        'gender',
        'picture.height(300).width(300)',
        'cover',
        'age_range',
        'birthday', //A permissão responsável deve ser aprovada
        'education', //A permissão responsável deve ser aprovada
        'hometown', //A permissão responsável deve ser aprovada
        'location', //A permissão responsável deve ser aprovada
        'relationship_status', //A permissão responsável deve ser aprovada
        'work' //A permissão responsável deve ser aprovada
    ]
);
```

## Uso
+ Carregue o módulo no método construtor do controller:
```php
$this->load('Modules\Facebook', $configs->facebook);
```
+ Recursos disponíveis:
    * 