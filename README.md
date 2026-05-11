# Musical Association Site

Site para a associacao musical onde toco, feito como projeto escolar inicial com HTML, PHP, MySQL e Docker Compose.

## Estrutura

- `www/`: entrada PHP em desenvolvimento.
- `Backend/`: ficheiros PHP de backend.
- `Database/associacaomusical.sql`: schema inicial da base de dados.
- `docker-compose.yml`: ambiente local com Apache/PHP e MySQL.

## Arrancar localmente

1. Instala o Docker Desktop.
2. Sobe os containers:

   ```powershell
   docker compose up -d
   ```

3. Abre a aplicacao:

   ```text
   http://localhost:8080
   ```

A base de dados fica disponivel em `localhost:3307`. O utilizador e `root` e a password e `root`.

## Trabalhar em varios PCs

Em cada PC novo:

```powershell
git clone https://github.com/AndrePx17/Musical-association-site.git
Set-Location Musical-association-site
docker compose up -d
```

Fluxo normal de trabalho:

```powershell
git pull
git status
git add .
git commit -m "descreve a alteracao"
git push
```

Mantem `config.php` e pastas de dados locais fora do Git.
