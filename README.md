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
+ Defina a URI de redirecionamento:
```php
$domain = $configs->site->url;
$facebook_redirect_uri = $domain . $this->getRelativeURL('login/facebook/', false);
```
+ Gere a URI de login:
```php
$facebookLoginURI = $this->facebook->loginUrl->get($facebook_redirect_uri);
```
+ Passe a URI de login para a view:
```php
$this->view->setTitle('HXPHP - Faça login')
            ->setVars([
                'facebook_login_url' => $facebookLoginUrl
            ]);
```
+ Adicione as configurações `(app/config.php)` do serviço de autenticação:
```php
$configs->env->development->auth->setURLs('/sistema/home/', '/sistema/login/');
```
+ Carregue o serviço de autenticação no método construtor do controller:
```php
$this->load(
    'Services\Auth',
    $configs->auth->after_login,
    $configs->auth->after_logout,
    true
);
```
+ Adicione as colunas `facebook_id` e `auth_type` à coluna `users` do seu banco de dados. A coluna `auth_type` pode ser do tipo `ENUM` e o objetivo dela é diferenciar quem foi cadastrado através do formulário e quem foi através do Facebook;
+ Crie a `action` de acordo com a URI de redirecionamento que foi definida, isto é, como no exemplo consta `login/facebook/`, a action é `facebook`:
```php
    public function facebookAction()
    {
        $this->auth->redirectCheck(true);
        $this->view->setFile('index');
    
        $userData = $this->facebook->getUserData(); //Array com os dados do usuário

        if ($this->facebook->errors->hasError() || is_null($userData)) {
            // Se ocorrer um erro durante o processo já será carregado no Alert helper. 
            // Portanto, certifique-se de adicioná-lo à view.
            $this->load('Helpers\Alert', $this->facebook->errors->getErrors());
        }
        else {
            if (!isset($userData['email'])) {
                // É preciso que o usuário permita que o aplicativo obtenha o e-mail usado no Facebook. 
                $this->load('Helpers\Alert', [
                    'danger',
                    'Oops! Não foi possível resgatar o seu e-mail. Por favor, verifique e tente novamente'
                ]);
            }
            else {
                // Uma maneira simples e de fácil entendimento para verificar se este usuário já está cadastrado. 
                // Pode ser alterado para usar a id e também otimizado para retornar apenas o COUNT e etc.
                $exists = User::find_by_email_and_auth_type($userData['email'], 'Facebook'); 

                if (is_null($exists)) {
                    // É o seu primeiro login, portanto, é feito o cadastro
                    $post = [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'auth_type' => 'Facebook',
                        'facebook_id' => $userData['id']
                    ];
                    
                    // Este exemplo considera que seu método de cadastro é baseado neste padrão:
                    // https://github.com/brunosantoshx/serie-criando-sistema-de-cadastro-e-login/blob/master/app/models/User.php
                    $cadastrarUsuario = User::cadastrar($post, 'user');

                    if ($cadastrarUsuario->status === false) {
                        $this->load('Helpers\Alert', array(
                            'danger',
                            'Ops! Não foi possível efetuar seu cadastro. <br> Verifique os erros abaixo:',
                            $cadastrarUsuario->errors
                        ));
                    }
                    else {
                        /*
                        Se você possui permissões adicionais aprovadas pode salvar outras informações do usuário em uma tabela específica.
                        foreach ($userData as $field => $value) {
                            UserMeta::addMeta($cadastrarUsuario->user->id, $field, $value);
                        }
                        */
                        
                        // LOGIN do usuário após o cadastro
                        return $this->auth->login($cadastrarUsuario->user->id, $cadastrarUsuario->user->email);
                    }
                }
                else {
                    if ($exists->status === 'Inactive') {
                        $this->load('Helpers\Alert', [
                            'danger',
                            'Oops! Não foi possível continuar com o seu login.',
                            'O seu acesso encontra-se bloqueado. Por favor, contate o suporte para que a situação seja resolvida.'
                        ]);
                    }
                    else {
                        // LOGIN do usuário
                        return $this->auth->login($exists->id, $exists->email);
                    }
                }
            }
        }
    }
```
