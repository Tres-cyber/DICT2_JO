ALTER TABLE Personnels
ADD project_id INTEGER UNSIGNED NULL DEFAULT 3;

ALTER TABLE Personnels
ADD CONSTRAINT fk_project_id FOREIGN KEY (project_id)
    REFERENCES Projects (project_id)
    ON DELETE SET NULL
    ON UPDATE CASCADE;
