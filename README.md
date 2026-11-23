# Atividades_DAW

# Inclusão de Disciplina

Exercício da disciplina **Desenvolvimento de Aplicações Web (DAW)** da FAETERJ-Rio.

Cadastro de disciplinas (sigla, nome e carga horária) em um banco **MySQL**. O front-end
envia os dados com **XMLHttpRequest** e o back-end em **PHP** grava no banco e responde em **JSON**.

## Tecnologias

- HTML
- JavaScript (XMLHttpRequest + FormData)
- PHP (mysqli com prepared statements)
- MySQL (XAMPP)

## Arquivos

| Arquivo | Função |
|---|---|
| `incluirDisciplina.html` | Formulário. Envia os dados para o PHP e mostra a resposta na tela |
| `IncluirDisciplina.php` | Conecta no banco, valida os dados, faz o `INSERT` e devolve JSON |
| `banco.sql` | Cria o banco `daw` e a tabela `disciplinas` |

## Tabela

```sql
CREATE TABLE disciplinas (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    sigla         VARCHAR(10)  NOT NULL UNIQUE,
    nome          VARCHAR(100) NOT NULL,
    carga_horaria INT          NOT NULL
);
```

## Como executar

1. Inicie o **Apache** e o **MySQL** no painel do XAMPP.
2. Abra `http://localhost/phpmyadmin`, vá na aba **Importar**, selecione `banco.sql` e clique em **Executar**.
3. Copie os arquivos para uma pasta dentro de `C:\xampp\htdocs\` (ex.: `htdocs\disciplina`).
4. Acesse `http://localhost/disciplina/incluirDisciplina.html`.

> Se o seu MySQL tiver senha, altere a variável `$password` no início do `IncluirDisciplina.php`.

## Como funciona

```
incluirDisciplina.html ──(POST via XMLHttpRequest)──> IncluirDisciplina.php ──(mysqli)──> MySQL
          ▲                                                    │
          └──────────────────(resposta em JSON)────────────────┘
```

1. O usuário preenche o formulário e clica em **Incluir Disciplina**.
2. O JavaScript envia os campos com `FormData` para o `IncluirDisciplina.php`.
3. O PHP valida os dados e insere a disciplina na tabela `disciplinas`.
4. O PHP devolve um JSON, e o JavaScript mostra a mensagem na tela.

## Validações (feitas no PHP)

- Sigla, nome e carga horária são obrigatórios.
- A sigla é convertida para maiúsculas (`daw` → `DAW`) e não pode se repetir.
- A sigla tem no máximo 10 caracteres.
- A carga horária precisa ser um número inteiro maior que zero.

## Exemplos de resposta

Sucesso:

```json
{
  "sucesso": true,
  "mensagem": "Disciplina incluída com sucesso!",
  "disciplina": { "id": 1, "sigla": "DAW", "nome": "Desenvolvimento de Aplicações Web", "carga_horaria": 80 }
}
```

Erro:

```json
{ "sucesso": false, "mensagem": "Já existe uma disciplina com a sigla DAW." }
```

## Autor

Henrique Oliveira — Análise e Desenvolvimento de Sistemas, FAETERJ-Rio
