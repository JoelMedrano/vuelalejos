ALTER TABLE purchases 
  MODIFY COLUMN date_updated_purchase TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ;


ALTER TABLE purchases
  MODIFY COLUMN date_updated_purchase DATETIME NOT NULL DEFAULT (UTC_TIMESTAMP) ON UPDATE UTC_TIMESTAMP ;

SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'providers' AND table_schema='credifarma';

SELECT * FROM therapies;
UPDATE therapies SET date_created_therapy='2022-11-01';

SELECT * FROM laboratories;
UPDATE laboratories SET date_created_laboratory='2022-11-01';

SELECT id_substance,code_substance,name_substance FROM substances;
UPDATE substances SET date_created_substance='2022-11-01';

SELECT * FROM categories;

SELECT * FROM articles; WHERE id_article='44867';
UPDATE articles SET date_created_article ='2022-11-02';

SELECT * FROM artscoms;
UPDATE artscoms SET date_created_artcom ='2022-11-02';

SELECT @@global.time_zone, @@session.time_zone;
SET @@session.time_zone='-05:00';

SELECT * FROM providers;
UPDATE providers SET date_created_provider ='2022-11-05';

SELECT NOW();

SELECT CURRENT_TIMESTAMP();

CREATE TABLE `blah` (
    id INT NOT NULL AUTO_INCREMENT,
    creation_time TIMESTAMP NOT NULL DEFAULT (UTC_TIMESTAMP)
);