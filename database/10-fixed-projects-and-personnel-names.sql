UPDATE `Personnels` SET `name` = 'Engr. Pinky T. Jimenez, PECE, Ph.D.' WHERE `Personnels`.`personnel_id` = 1;
UPDATE `Personnels` SET `name` = 'Engr. Ronald S. Bariuan' WHERE `Personnels`.`personnel_id` = 18;
INSERT INTO `Projects` (`project_id`, `project_name`, `project_code`, `focal_person_id`, `project_logo`) VALUES (NULL, 'Technology for Education, Employment, Entrepreneurs, and Economic Development', 'Tech4Ed', '6', '/storage/tech4ed.png');
UPDATE `Projects` SET `focal_person_id` = '47' WHERE `Projects`.`project_id` = 7;
