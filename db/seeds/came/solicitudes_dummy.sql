--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.23
-- Dumped by pg_dump version 10.12 (Ubuntu 10.12-0ubuntu0.18.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: solicitud; Type: TABLE DATA; Schema: public; Owner: main
--

COPY public.solicitud (id, no_solicitud, fecha, estatus, referencia_bancaria, monto, tipo_pago, documento, url_archivo, validado, fecha_comprobante, observaciones) FROM stdin;
1	NS_000001	2020-05-07	Solicitud confirmada	\N	\N	\N	\N	\N	\N	\N	\N
2	NS_000002	2020-05-07	Solicitud creada	\N	\N	\N	\N	\N	\N	\N	\N
\.


--
-- Data for Name: campo_clinico; Type: TABLE DATA; Schema: public; Owner: main
--

COPY public.campo_clinico (id, convenio_id, solicitud_id, estatus_campo_id, unidad_id, fecha_inicial, fecha_final, horario, promocion, lugares_solicitados, lugares_autorizados, referencia_bancaria, monto, asignatura) FROM stdin;
1	3	1	\N	10	2020-05-07	2020-05-18	\N	\N	11	11	\N	-1	\N
2	3	1	\N	9	2020-06-05	2020-05-13	\N	\N	11	11	\N	-1	\N
3	38	2	\N	15	2020-05-26	2020-05-27	\N	\N	10	10	\N	-1	\N
\.


--
-- Name: campo_clinico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: main
--

SELECT pg_catalog.setval('public.campo_clinico_id_seq', 3, true);


--
-- Name: solicitud_id_seq; Type: SEQUENCE SET; Schema: public; Owner: main
--

SELECT pg_catalog.setval('public.solicitud_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

