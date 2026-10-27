# Prueba Backend - API de Productos

API REST en Laravel para gestionar productos y sus precios en distintas divisas.

## Instalación

```bash
git clone https://github.com/Javistella77/prueba-backend
cd prueba-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Usuario de prueba: `admin@test.com` / `password`

## Autenticación

Se usa Laravel Sanctum. Se hace `POST /api/login` y se manda el token en el header
`Authorization: Bearer <token>`.

## Endpoints

| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | /api/login | Obtener token |
| POST | /api/logout | Cerrar sesión |
| GET | /api/products | Listar productos (paginado) |
| POST | /api/products | Crear producto |
| GET | /api/products/{id} | Ver producto |
| PUT | /api/products/{id} | Actualizar producto |
| DELETE | /api/products/{id} | Eliminar producto |
| GET | /api/products/{id}/prices | Listar precios del producto |
| POST | /api/products/{id}/prices | Agregar precio en otra divisa |

## Tests

```bash
php artisan test
```

## Documentación

Colección de Postman en `docs/postman_collection.json`.