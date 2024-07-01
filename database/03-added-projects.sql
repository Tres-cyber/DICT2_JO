INSERT INTO Projects(project_name, project_code, focal_person_id)
VALUES
(
    'ICT Industry Development Bureau',
    'IIDB',
    26
),(
    'National ICT Planning, Policy and Standards Bureau',
    'NIPPSB',
    6
),(
    'electronic Local Government Unit',
    'e-LGU',
    18
),(
    'Cybersecurity',
    'PNPKI',
    35
),(
    'National Government Portal',
    'NGP',
    26
),(
    'ICT Literacy and Competency Development Bureau',
    'ILCDB',
    6
),(
    'Government Emergency Communications System',
    'GECS',
    18
),(
    'Free Wi-Fi for All - Free Public Internet Access Program',
    'Free Wi-Fi 4 All',
    47
),(
    'Government Network',
    'GovNet',
    47
);

UPDATE Projects SET project_name = 'Philippine National Public Key Infrastructure' WHERE project_id = 4
