# 📂 Diretório de Testes (`teste/`)

Este diretório contém scripts para **testes** e **geração de dados fictícios**, usados para simular o funcionamento da aplicação de rede social. Ele utiliza a biblioteca [Faker](https://fakerphp.github.io/) para criar dados como usuários, publicações e outros elementos do sistema.

---

## 🗂️ Estrutura do Diretório

```plaintext
teste/
├── criar_dados.php        # Script para gerar dados fictícios
├── README.md              # Documentação do diretório
```

---

## 🔧 Pré-requisitos

1. Certifique-se de que o **projeto principal** está configurado corretamente.
2. Instale as dependências do projeto com o Composer.
3. Este diretório depende do autoload do Composer (localizado em `vendor/autoload.php`) na raiz do projeto.

---

## 🚀 Como Usar

1. Navegue até o diretório principal do projeto:

   ```bash
   cd ./
   ```

2. Execute o script diretamente:

   ```bash
   php teste/criar_dados.php
   ```

---

## 🖥️ Funcionalidades

### `criar_dados.php`
O script realiza as seguintes tarefas:

1. **Gera 50 usuários fictícios**, com:
   - Nome.
   - E-mail.
   - Localização fictícia.
2. **Cria publicações aleatórias para cada usuário**:
   - Entre 1 e 10 publicações por usuário.
3. **Simula uploads de imagens de perfil** para 2 usuários.

---

## 📝 Saída Esperada

Ao executar o script, você verá uma saída no console semelhante a esta:

```plaintext
João Silva - joao.silva@example.com - publicações: 5
Maria Oliveira - maria.oliveira@example.com - publicações: 3
...
```

---

## ⚙️ Personalização

Você pode ajustar o comportamento do script alterando os seguintes parâmetros:

- **Número de usuários**:  
  Modifique o valor da variável no loop principal:  

  ```php
  for ($i = 1; $i <= 50; $i++) { // Altere o 50 para o valor desejado.
  ```

- **Quantidade de publicações por usuário**:  
  Ajuste o intervalo gerado pela função `rand()`:

  ```php
  $vezes_de_pbl = rand(1, 10); // Altere para (1, valor_desejado).
  ```

---

## ⚠️ Observações

- **Dependências**: Certifique-se de que as dependências estão instaladas via Composer na raiz do projeto.
- **Validação de Dados**: Este script é **destinado apenas para testes**. Não utilize os dados gerados diretamente em produção.

---

## 🤝 Contribuição

Contribuições são bem-vindas! Para contribuir:

1. Faça um **fork** do repositório principal.
2. Adicione ou edite os arquivos no diretório `teste/`.
3. Submeta suas alterações via **Pull Request** no repositório principal.

---

## 📄 Licença

Consulte o arquivo de licença do projeto principal para informações detalhadas.

---