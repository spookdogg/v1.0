-- uncomment the DROP lines whenever you want to do a Fresh Install
-- and not have to DROP the existing tables and sequences manually
-- NOTE: I'd put in an IF EXISTS, but that only works on PostgreSQL 8.2+

-- DROP TABLE "attachment" CASCADE ;
-- DROP SEQUENCE "attachment_id_seq" CASCADE ;

CREATE SEQUENCE "attachment_id_seq" ;

CREATE TABLE  "attachment" (
   "id" integer DEFAULT nextval('"attachment_id_seq"') NOT NULL,
   "filename"   varchar(255) default NULL,
   "filedata"   bytea,
   "viewcount" int CHECK ("viewcount" >= 0) default NULL,
   "parent" int CHECK ("parent" >= 0) default NULL,
   primary key ("id")
);
CREATE INDEX "attachment_parent_idx" ON "attachment" USING btree ("parent");

-- DROP TABLE "avatar" CASCADE ;
CREATE TABLE  "avatar" (
   "id" int CHECK ("id" >= 0) NOT NULL default '0',
   "filename"   varchar(255) default NULL,
   "datum"   bytea,
   "datetime" int CHECK ("datetime" >= 0) NOT NULL default '0',
   "datatype"  smallint CHECK ("datatype" >= 0) NOT NULL default '0',
   primary key ("id")
);

-- DROP TABLE "board" CASCADE ;
-- DROP SEQUENCE "board_id_seq" CASCADE ;

CREATE SEQUENCE "board_id_seq" ;

CREATE TABLE  "board" (
   "id" integer DEFAULT nextval('"board_id_seq"') NOT NULL,
   "disporder" smallint CHECK ("disporder" >= 0) NOT NULL default '0',
   "name"   varchar(255) default NULL,
   "description"   varchar(255) default NULL,
   "displaydepth"  smallint CHECK ("displaydepth" >= 0) NOT NULL default '0',
   "parent" smallint CHECK ("parent" >= 0) default NULL,
   "postcount" int CHECK ("postcount" >= 0) NOT NULL default '0',
   "threadcount" int CHECK ("threadcount" >= 0) NOT NULL default '0',
   "lpost" int CHECK ("lpost" >= 0) default NULL,
   "lposter" int CHECK ("lposter" >= 0) default NULL,
   "lthread" int CHECK ("lthread" >= 0) default NULL,
   "lthreadpcount" int CHECK ("lthreadpcount" >= 0) default NULL,
   primary key ("id")
);

INSERT INTO "board` (`id`", "`disporder`", "`name`", "`description`)" VALUES (1, 1, E'Main Category', E'Main category description');
INSERT INTO "board` (`id`", "`disporder`", "`name`", "`description`", "`displaydepth`", "`parent`)" VALUES (2, 1, E'Main Forum', E'Main forum description', 1, 1);
CREATE INDEX "board_displaydepth_idx" ON "board" USING btree ("displaydepth");
CREATE INDEX "board_disporder_idx" ON "board" USING btree ("disporder");
CREATE INDEX "board_parent_idx" ON "board" USING btree ("parent");

-- DROP TABLE "configuration" CASCADE ;
CREATE TABLE  "configuration" (
   "name"   varchar(255) NOT NULL default '',
   "content"   text NOT NULL,
   primary key ("name")
);

-- DROP TABLE "event" CASCADE ;
-- DROP SEQUENCE "event_id_seq" CASCADE ;

CREATE SEQUENCE "event_id_seq" ;

CREATE TABLE  "event" (
   "id" integer DEFAULT nextval('"event_id_seq"') NOT NULL,
   "author" int CHECK ("author" >= 0) default NULL,
   "startdate"   date default NULL,
   "title"   varchar(255) default NULL,
   "body"   text,
   "private"  smallint CHECK ("private" >= 0) default NULL,
   "dsmilies"  smallint CHECK ("dsmilies" >= 0) default NULL,
   "ipaddress"   int default NULL,
   primary key ("id")
);

-- DROP TABLE "citizen" CASCADE ;
-- DROP SEQUENCE "citizen_id_seq" CASCADE ;

CREATE SEQUENCE "citizen_id_seq" ;

CREATE TABLE  "citizen" (
   "id" integer DEFAULT nextval('"citizen_id_seq"') NOT NULL,
   "username"   varchar(255) NOT NULL,
   "passphrase"   varchar(255) NOT NULL,
   "email"   varchar(255) NOT NULL,
   "datejoined"   date NOT NULL,
   "website"   varchar(255) default NULL,
   "aim"   varchar(255) default NULL,
   "icq"   varchar(255) default NULL,
   "msn"   varchar(255) default NULL,
   "yahoo"   varchar(255) default NULL,
   "referrer"   varchar(255) default NULL,
   "birthday"   varchar(255) default NULL,
   "bio"   varchar(255) default NULL,
   "residence"   varchar(255) default NULL,
   "interests"   varchar(255) default NULL,
   "occupation"   varchar(255) default NULL,
   "avatarid"   int default NULL,
   "signature"   varchar(255) default NULL,
   "allowmail"  smallint CHECK ("allowmail" >= 0) default '1',
   "invisible"  smallint CHECK ("invisible" >= 0) default '0',
   "publicemail"  smallint CHECK ("publicemail" >= 0) default '0',
   "enablepms"  smallint CHECK ("enablepms" >= 0) default '1',
   "pmnotifya"  smallint CHECK ("pmnotifya" >= 0) default '1',
   "pmnotifyb"  smallint CHECK ("pmnotifyb" >= 0) default '1',
   "rejectpms"  smallint CHECK ("rejectpms" >= 0) default '1',
   "threadview" smallint CHECK ("threadview" >= 0) default '0',
   "postsperpage"  smallint CHECK ("postsperpage" >= 0) default '0',
   "threadsperpage"  smallint CHECK ("threadsperpage" >= 0) default '0',
   "weekstart"  smallint CHECK ("weekstart" >= 0) default '0',
   "timeoffset"    integer default '0',
   "title"   varchar(255) default NULL,
   "lastactive"   int default NULL,
   "postcount" int CHECK ("postcount" >= 0) NOT NULL default '0',
   "lastlocation"   varchar(255) default NULL,
   "ipaddress"   int default NULL,
   "dst"  smallint CHECK ("dst" >= 0) default '0',
   "dstoffset" smallint CHECK ("dstoffset" >= 0) default '0',
   "showsigs"  smallint CHECK ("showsigs" >= 0) default '1',
   "showavatars"  smallint CHECK ("showavatars" >= 0) default '1',
   "autologin"  smallint CHECK ("autologin" >= 0) default '0',
   "buddylist"   text,
   "ignorelist"   text,
   "pmfolders"   text,
   "usergroup"  smallint CHECK ("usergroup" >= 0) default '0',
   "loggedin"    smallint default '0',
   "lastrequest"   text,
   "reghash"   char(32) default NULL,
   primary key ("id")
);
CREATE INDEX "citizen_lastactive_idx" ON "citizen" USING btree ("lastactive");
CREATE INDEX "citizen_loggedin_idx" ON "citizen" USING btree ("loggedin");
CREATE INDEX "citizen_reghash_idx" ON "citizen" USING btree ("reghash");

-- DROP TABLE "pm" CASCADE ;
-- DROP SEQUENCE "pm_id_seq" CASCADE ;

CREATE SEQUENCE "pm_id_seq" ;

CREATE TABLE  "pm" (
   "id" integer DEFAULT nextval('"pm_id_seq"') NOT NULL,
   "ownerid" int CHECK ("ownerid" >= 0) default NULL,
   "author" int CHECK ("author" >= 0) default NULL,
   "recipient" int CHECK ("recipient" >= 0) default NULL,
   "subject"   varchar(255) default NULL,
   "body"   text,
   "parent" smallint CHECK ("parent" >= 0) default NULL,
   "ipaddress"   int default NULL,
   "icon"  smallint CHECK ("icon" >= 0) default NULL,
   "dsmilies"  smallint CHECK ("dsmilies" >= 0) default NULL,
   "beenread"  smallint CHECK ("beenread" >= 0) default NULL,
   "readtime" int CHECK ("readtime" >= 0) default NULL,
   "tracking"  smallint CHECK ("tracking" >= 0) default NULL,
   "replied"  smallint CHECK ("replied" >= 0) default NULL,
   "datetime"   int default NULL,
   primary key ("id")
);
CREATE INDEX "pm_ownerid_idx" ON "pm" USING btree ("ownerid");

-- DROP TABLE "poll" CASCADE ;
CREATE TABLE  "poll" (
   "id" int CHECK ("id" >= 0) NOT NULL default '0',
   "datetime"   int default NULL,
   "question"   varchar(255) default NULL,
   "answers"   text,
   "multiplechoices"  smallint CHECK ("multiplechoices" >= 0) default NULL,
   "timeout" smallint CHECK ("timeout" >= 0) default NULL,
   primary key ("id")
);

-- DROP TABLE "pollvote" CASCADE ;
-- DROP SEQUENCE "pollvote_id_seq" CASCADE ;

CREATE SEQUENCE "pollvote_id_seq" ;

CREATE TABLE  "pollvote" (
   "id" integer DEFAULT nextval('"pollvote_id_seq"') NOT NULL,
   "parent" int CHECK ("parent" >= 0) default NULL,
   "ownerid" int CHECK ("ownerid" >= 0) default NULL,
   "vote" int CHECK ("vote" >= 0) default NULL,
   "votedate" int CHECK ("votedate" >= 0) default NULL,
   primary key ("id")
);

-- DROP TABLE "post" CASCADE ;
-- DROP SEQUENCE "post_id_seq" CASCADE ;

CREATE SEQUENCE "post_id_seq" ;

CREATE TABLE  "post" (
   "id" integer DEFAULT nextval('"post_id_seq"') NOT NULL,
   "author" int CHECK ("author" >= 0) default NULL,
   "datetime_posted"   int default NULL,
   "datetime_edited"   int default NULL,
   "title"   varchar(255) default NULL,
   "body"   text,
   "parent" int CHECK ("parent" >= 0) default NULL,
   "ipaddress"   int default NULL,
   "icon"  smallint CHECK ("icon" >= 0) default NULL,
   "dsmilies"  smallint CHECK ("dsmilies" >= 0) default NULL,
   primary key ("id")
);
CREATE INDEX "post_datetime_posted_idx" ON "post" USING btree ("datetime_posted");
CREATE INDEX "post_author_idx" ON "post" USING btree ("author");
CREATE INDEX "post_parent_idx" ON "post" USING btree ("parent");

-- DROP TABLE "request" CASCADE ;
-- DROP SEQUENCE "request_id_seq" CASCADE ;

CREATE SEQUENCE "request_id_seq" ;

CREATE TABLE  "request" (
   "id" integer DEFAULT nextval('"request_id_seq"') NOT NULL,
   "rkey" int CHECK ("rkey" >= 0) default NULL,
   "rtimestamp"   int default NULL,
   primary key ("id")
);

-- DROP TABLE "searchindex" CASCADE ;
CREATE TABLE  "searchindex" (
   "postid" int CHECK ("postid" >= 0) NOT NULL default '0',
   "wordid" int CHECK ("wordid" >= 0) NOT NULL default '0',
   "intitle"  smallint CHECK ("intitle" >= 0) NOT NULL default '0'
);

-- DROP TABLE "searchresult" CASCADE ;
-- DROP SEQUENCE "searchresult_id_seq" CASCADE ;

CREATE SEQUENCE "searchresult_id_seq" ;

CREATE TABLE  "searchresult" (
   "id" integer DEFAULT nextval('"searchresult_id_seq"') NOT NULL,
   "author" int CHECK ("author" >= 0) NOT NULL default '0',
   "ipaddress"   int default NULL,
   "searchtime" int CHECK ("searchtime" >= 0) NOT NULL default '0',
   "querystring"   varchar(255) NOT NULL default '',
   "results"   text NOT NULL,
   "sortinfo"   varchar(255) NOT NULL default '',
   "showposts"  smallint CHECK ("showposts" >= 0) NOT NULL default '0',
   primary key ("id")
);

-- DROP TABLE "searchword" CASCADE ;
-- DROP SEQUENCE "searchword_wordid_seq" CASCADE ;

CREATE SEQUENCE "searchword_wordid_seq" ;

CREATE TABLE  "searchword" (
   "wordid" integer DEFAULT nextval('"searchword_wordid_seq"') NOT NULL,
   "word"   varchar(255) NOT NULL default '',
   primary key ("wordid")
);

-- DROP TABLE "guest" CASCADE ;
CREATE TABLE  "guest" (
   "id"   varchar(32) NOT NULL default '',
   "lastactive"   int default NULL,
   "lastlocation"   varchar(255) default NULL,
   "ipaddress"   int default NULL,
   "lastrequest"   text,
   primary key ("id")
);
CREATE INDEX "guest_lastactive_idx" ON "guest" USING btree ("lastactive");

-- DROP TABLE "stats" CASCADE ;
CREATE TABLE  "stats" (
   "name"   varchar(255) NOT NULL default '0',
   "content" int CHECK ("content" >= 0) default NULL,
   primary key ("name")
);

INSERT INTO "stats" ("name", "content") VALUES ('membercount', 0);
INSERT INTO "stats" ("name", "content") VALUES ('newestmember', NULL);
INSERT INTO "stats" ("name", "content") VALUES ('threadcount', 0);
INSERT INTO "stats" ("name", "content") VALUES ('postcount', 0);
INSERT INTO "stats" ("name", "content") VALUES ('mostuserscount', 0);
INSERT INTO "stats" ("name", "content") VALUES ('mostusersdate', NULL);

-- DROP TABLE "thread" CASCADE ;
-- DROP SEQUENCE "thread_id_seq" CASCADE ;

CREATE SEQUENCE "thread_id_seq" ;

CREATE TABLE  "thread" (
   "id" integer DEFAULT nextval('"thread_id_seq"') NOT NULL,
   "title"   varchar(255) default NULL,
   "description"   varchar(255) default NULL,
   "parent" smallint CHECK ("parent" >= 0) default NULL,
   "viewcount" int CHECK ("viewcount" >= 0) NOT NULL default '0',
   "postcount" int CHECK ("postcount" >= 0) NOT NULL default '0',
   "attachcount" int CHECK ("attachcount" >= 0) NOT NULL default '0',
   "lpost" int CHECK ("lpost" >= 0) NOT NULL default '0',
   "lposter" int CHECK ("lposter" >= 0) default NULL,
   "icon"  smallint CHECK ("icon" >= 0) default NULL,
   "author" int CHECK ("author" >= 0) default NULL,
   "closed"  smallint CHECK ("closed" >= 0) default NULL,
   "visible"  smallint CHECK ("visible" >= 0) default NULL,
   "sticky"  smallint CHECK ("sticky" >= 0) default NULL,
   "notes"   text,
   "poll"  smallint CHECK ("poll" >= 0) default NULL,
   primary key ("id")
);CREATE INDEX "thread_parent_idx" ON "thread" USING btree ("parent");
CREATE INDEX "thread_visible_idx" ON "thread" USING btree ("visible");
