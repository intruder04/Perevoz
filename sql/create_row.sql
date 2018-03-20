ALTER TABLE perevoz_order ADD COLUMN tariff text;
ALTER TABLE perevoz_order ADD COLUMN sms_driver_id text;
ALTER TABLE perevoz_user ADD COLUMN sb_user character varying(100);
ALTER TABLE perevoz_order ADD COLUMN sb_user character varying(100);
ALTER TABLE perevoz_order ADD COLUMN sb_srok integer;

ALTER TABLE perevoz_user ADD COLUMN sb_seeall integer;
UPDATE perevoz_user SET sb_seeall = 0;
ALTER TABLE perevoz_user ALTER COLUMN sb_seeall SET NOT NULL;
ALTER TABLE perevoz_user ALTER COLUMN sb_seeall SET DEFAULT 0;