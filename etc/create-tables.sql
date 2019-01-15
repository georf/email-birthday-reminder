CREATE TABLE public.birthday_logs (
    id serial NOT NULL,
    ip character varying(200) NOT NULL,
    type character varying(200) NOT NULL,
    "table" character varying(200) NOT NULL,
    table_id integer NOT NULL,
    set text NOT NULL,
    logged timestamp without time zone NOT NULL
);
ALTER TABLE public.birthday_logs OWNER TO birthdays;
CREATE SEQUENCE public.birthday_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE public.birthday_logs_id_seq OWNER TO birthdays;
ALTER SEQUENCE public.birthday_logs_id_seq OWNED BY public.birthday_logs.id;

CREATE TABLE public.birthdays (
    id serial NOT NULL,
    name character varying(200) NOT NULL,
    hint character varying(200) NOT NULL,
    date date NOT NULL
);
ALTER TABLE public.birthdays OWNER TO birthdays;
CREATE SEQUENCE public.birthdays_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE public.birthdays_id_seq OWNER TO birthdays;
ALTER SEQUENCE public.birthdays_id_seq OWNED BY public.birthdays.id;
