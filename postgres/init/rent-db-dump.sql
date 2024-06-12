--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1
-- Dumped by pg_dump version 16.1

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
-- Name: rent_db; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE rent_db WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'ru_RU.UTF-8';


ALTER DATABASE rent_db OWNER TO postgres;

\connect rent_db

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: building_condition; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.building_condition (
    id smallint NOT NULL,
    name character varying(50),
    CONSTRAINT building_condition_name_check CHECK ((length((name)::text) > 0))
);


ALTER TABLE public.building_condition OWNER TO postgres;

--
-- Name: building_condition_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.building_condition_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.building_condition_id_seq OWNER TO postgres;

--
-- Name: building_condition_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.building_condition_id_seq OWNED BY public.building_condition.id;


--
-- Name: building_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.building_type (
    id smallint NOT NULL,
    name character varying(50),
    CONSTRAINT building_type_name_check CHECK ((length((name)::text) > 0))
);


ALTER TABLE public.building_type OWNER TO postgres;

--
-- Name: building_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.building_type_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.building_type_id_seq OWNER TO postgres;

--
-- Name: building_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.building_type_id_seq OWNED BY public.building_type.id;


--
-- Name: client; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.client (
    id bigint NOT NULL,
    organisation_name character varying(50),
    email character varying(150) NOT NULL,
    address character varying(200),
    contact_person character varying(150) NOT NULL,
    phone_number character varying(20)
);


ALTER TABLE public.client OWNER TO postgres;

--
-- Name: client_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.client_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.client_id_seq OWNER TO postgres;

--
-- Name: client_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.client_id_seq OWNED BY public.client.id;


--
-- Name: office; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.office (
    id bigint NOT NULL,
    owner_id bigint,
    building_type_id smallint,
    building_condition_id smallint,
    address character varying(200) NOT NULL,
    floor smallint,
    total_surface smallint NOT NULL,
    monthly_rent integer NOT NULL,
    has_parking_lot boolean,
    has_air_conditioning boolean,
    has_coffee_machine boolean,
    has_wc boolean,
    has_security boolean,
    CONSTRAINT office_floor_check CHECK ((floor > 0)),
    CONSTRAINT office_monthly_rent_check CHECK ((monthly_rent > 0)),
    CONSTRAINT office_total_surface_check CHECK ((total_surface > 0))
);


ALTER TABLE public.office OWNER TO postgres;

--
-- Name: office_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.office_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.office_id_seq OWNER TO postgres;

--
-- Name: office_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.office_id_seq OWNED BY public.office.id;


--
-- Name: rent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rent (
    id bigint NOT NULL,
    office_id bigint NOT NULL,
    client_id bigint,
    start_date date NOT NULL,
    end_date date NOT NULL,
    termination_date date,
    termination_reason_id smallint
);


ALTER TABLE public.rent OWNER TO postgres;

--
-- Name: rent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rent_id_seq OWNER TO postgres;

--
-- Name: rent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rent_id_seq OWNED BY public.rent.id;


--
-- Name: termination_reason; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.termination_reason (
    id smallint NOT NULL,
    name character varying(200),
    CONSTRAINT termination_reason_name_check CHECK ((length((name)::text) > 0))
);


ALTER TABLE public.termination_reason OWNER TO postgres;

--
-- Name: termination_reason_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.termination_reason_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.termination_reason_id_seq OWNER TO postgres;

--
-- Name: termination_reason_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.termination_reason_id_seq OWNED BY public.termination_reason.id;


--
-- Name: building_condition id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.building_condition ALTER COLUMN id SET DEFAULT nextval('public.building_condition_id_seq'::regclass);


--
-- Name: building_type id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.building_type ALTER COLUMN id SET DEFAULT nextval('public.building_type_id_seq'::regclass);


--
-- Name: client id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.client ALTER COLUMN id SET DEFAULT nextval('public.client_id_seq'::regclass);


--
-- Name: office id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.office ALTER COLUMN id SET DEFAULT nextval('public.office_id_seq'::regclass);


--
-- Name: rent id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rent ALTER COLUMN id SET DEFAULT nextval('public.rent_id_seq'::regclass);


--
-- Name: termination_reason id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.termination_reason ALTER COLUMN id SET DEFAULT nextval('public.termination_reason_id_seq'::regclass);


--
-- Data for Name: building_condition; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.building_condition (id, name) FROM stdin;
1	Офисная отделка
2	Под чистовую отделку
3	Требуется косметический ремонт
4	Требуется капитальный ремонт
\.


--
-- Data for Name: building_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.building_type (id, name) FROM stdin;
1	Бизнес-центр
2	Офисное здание
3	Административное здание
4	Многофункциональный комплекс
5	Помещение свободного назначения
6	Жилое здание
\.


--
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.client (id, organisation_name, email, address, contact_person, phone_number) FROM stdin;
1	\N	bvase0@springer.com	67933 American Ash Lane	Betty Vase	\N
2	\N	cdracksford1@elegantthemes.com	22 Barnett Place	Constancia Dracksford	+33 (431) 731-9003
3	Predovic, Leannon and McLaughlin	rbettesworth2@google.co.uk	1 Doe Crossing Lane	Renee Bettesworth	\N
4	\N	mduncklee3@biglobe.ne.jp	632 Oneill Avenue	Madelaine Duncklee	\N
5	\N	bilyushkin4@webnode.com	\N	Bertha Ilyushkin	+57 (790) 666-9150
6	\N	fmacconnulty5@engadget.com	\N	Fabien MacConnulty	\N
7	Schmidt-Prosacco	isimkins6@baidu.com	\N	Inigo Simkins	\N
8	Kessler-Wilkinson	helles7@go.com	5579 Memorial Lane	Hermina Elles	\N
9	DuBuque, Funk and Gulgowski	lartingstall8@macromedia.com	5 Northport Center	Leontine Artingstall	+84 (836) 313-8243
10	Koelpin Inc	ilorenc9@reverbnation.com	\N	Ignace Lorenc	+7 (702) 605-3581
11	Yost, Gottlieb and Schroeder	pthreddera@discovery.com	32926 Jackson Drive	Paola Thredder	+595 (604) 751-5548
12	Williamson, Harber and Hermiston	lburgynb@feedburner.com	\N	Lottie Burgyn	\N
13	Gulgowski Group	keynaldc@adobe.com	65185 Marcy Circle	Keely Eynald	\N
14	Bergstrom, Gleason and Schinner	oszymanowiczd@sun.com	121 Montana Circle	Opal Szymanowicz	\N
15	Rutherford, Nikolaus and Wyman	sspinkee@unesco.org	\N	Saxon Spinke	+86 (943) 407-8888
16	\N	chewf@blinklist.com	\N	Chrissy Hew	\N
17	West LLC	raicking@adobe.com	4 Anderson Alley	Ricky Aickin	+967 (817) 955-0173
18	Cremin LLC	aabelovh@howstuffworks.com	822 Dawn Trail	Allys Abelov	+86 (484) 341-8903
19	Pouros-White	chuskissoni@sohu.com	123 Washington Way	Carce Huskisson	\N
20	Koch LLC	bestevezj@dyndns.org	1 Boyd Way	Barry Estevez	+1 (916) 431-6044
21	\N	hducketk@qq.com	53255 New Castle Alley	Harlie Ducket	+92 (486) 972-9989
22	\N	mrotherhaml@cyberchimps.com	980 8th Point	Morgan Rotherham	\N
23	\N	fterronm@cbsnews.com	203 Mayer Avenue	Fannie Terron	\N
24	\N	dpolleyen@imdb.com	02817 Bartillon Place	Drud Polleye	+62 (434) 333-9459
25	Beahan Group	ccolbecko@posterous.com	281 Sycamore Crossing	Claude Colbeck	\N
26	\N	ddombp@yale.edu	5 Kinsman Plaza	Dulciana Domb	+63 (383) 323-1962
27	\N	dgearingq@miibeian.gov.cn	0 Doe Crossing Avenue	Duke Gearing	+7 (392) 938-1668
28	\N	idir@usda.gov	6 New Castle Park	Ilyse Di Giorgio	+86 (387) 565-9660
29	Keebler, Goldner and Koelpin	dveeverss@ucoz.ru	\N	Dagny Veevers	+86 (892) 195-4183
30	Fay, Orn and Hyatt	twurstt@twitpic.com	09842 Dottie Court	Thibaut Wurst	+63 (674) 194-5469
31	\N	glucianu@odnoklassniki.ru	77412 Goodland Pass	Goldi Lucian	+62 (431) 592-1897
32	\N	merswellv@wix.com	9 Sunbrook Point	Meredeth Erswell	+351 (919) 148-0243
33	Beier and Sons	gshadboltw@about.com	552 Sullivan Center	Genovera Shadbolt	+86 (588) 831-8411
34	\N	cgerardetx@nyu.edu	\N	Claiborn Gerardet	\N
35	\N	cpattissony@buzzfeed.com	251 Boyd Terrace	Clea Pattisson	\N
36	\N	hcoldhamz@xrea.com	\N	Haven Coldham	+33 (756) 537-1571
37	\N	amease10@cam.ac.uk	1 Toban Hill	Arliene Mease	\N
38	\N	bhulbert11@pinterest.com	734 Continental Road	Burl Hulbert	+86 (105) 340-7287
39	Baumbach-Lakin	ehayers12@howstuffworks.com	720 Summerview Street	Erroll Hayers	+63 (929) 744-9911
40	Willms-Hermann	kpregal13@shinystat.com	7 Sundown Street	Kirsten Pregal	+30 (644) 577-7964
41	MacGyver-Herzog	bmattheis14@amazonaws.com	78 Killdeer Avenue	Bettye Mattheis	+62 (185) 353-7851
42	Volkman-O'Connell	crussilll15@biglobe.ne.jp	\N	Cole Russilll	\N
43	Kozey and Sons	eingreda16@webnode.com	\N	Ernesta Ingreda	+1 (214) 867-0723
44	\N	dseiler17@about.me	\N	Davon Seiler	+60 (456) 751-7406
45	\N	gmacia18@utexas.edu	0801 Morning Center	Gert Macia	+63 (218) 347-8023
46	Rolfson-Raynor	kshovelbottom19@squidoo.com	8 Memorial Hill	Keven Shovelbottom	+380 (973) 986-0711
47	Armstrong-Kirlin	talexis1a@godaddy.com	894 Karstens Place	Tersina Alexis	+1 (952) 256-5081
48	Ferry, Sauer and Maggio	ehodcroft1b@google.cn	2 Jenifer Way	Eolanda Hodcroft	\N
49	\N	wshillington1c@narod.ru	216 Kenwood Parkway	Westleigh Shillington	+86 (534) 453-5275
50	\N	ahalloway1d@google.nl	\N	Alethea Halloway	\N
51	Schmeler, Balistreri and Keebler	bspeed1e@wikimedia.org	\N	Bobbee Speed	+1 (449) 350-8188
52	Hessel-Cummerata	nrosenzwig1f@cpanel.net	54 Declaration Road	Natasha Rosenzwig	+60 (413) 895-8485
53	MacGyver-Bailey	lhardstaff1g@home.pl	\N	Linnet Hardstaff	\N
54	Kilback-Quitzon	cbarnwille1h@engadget.com	3553 Jay Street	Christoffer Barnwille	+57 (764) 647-5997
55	Effertz, Rodriguez and Muller	rtume1i@aboutads.info	055 Sunbrook Alley	Rabbi Tume	+54 (801) 531-5136
56	Koch-D'Amore	qbrimming1j@slate.com	56024 Dexter Circle	Quentin Brimming	+961 (392) 750-6109
57	\N	amoxsom1k@people.com.cn	0004 Loomis Parkway	Aeriela Moxsom	\N
58	\N	cgiggie1l@google.co.uk	4 East Center	Chancey Giggie	+968 (905) 721-1081
59	Pfannerstill, Hudson and Collins	gharrowell1m@ehow.com	\N	Gardie Harrowell	+86 (516) 405-7589
60	Nikolaus-Ritchie	kgadney1n@netvibes.com	328 Mcguire Street	Kerstin Gadney	\N
61	Rohan Group	mpolin1o@meetup.com	105 Hansons Point	Marchall Polin	+55 (647) 828-3686
62	Jaskolski Inc	cmion1p@google.ca	5 Kensington Trail	Carolann Mion	+7 (155) 318-7181
63	\N	rbeckers1q@webmd.com	1 Hudson Park	Rasla Beckers	\N
64	\N	greisenberg1r@indiatimes.com	\N	Glennis Reisenberg	\N
65	Lebsack Inc	wdearlove1s@si.edu	\N	Wilhelmine Dearlove	\N
66	\N	sblankhorn1t@wikia.com	\N	Stanford Blankhorn	\N
67	Rippin, Schulist and Mayer	nsherland1u@yahoo.com	7742 Logan Point	Nerita Sherland	\N
68	Sanford-Batz	lbelvard1v@friendfeed.com	492 Manufacturers Pass	Laurella Belvard	+62 (758) 443-5015
69	\N	bmaxstead1w@ifeng.com	\N	Brodie Maxstead	+84 (694) 252-5625
70	\N	nclassen1x@usa.gov	\N	Norri Classen	+62 (920) 322-5242
71	Fahey, Casper and Crooks	cgaukrodge1y@dell.com	\N	Cacilia Gaukrodge	\N
72	\N	scaldero1z@columbia.edu	\N	Stan Caldero	+998 (364) 965-2065
73	\N	geckford20@ask.com	\N	Gilles Eckford	\N
74	\N	hgiraudou21@bigcartel.com	11929 Aberg Way	Hamish Giraudou	\N
75	\N	srettie22@yahoo.co.jp	\N	Saidee Rettie	+63 (448) 248-3759
76	\N	rstarton23@behance.net	8481 4th Place	Rosalinde Starton	\N
77	Baumbach, Williamson and Sawayn	gdimmne24@wikipedia.org	\N	Giovanni Dimmne	+355 (178) 645-8989
78	\N	dhollingshead25@mac.com	85068 Tennessee Alley	Danie Hollingshead	+60 (528) 513-5626
79	Kling, Mertz and Trantow	gscargle26@weather.com	\N	Gregoire Scargle	+30 (521) 681-7948
80	\N	tsyers27@over-blog.com	\N	Tisha Syers	+86 (213) 295-7043
81	Schoen-Ruecker	slawless28@seattletimes.com	7 Miller Lane	Sigismondo Lawless	\N
82	Miller, Prohaska and Hammes	whorrell29@cargocollective.com	\N	Wilmer Horrell	\N
83	Schimmel LLC	gkeymar2a@miibeian.gov.cn	6988 Waubesa Drive	Gage Keymar	+55 (759) 110-8735
84	\N	ghothersall2b@umn.edu	\N	Georgiana Hothersall	+1 (202) 296-2599
85	Schiller-Aufderhar	idalyell2c@storify.com	041 Fulton Avenue	Ingrid Dalyell	+62 (943) 893-4908
86	Hamill, Hegmann and Heathcote	adibb2d@wikipedia.org	\N	Adelaida Dibb	\N
87	\N	hmordin2e@clickbank.net	4300 Sauthoff Court	Hillard Mordin	+57 (582) 558-2637
88	\N	wo2f@umn.edu	78371 South Park	Welbie O' Borne	\N
89	Dietrich Inc	tbelch2g@scribd.com	633 Mandrake Court	Thaine Belch	+63 (102) 685-0825
90	Kassulke-Nicolas	amassel2h@flickr.com	871 Washington Drive	Artemis Massel	\N
91	\N	cruske2i@infoseek.co.jp	\N	Crista Ruske	+98 (756) 279-7491
92	\N	rgodbehere2j@hostgator.com	46 Beilfuss Parkway	Ronnie Godbehere	+62 (947) 658-3038
93	\N	rdugue2k@epa.gov	\N	Rad Dugue	+420 (667) 522-6571
94	\N	bbennion2l@wiley.com	662 David Point	Bogey Bennion	+86 (888) 468-5626
95	Feest-O'Keefe	gcogger2m@skype.com	\N	Glen Cogger	+98 (797) 168-9510
96	Bechtelar Inc	kmenilove2n@apache.org	5 Mifflin Junction	Kellby Menilove	\N
97	Shields LLC	irumbellow2o@exblog.jp	9 Miller Road	Issi Rumbellow	+86 (929) 669-6783
98	\N	atreadgold2p@state.tx.us	3 Redwing Alley	Audy Treadgold	+86 (633) 302-7516
99	\N	akulis2q@mediafire.com	68775 Bellgrove Lane	Arlie Kulis	\N
100	Lakin-Corwin	ypobjoy2r@wunderground.com	\N	Yves Pobjoy	+86 (754) 670-0789
\.


--
-- Data for Name: office; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.office (id, owner_id, building_type_id, building_condition_id, address, floor, total_surface, monthly_rent, has_parking_lot, has_air_conditioning, has_coffee_machine, has_wc, has_security) FROM stdin;
1	1	2	1	55 Russell Crossing	14	144	68919	t	f	f	t	t
2	2	1	3	78 Vera Avenue	16	77	14592	t	f	f	t	f
3	3	3	1	6 Red Cloud Terrace	11	102	88520	f	f	f	f	f
4	4	6	4	300 Sunfield Drive	18	67	69872	t	t	t	f	t
5	5	1	4	1 Cascade Park	20	78	97177	t	f	t	f	t
6	6	2	2	53 Jackson Way	11	167	50034	f	t	t	t	f
7	7	4	3	19 Messerschmidt Point	16	201	46342	f	t	t	t	t
8	8	4	3	376 Hazelcrest Junction	7	173	47221	f	f	t	f	t
9	9	6	2	9 Eliot Pass	18	88	82958	t	t	f	t	f
10	10	1	1	0422 Stang Lane	20	82	5659	f	f	t	t	f
11	11	1	1	93849 Hovde Way	15	96	33583	t	f	t	t	t
12	12	1	1	39 High Crossing Way	12	186	38860	f	t	t	f	f
13	13	1	3	84 Gateway Lane	6	197	34413	f	f	f	t	f
14	14	1	3	7 Buhler Court	11	289	85936	f	f	t	t	t
15	15	2	1	12 Crownhardt Center	1	268	31653	t	t	t	t	t
16	16	2	1	168 Coolidge Avenue	12	84	79127	f	f	t	t	f
17	17	2	1	334 Oak Crossing	7	48	63554	t	t	f	f	f
18	18	3	3	14 Spohn Way	12	49	6094	f	f	t	t	t
19	19	3	3	553 Fordem Street	7	171	80630	f	t	f	t	f
20	20	1	3	5808 Forest Dale Hill	13	110	20058	t	t	t	t	f
21	21	6	4	16416 Magdeline Avenue	8	282	39004	f	t	f	f	f
22	22	6	2	72809 Crownhardt Plaza	12	154	7719	t	t	t	t	f
23	23	4	2	4 Di Loreto Drive	15	29	99991	f	t	f	t	f
24	24	4	2	965 Kipling Road	8	11	35646	t	t	f	f	t
25	25	2	1	05 Thierer Avenue	11	152	15791	t	t	t	f	f
26	26	2	1	0 Butterfield Plaza	16	39	34735	t	t	t	t	t
27	27	1	1	05 Sullivan Point	12	95	28844	t	t	t	f	t
28	28	1	1	306 Mesta Street	13	278	72095	t	t	f	f	t
29	29	1	1	02436 Atwood Point	1	151	50037	t	f	f	f	t
30	30	1	4	283 Mandrake Road	18	77	25383	t	t	f	t	t
31	31	1	3	7210 Ramsey Avenue	19	93	42455	t	f	f	t	f
32	32	2	1	484 American Ash Court	12	168	38811	t	f	f	f	t
33	33	2	3	223 Rockefeller Terrace	11	185	63738	f	f	t	t	f
34	34	2	1	546 Mayfield Hill	5	211	89557	t	f	f	t	f
35	35	4	1	03935 Dexter Parkway	20	93	71821	f	t	f	t	f
36	1	3	2	20 Cooper Square	14	2	16000	t	t	f	t	f
\.


--
-- Data for Name: rent; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rent (id, office_id, client_id, start_date, end_date, termination_date, termination_reason_id) FROM stdin;
1	9	92	2023-12-14	2024-01-14	2024-01-14	1
2	34	24	2022-02-12	2022-12-12	2021-11-24	8
3	8	61	2022-01-19	2022-08-19	2022-05-04	7
4	9	98	2023-01-01	2023-11-01	2023-11-01	1
5	15	11	2023-05-10	2023-06-10	2023-06-10	1
6	32	92	2022-02-03	2022-06-03	2022-06-03	1
7	34	58	2023-04-09	2023-09-09	2023-09-09	1
8	8	1	2023-06-01	2024-10-01	\N	\N
9	7	80	2023-08-27	2024-02-27	2023-10-06	3
10	10	17	2023-03-25	2023-09-25	2023-04-07	5
11	23	100	2024-02-08	2024-09-08	\N	\N
12	22	82	2023-01-28	2023-02-28	2023-02-28	1
13	31	44	2022-10-07	2023-02-07	2022-11-09	6
14	5	53	2023-07-18	2024-07-18	\N	\N
15	32	26	2023-03-16	2024-06-20	\N	\N
16	24	64	2023-05-26	2023-06-26	2023-06-26	1
17	1	86	2023-10-14	2024-08-14	\N	\N
18	18	59	2023-05-20	2023-09-20	2023-08-24	2
19	23	69	2022-12-28	2023-10-28	2023-01-21	8
20	27	19	2023-05-31	2023-12-31	2023-07-27	4
21	2	64	2023-07-26	2023-09-26	2023-08-10	5
\.


--
-- Data for Name: termination_reason; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.termination_reason (id, name) FROM stdin;
1	Окончание срока аренды
2	Досрочное расторжение договора по соглашению сторон
3	Отказ в одностороннем порядке до истечения срока аренды
4	Отсутствие внесения арендной платы арендатором более двух раз подряд
5	Нарушение условий договора или ухудшение имущества арендатором
6	Нарушение условий договора владельцем
7	Стоимость аренды изменена в одностороннем порядке
8	Состояние арендуемого объекта не соответствует описанному владельцем
\.


--
-- Name: building_condition_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.building_condition_id_seq', 4, true);


--
-- Name: building_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.building_type_id_seq', 6, true);


--
-- Name: client_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.client_id_seq', 100, true);


--
-- Name: office_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.office_id_seq', 36, true);


--
-- Name: rent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rent_id_seq', 21, true);


--
-- Name: termination_reason_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.termination_reason_id_seq', 8, true);


--
-- Name: building_condition building_condition_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.building_condition
    ADD CONSTRAINT building_condition_pkey PRIMARY KEY (id);


--
-- Name: building_type building_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.building_type
    ADD CONSTRAINT building_type_pkey PRIMARY KEY (id);


--
-- Name: client client_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_pkey PRIMARY KEY (id);


--
-- Name: office office_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.office
    ADD CONSTRAINT office_pkey PRIMARY KEY (id);


--
-- Name: rent rent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rent
    ADD CONSTRAINT rent_pkey PRIMARY KEY (id);


--
-- Name: termination_reason termination_reason_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.termination_reason
    ADD CONSTRAINT termination_reason_pkey PRIMARY KEY (id);


--
-- Name: office office_building_condition_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.office
    ADD CONSTRAINT office_building_condition_id_fkey FOREIGN KEY (building_condition_id) REFERENCES public.building_condition(id);


--
-- Name: office office_building_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.office
    ADD CONSTRAINT office_building_type_id_fkey FOREIGN KEY (building_type_id) REFERENCES public.building_type(id);


--
-- Name: office office_owner_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.office
    ADD CONSTRAINT office_owner_id_fkey FOREIGN KEY (owner_id) REFERENCES public.client(id) ON DELETE SET NULL;


--
-- Name: rent rent_client_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rent
    ADD CONSTRAINT rent_client_id_fkey FOREIGN KEY (client_id) REFERENCES public.client(id) ON DELETE SET NULL;


--
-- Name: rent rent_office_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rent
    ADD CONSTRAINT rent_office_id_fkey FOREIGN KEY (office_id) REFERENCES public.office(id);


--
-- Name: rent rent_termination_reason_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rent
    ADD CONSTRAINT rent_termination_reason_id_fkey FOREIGN KEY (termination_reason_id) REFERENCES public.termination_reason(id);


--
-- PostgreSQL database dump complete
--

