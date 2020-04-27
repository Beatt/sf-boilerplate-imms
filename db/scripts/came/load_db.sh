#/bin/bash

PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < nivel_academico.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < carrera.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < categoria.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < ciclo_academico.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < institucion.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < region.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < delegacion.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < tipo_unidad.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < unidad.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < convenios.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < departamento.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < permiso.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < rol.sql
PGPASSFILE=/tmp/.pgpass psql -U main -h localhost --port=33417 main -w < usuario.sql
