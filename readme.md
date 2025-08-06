# 🚀 API RESTful de Usuários - CodeIgniter 3

Uma API moderna e segura para gerenciamento de usuários, construída com CodeIgniter 3, PHP 8.1 e MySQL, rodando em ambiente Docker.

![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?style=flat&logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3-EF4223?style=flat&logo=codeigniter&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-28.2.2-2496ED?style=flat&logo=docker&logoColor=white)

## 📋 Índice

- [Características](#-características)
- [Pré-requisitos](#-pré-requisitos)
- [Instalação](#-instalação)
- [Configuração](#-configuração)
- [Uso da API](#-uso-da-api)
- [Endpoints](#-endpoints)
- [Exemplos](#-exemplos)
- [Segurança](#-segurança)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Contribuição](#-contribuição)

## ✨ Características

- 🔐 **Segurança Robusta** : Validação de entrada, sanitização de dados e hash de senhas
- 📄 **Paginação Inteligente** : Listagem de usuários com controle de paginação
- 🌐 **CORS Configurado** : Suporte completo para requisições cross-origin
- 📊 **Respostas Padronizadas**: Formato JSON consistente em todas as respostas
- ⚡ **Performance Otimizada** : Query Builder e índices de banco otimizados
- 🐳 **Docker Ready** : Ambiente completamente containerizado
- 📝 **Logs Detalhados** : Sistema completo de logging para debugging
- 🔄 **CRUD Completo** : Create, Read, Update e Delete implementados

## 🔧 Pré-requisitos

- Docker > = 28.2.2
- Docker Compose > = 2.0
- Git

## 🐳 Instalação

### 1. Clone o Repositório

```bash
git clone https: //github.com/diegoreisdev/api-usuarios-ci3.git
cd api-usuarios-ci3
```

### 2. Suba os Containers

```bash
docker compose up -d
```

### 3. Configure o CodeIgniter

Ajuste o arquivo `application/config/database.php`:

```php
$db['default'] = array(
	'dsn'      => '',
	'hostname' => 'db',
	'username' => 'api-ci3',
	'password' => 'api-ci3',
	'database' => 'api-ci3',
	'dbdriver' => 'mysqli',
      // ... resto da configuração
);
```

## 🌐 Uso da API

### Base URL

```
http: //localhost:8000/api/users
```

## 📡 Endpoints

| Método   | Endpoint          | Descrição                               |
| -------- | ----------------- | --------------------------------------- |
| `GET`    | `/api/users`      | Lista todos os usuários (com paginação) |
| `GET`    | `/api/users/{id}` | Obtém um usuário específico             |
| `POST`   | `/api/users`      | Cria um novo usuário                    |
| `PUT`    | `/api/users/{id}` | Atualiza um usuário existente           |
| `DELETE` | `/api/users/{id}` | Remove um usuário                       |

## 💡 Exemplos

### 📝 Criar Usuário

```bash
curl -X POST http: //localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name"    : "Diego Reis",
    "email"   : "diego@email.com",
    "password": "123456",
    "phone"   : "11666666666",
  }'
```

**Resposta: **

```json
{
	"success": true,
	"message": "Usuário criado com sucesso",
	"data": {
		"id": 1,
		"name": "Diego Reis",
		"email": "diego@email.com",
		"phone": "11666666666",
		"status": "active",
		"created_at": "2025-08-05 14:30:00",
		"updated_at": "2025-08-05 14:30:00"
	}
}
```

### 📋 Listar Usuários com Paginação

```bash
curl "http://localhost:8000/api/users?page=1&per_page=10"
```

**Resposta: **

```json
{
	"success": true,
	"message": "Usuários listados com sucesso",
	"data": [
		{
			"id": 1,
			"name": "Diego Reis",
			"email": "diego@email.com",
			"phone": "11666666666",
			"status": "active",
			"created_at": "2025-08-05 14:30:00",
			"updated_at": "2025-08-05 14:30:00"
		}
	],
	"pagination": {
		"current_page": 1,
		"per_page": 10,
		"total": 1,
		"total_pages": 1,
		"has_next": false,
		"has_prev": false
	}
}
```

### 🔍 Buscar Usuário por ID

```bash
curl http: //localhost:8000/api/users/1
```

### ✏️ Atualizar Usuário

```bash
curl -X PUT http: //localhost:8000/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name" : "Diego Reis",
    "phone": "11888888888"
  }'
```

### 🗑️ Deletar Usuário

```bash
curl -X DELETE http: //localhost:8000/api/users/1
```

## 🔐 Segurança

### Validações Implementadas

- ✅ **Validação de entrada** : Todos os campos são validados
- ✅ **Sanitização** : Dados são limpos contra XSS
- ✅ **Email único** : Verificação de duplicatas
- ✅ **Hash de senhas** : Senhas criptografadas com `password_hash()`
- ✅ **Proteção SQL Injection**: Query Builder seguro

### Campos Obrigatórios

#### Criação (`POST`)

```json
{
	"name": "string (2-100 caracteres)",
	"email": "string (email válido, único)",
	"password": "string (6-50 caracteres)"
}
```

#### Campos Opcionais

```json
{
	"phone": "string (máx 20 caracteres)",
	"status": "enum (active|inactive)"
}
```

## 📁 Estrutura do Projeto

```
api-usuarios-ci3/
├── 📂 application/
│   ├── 📂 config/
│   │   ├── database.php
│   │   └── routes.php
│   ├── 📂 controllers/api/
│   │   └── Users.php
│   ├── 📂 libraries/
│   │   ├── Api_response.php
│   │   └── Api_validator.php
│   └── 📂 models/
│       └── User_model.php
├── 📂 database/
│   └── init.sql
└── 📂 docker/
    └── 📂 nginx/
        └── default.conf
        📂 php/
        └── Dockerfile
        📂 redis/
        └── Dockerfile
├── 🐳 docker-compose.yml
├── 📄 README.md
```

## 🔧 Comandos Úteis

### Docker

```bash
# Iniciar os serviços
docker compose up -d

# Ver logs em tempo real
docker compose logs -f

# Parar os serviços
docker compose down

# Rebuild dos containers
docker compose up --build -d

# Entrar no container web
docker exec -it api-ci3-app bash

# Entrar no container MySQL
docker exec -it api-ci3-db mysql -u api-ci3 -p
```

### Debugging

```bash
# Ver logs do Apache
docker exec api-ci3-app tail -f /var/log/apache2/error.log

# Ver logs do PHP
docker exec api-ci3-app tail -f /var/log/php_errors.log

# Status dos containers
docker compose ps
```

## 📊 Monitoramento

### phpMyAdmin

Acesse: `http://localhost:8080/`

- **Usuário**: `api-ci3`
- **Senha** : `api-ci3`

### Logs da Aplicação

Os logs ficam armazenados em:

- Apache : `/var/log/apache2/`
- PHP : `/var/log/php_errors.log`
- CodeIgniter: `application/logs/`

## 🐛 Resolução de Problemas

### Erro de Conexão com Banco

```bash
# Verificar se o MySQL está rodando
docker compose ps

# Recriar o container do banco
docker compose down db
docker compose up -d db
```

### Erro de Permissões

```bash
# Dar permissão para o Apache escrever logs
docker exec api-ci3-app chown -R www-data: www-data /var/www/html/application/logs/
```

### Container não inicia

```bash
# Ver logs detalhados
docker compose logs api-ci3-app
docker compose logs db
```

## 🤝 Contribuição

1. **Fork** o projeto
2. Crie uma **branch** para sua feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. **Push** para a branch (`git push origin feature/AmazingFeature`)
5. Abra um **Pull Request**

## 📜 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👥 Autor

- **Diego Reis** - _Desenvolvimento Inicial_ - [@diegoreisdev](https://github.com/diegoreisdev)

## 🙏 Agradecimentos

- [CodeIgniter](https://codeigniter.com/) - Framework PHP
- [Docker](https://docker.com/) - Containerização
- [MySQL](https://mysql.com/) - Banco de dados

---

⭐ **Gostou do projeto?** Deixe uma estrela no repositório!

📧 **Dúvidas?** Abra uma [issue](https://github.com/diegoreisdev/api-usuarios-ci3/issues) ou entre em contato!
