# moodle-tool_competencycustomfields

[English](#english) · [Português (Brasil)](#português-brasil)

Documentation / Documentação: <https://moodle-fields-suite.github.io/tool_competencycustomfields/>

## English

Custom fields for competencies and competency frameworks, part of the [Moodle Fields Suite](https://moodle-fields-suite.github.io). Moodle has custom fields for courses, cohorts and groups, but not for competencies: this admin tool adds two areas, with every field type installed on the site. Fields are managed at *Site administration > Competencies*; values are edited from the competency and framework pages, shown there according to each field's visibility and read by integrations through the `local_fieldssuite_get_values` web service. Requires [`local_fieldssuite`](https://github.com/moodle-fields-suite/local_fieldssuite). Supports Moodle 4.5 LTS and 5.3 LTS.

Install: copy this repository to `admin/tool/competencycustomfields` (`public/admin/tool/competencycustomfields` on Moodle 5.1+), with `local_fieldssuite`, and visit *Site administration > Notifications*.

## Português (Brasil)

Campos personalizados para competências e quadros de competências, parte da [Moodle Fields Suite](https://moodle-fields-suite.github.io). O Moodle tem campos personalizados para cursos, coortes e grupos, mas não para competências: esta ferramenta administrativa acrescenta duas áreas, com todos os tipos de campo instalados no site. Os campos são geridos em *Administração do site > Competências*; os valores são editados a partir das páginas da competência e do quadro, aparecem nelas conforme a visibilidade de cada campo e podem ser lidos por integrações pelo web service `local_fieldssuite_get_values`. Requer o [`local_fieldssuite`](https://github.com/moodle-fields-suite/local_fieldssuite). Suporta Moodle 4.5 LTS e 5.3 LTS.

Instalação: copie este repositório para `admin/tool/competencycustomfields` (`public/admin/tool/competencycustomfields` no Moodle 5.1+), junto com o `local_fieldssuite`, e acesse *Administração do site > Notificações*.

## Development / Desenvolvimento

- CI (`.github/workflows/ci.yml`) runs `moodle-plugin-ci` on Moodle 4.5 and 5.3 with PostgreSQL and MariaDB, installing `local_fieldssuite`; run it locally with [`act`](https://github.com/nektos/act) before pushing.

## Support and privacy / Suporte e privacidade

- Issue tracker / Rastreador de issues: <https://github.com/moodle-fields-suite/tool_competencycustomfields/issues>
- Privacy / Privacidade: the values belong to competencies and frameworks, not to users; nothing leaves Moodle / os valores pertencem às competências e aos quadros, não aos usuários; nada sai do Moodle.
- Translations / Traduções: only English is shipped; other languages go through AMOS / o pacote traz só inglês; outros idiomas via AMOS.

## License / Licença

GNU GPL v3 or later. See [LICENSE](LICENSE).
