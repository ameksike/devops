To initialize the database with a test table and some sample data, create an SQL file inside a directory named init. This script will be executed automatically when the PostgreSQL container starts for the first time.

```csharp
/project-directory
  ├── docker-compose.yml
  └── init
      └── init.sql
```

### Key Points in the docker-compose.yml:
- PostgreSQL service:
    - Uses the latest PostgreSQL image (postgres:latest).
    - Exposes port 5432 for database connections.
    - A volume postgres-data is used to persist data between container restarts.
    - A directory ./init is mounted to /docker-entrypoint-initdb.d/ to run initialization scripts when the container first starts.

- PgAdmin service:
    - Uses the latest PgAdmin image (dpage/pgadmin4:latest).
    - Exposes port 5050 to access PgAdmin via a web browser.
    - Environment variables are set to configure the default admin login (admin@example.com) and password (admin_password).

- Networking:
    - Both services are part of the same Docker network (postgres-network), allowing them to communicate internally.


### Access PgAdmin:

- Open your web browser and go to `http://localhost:5050`.
- Log in using the credentials specified in the docker-compose.yml (`admin@example.com` / `admin`).
- Connect PgAdmin to PostgreSQL:
    - In PgAdmin, add a new server with the following details:
        - Host: postgres
        - Port: 5432
        - database: test_db
        - Username: `postgres`
        - Password: `postgres`

### Get Container IP
- docker container ls 
- copy <CONTAINER ID>
- docker inspect <CONTAINER ID>
- docker inspect 7c3334661045
- Get from JSON the: "IPAddress": "172.19.0.2",

## References 
- [How to create a docker-compose setup with PostgreSQL and pgAdmin4](https://www.youtube.com/watch?v=qECVC6t_2mU)