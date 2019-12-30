-- Adminer 4.6.3 PostgreSQL dump

CREATE EXTENSION pgcrypto;

DROP TABLE IF EXISTS "change_logs";

CREATE TABLE "public"."change_logs" (
    "log_id" UUID PRIMARY KEY DEFAULT gen_random_uuid() NOT NULL,
    "created" integer NOT NULL,
    "created_by" character varying(32) NOT NULL,
    "controller" character varying(64) NOT NULL,
    "action" character varying(32) NOT NULL,
    "querystring" text,
    "post" text,
    "url" text NOT NULL,
    "ip" character varying(45) NOT NULL,
    "current_data" text,
    "new_data" text,
    "db_query" text,
    "browser" text,
    "method" character varying(15)
);

DROP TABLE IF EXISTS "user_group";

CREATE TABLE "public"."user_group" (
    "user_group_id" UUID PRIMARY KEY DEFAULT gen_random_uuid() NOT NULL,
    "created" integer NOT NULL,
    "updated" integer NOT NULL,
    "created_by" character varying(32) NOT NULL,
    "updated_by" character varying(32) NOT NULL,
    "user_group_name" character varying(150) NOT NULL,
    "user_group_role" text,
    "user_group_default" smallint NOT NULL,
    "user_group_status" smallint NOT NULL
);

INSERT INTO "user_group" ("user_group_id", "created", "updated", "created_by", "updated_by", "user_group_name", "user_group_role", "user_group_default", "user_group_status") VALUES
('0d60a85e-0b90-4482-a14c-108aea2557aa',	1530884439,	1577710769,	'imamkusniadi',	'unix666',	'Super Admin',	'{"dashboard":{"index":"1"},"change_logs":{"index":"1"},"detect_plastic":{"index":"1"},"apidocs":{"index":"1"},"detection_logs":{"index":"1","detail":"1","delete":"1"},"user_group":{"index":"1","add":"1","update":"1"},"user":{"index":"1","add":"1","update":"1","change_password":"1","authenticate":"1","terminate":"1"}}',	2,	1),
('63279342-9a20-435d-a722-9f1b9f6fce84',	1563445924,	1577710780,	'imamkusniadi',	'unix666',	'Reguler',	'{"dashboard":{"index":"1"},"change_logs":{"index":"1"},"detect_plastic":{"index":"1"},"apidocs":{"index":"1"},"detection_logs":{"index":"1","detail":"1"},"user":{"change_password":"1"}}',	1,	1);

DROP TABLE IF EXISTS "users";

CREATE TABLE "public"."users" (
    "user_id" UUID PRIMARY KEY DEFAULT gen_random_uuid() NOT NULL,
    "created" integer NOT NULL,
    "updated" integer NOT NULL,
    "created_by" character varying(32) NOT NULL,
    "updated_by" character varying(32) NOT NULL,
    "user_name" character varying(150) NOT NULL,
    "user_login" character varying(32) NOT NULL,
    "user_email" character varying(150),
    "user_password" character varying(40) NOT NULL,
    "user_balance" integer,
    "user_last_login" integer,
    "user_last_ip" character varying(45),
    "user_last_browser" text,
    "user_group_id" UUID NOT NULL,
    "user_secret" character varying(45),
    "user_status" smallint NOT NULL,
    "api_access_only" smallint NOT NULL,
    "forgot_password_token" character varying(45),
    CONSTRAINT "users_user_login_key" UNIQUE ("user_login")
);

INSERT INTO "users" ("user_id", "created", "updated", "created_by", "updated_by", "user_name", "user_login", "user_email", "user_password", "user_balance", "user_last_login", "user_last_ip", "user_last_browser", "user_group_id", "user_secret", "user_status", "api_access_only", "forgot_password_token") VALUES
('39240e9f-ae09-4e95-9fd0-a712035c8ad7',	1530809061,	1547092252,	'admin',	'admin',	'Imam Kusniadi',	'unix666',	'imam@unix666.ru',	'7c4a8d09ca3762af61e59520943dc26494f8941b', NULL,	1552802663,	'127.0.0.1',	'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',    '0d60a85e-0b90-4482-a14c-108aea2557aa', NULL,	1,  2,  NULL);

DROP TABLE IF EXISTS "checking_logs";

CREATE TABLE "public"."checking_logs" (
    "checking_log_id" UUID PRIMARY KEY DEFAULT gen_random_uuid() NOT NULL,
    "created" integer NOT NULL,
    "updated" integer NOT NULL,
    "created_by" character varying(32) NOT NULL,
    "updated_by" character varying(32) NOT NULL,
    "user_id" UUID NOT NULL,
    "plastic_type" character varying(150) NOT NULL,
    "plastic_percentage" character varying(32) NOT NULL,
    "plastic_file" text NOT NULL,
    "time_used" float NOT NULL,
    "request_id" UUID NOT NULL,
    "request_data" text NOT NULL,
    "plastic_status" smallint NOT NULL
);

DROP TABLE IF EXISTS "settings";

CREATE TABLE "public"."settings" (
    "created_by" character varying(32) NOT NULL,
    "updated_by" character varying(32) NOT NULL,
    "created" integer NOT NULL,
    "updated" integer NOT NULL,
    "telegram_api" character varying(45) NOT NULL,
    "telegram_chatid" character varying(15) NOT NULL,
    "notification_detail" text NOT NULL,
    "notification_status" smallint NOT NULL
);

INSERT INTO settings VALUES
('admin', 'admin', '1568270819', '1568270819', '500835998:AAHzPmJ7Y3b7SBybesfd4gWjwgDCR7npCd8', '212541915', 'Alertt! Suspect detected.', 1);