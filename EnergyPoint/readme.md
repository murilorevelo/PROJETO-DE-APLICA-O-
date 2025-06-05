
## Requisitos

- PHP 7.4+
- MySQL
- Composer
- Conta no Firebase configurada com autenticação por e-mail/senha
- APIs ativadas no Google Cloud (Maps, Geolocation)

## Instalação

1. Clone o projeto ou extraia os arquivos.
2. Execute o script `bd.sql` no seu banco de dados MySQL.
3. Configure o arquivo `db.php` com os dados do seu banco.
4. Adicione o arquivo `credencial.json` com as credenciais da sua conta Firebase.
5. Execute `composer install` para instalar as dependências.
6. Acesse o sistema pelo navegador via localhost ou servidor.

--------------------------------------------------------------------------------------------
Rodar o script no cmd para instalar

 cd C:\xampp\htdocs\EnergyPoint
 composer require kreait/firebase-php

A pasta vendor é criada automaticamente pelo Composer, o gerenciador de dependências do PHP.
Ela contém todas as bibliotecas externas que seu projeto utiliza, como o SDK do Firebase (kreait/firebase-php) e seus arquivos auxiliares.
O arquivo autoload.php serve para carregar automaticamente todas essas dependências no seu código.

Resumo:

Não edite nada dentro da pasta vendor.
Não apague a pasta vendor enquanto estiver usando bibliotecas externas.
Sempre inclua require 'vendor/autoload.php'; para usar as dependências instaladas via Composer.
Se você apagar a pasta vendor, basta rodar composer install novamente para restaurá-la.

Os arquivos composer.json e composer.lock são criados e gerenciados pelo Composer, o gerenciador de dependências do PHP.

Para que serve cada um?
composer.json
É o arquivo onde você declara as dependências do seu projeto (por exemplo, kreait/firebase-php).
Ele também pode conter informações sobre o projeto, scripts, requisitos de versão do PHP, etc.

composer.lock
É gerado automaticamente quando você instala ou atualiza dependências.
Ele registra exatamente quais versões das bibliotecas foram instaladas, garantindo que todos que rodarem composer install tenham o mesmo ambiente.


## Licença

Projeto acadêmico ou de demonstração. Uso livre para fins educativos.
