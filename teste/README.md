# Diretório de Testes (`teste/`)

Este diretório contém scripts para testes e geração de dados fictícios, usados para simular o funcionamento da rede social. Ele utiliza a biblioteca **Faker** para criar dados de usuários, publicações e outros elementos da aplicação.

---

## 📂 Estrutura do Diretório

```plaintext
teste/
├── criar_dados.php        # Script para gerar dados fictícios
├── README.md              # Documentação do diretório

🔧 Pré-requisitos
O projeto principal deve estar configurado corretamente, com as dependências já instaladas.
Este diretório depende do autoload do Composer (vendor/autoload.php) da raiz do projeto.

🚀 Como Usar
Navegue até o diretório principal do projeto da rede social.

cd /caminho/para/o/projeto
Execute o script diretamente:

php teste/criar_dados.php
🖥️ Funcionalidades
criar_dados.php
Este script realiza os seguintes testes:

Gera 50 usuários fictícios:

Nome.
E-mail.
Localização fictícia.
Cria publicações aleatórias para cada usuário:

Entre 1 e 10 publicações por usuário.
Simula uploads de imagens de perfil para 2 usuários.

Saída Esperada
O script exibirá no console algo como:

João Silva - joao.silva@example.com - postes: 5
Maria Oliveira - maria.oliveira@example.com - postes: 3
...
🛠️ Personalização
Número de usuários a serem gerados: Altere a variável $i no loop principal:

for ($i = 1; $i <= 50; $i++) { // Altere o 50 para o valor desejado.
Quantidade de postagens por usuário: Ajuste o intervalo gerado por rand():

$vezes_de_pbl = rand(1, 10); // Altere para (1, valor_desejado).
⚠️ Observações
Dependências: Certifique-se de que o diretório raiz do projeto contém as dependências instaladas com o Composer.

Validação de Dados: Este script é destinado apenas para testes e simulações. Não utilize os dados gerados diretamente em produção.

🤝 Contribuição
Se quiser melhorar este diretório ou adicionar novos testes:

Faça um fork do repositório principal.
Adicione ou edite os arquivos no diretório teste/.
Submeta suas alterações via Pull Request no repositório principal.
📄 Licença
Consulte o arquivo de licença do projeto principal para informações detalhadas.