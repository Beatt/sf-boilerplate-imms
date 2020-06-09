#/bin/bash

PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < permiso.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < rol.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < usuario.sql
