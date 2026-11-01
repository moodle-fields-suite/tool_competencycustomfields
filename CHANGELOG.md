# Changelog

## 1.0.0 - 2026-11-01

First release, built on `local_fieldssuite` 1.1.0.

### Added
- Two custom field areas: competencies and competency frameworks, with every field type installed on the site; values kept in the context of the framework.
- Field management at *Site administration > Competencies > Competency custom fields* and *Competency framework custom fields*, with the area settings *Locked* and *Visible to*.
- Values shown, with an *Edit custom fields* link, on the edit competency page, the edit framework page and the framework page; edited on the common page of `local_fieldssuite`.
- Values deleted when their competency or framework is deleted.
- Capabilities `tool/competencycustomfields:configurecustomfields`, `tool/competencycustomfields:edit` and `tool/competencycustomfields:changelockedcustomfields`.
- Privacy API provider, PHPUnit tests, CI with moodle-plugin-ci (Moodle 4.5 and 5.3, PostgreSQL and MariaDB), documentation in English and Brazilian Portuguese, release by draft and verification.
