INSERT INTO `JobOrder`(
    `job_order_id`,
    `project_id`,
    `created_at`,
    `scheduled_start_date`,
    `scheduled_end_date`,
    `performer_id`,
    `job_description`,
    `start_time`,
    `end_time`,
    `actual_job_done`,
    `remarks`,
    `client_name`,
    `client_position`,
    `client_contact`,
    `client_lgu`,
    `request_mode`,
    `request_date`,
    `issued_by`,
    `approved_by`,
    `job_order_number`
)
VALUES(
    NULL,
    '6',
    CURRENT_TIMESTAMP,
    '2024-02-26',
    '2024-02-27',
    '32',
    'Conduct eLGU training for the Staff of the Local Government Unit of Alcala and start the discussion for the eReadiness Survey for the said LGU',
    '2024-07-04 00:54:52.000000',
    '2024-07-04 00:54:52.000000',
    'Day 1 - February 26, 2024:\r\n\r\n1. Provided technical assistance to the participants from the LGU of Alcala in terms of discussion of specified technicalities in using the eGOV app.\r\n2. Assisted the trainers in the discussion of crucial parts in the configuration in the access control of the system.\r\n\r\nDay 2 - February 27, 2024:\r\n\r\n1. Assisted in the discussion of the recap from day 1. \r\n2. Assisted the trainers in the discussion of crucial parts in the civil registry and community tax certificate transaction in the eGOV app to the eGOV admin dashboard.',
    '',
    'Joel P. Manuel',
    'BPLO LGU Alcala',
    '09168225076',
    'Alcala, Cagayan',
    'On Site',
    '2024-06-27',
    '18',
    '1',
    'iBPLS-R2-2024-02-26-S37'
);

INSERT INTO `EndorsedPersonnels`(
    `endorsed_personnel_id`,
    `job_order_id`,
    `personnel_id`
)
VALUES(NULL, '1', '32'),(NULL, '1', '54');
