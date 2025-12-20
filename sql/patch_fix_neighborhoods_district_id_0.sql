-- patch_fix_neighborhoods_district_id_0.sql
-- Purpose: Fix neighborhoods that have district_id=0 by assigning them to the city's MERKEZ district (if it exists).
-- Safe to re-run.

START TRANSACTION;

UPDATE neighborhoods n
JOIN districts d
  ON d.city_id = n.city_id
 AND UPPER(d.name) = 'MERKEZ'
SET n.district_id = d.id
WHERE n.district_id = 0;

COMMIT;
