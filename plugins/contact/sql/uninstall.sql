TRUNCATE TABLE `mc_contact_page_content`;
DROP TABLE `mc_contact_page_content`;
TRUNCATE TABLE `mc_contact_page`;
DROP TABLE `mc_contact_page`;
TRUNCATE TABLE `mc_contact_content`;
DROP TABLE `mc_contact_content`;
TRUNCATE TABLE `mc_contact`;
DROP TABLE `mc_contact`;
TRUNCATE TABLE `mc_contact_config`;
DROP TABLE `mc_contact_config`;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'contact'
);