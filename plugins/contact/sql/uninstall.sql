TRUNCATE TABLE `mc_contact_content`;
DROP TABLE `mc_contact_content`;
TRUNCATE TABLE `mc_contact`;
DROP TABLE `mc_contact`;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'contact'
);