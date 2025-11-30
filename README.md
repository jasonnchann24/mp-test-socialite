## Laravel Octane + RoadRunner 

### Experimental
- Octane + RR
- Lightweight ports and adapters (pragmatic hexagonal) structure style on top of Laravel

### Stack
- Laravel
- Traefik
- Postgres

### Features
- Auth (Socialite, jwt-auth)

### Prereqs
- Docker & Docker Compose
- Optional: add your user to the `docker` group or use `sudo` for Docker commands.

### Setup
```bash
docker compose build

docker compose run --rm app sh -lc '
  composer install &&
  cp .env.example .env &&
  php artisan key:generate &&
  php artisan jwt:secret --force &&
  php artisan migrate
'
```

### Run
```bash
docker compose up -d
```

- App: [http://localhost:8080](http://localhost:8080)
- Swagger: [http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)
- Postman collection at [here](./MP-TEST-SOCIALITE.postman_collection.json)

### Notes

- Get Google Access Token 
    - https://developers.google.com/oauthplayground/
    - Google OAuth2 API v2 -> Authorize APIs -> Exchange authorization code for tokens
- Get Facebook Access Token (_untested_)
    - https://developers.facebook.com/tools/explorer/


### Backlog

- [ ] Refresh token should be put into response http only cookie
- [ ] Test coverage
