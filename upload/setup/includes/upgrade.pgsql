ALTER TABLE "citizen" ADD COLUMN "rejectpms" smallint CHECK ("rejectpms" >= 0) default '1';