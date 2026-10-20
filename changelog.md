# Changelog

## v1.0.3

- NEW: Add Dependabot GitHub Actions Monitoring
- FIX: DEPENDENCY: Upgrade Super-Linter To 9.0.0


## Version 1.0.2 (2026-10-18)

- FIX: Renamed the plug-in asset directory to canonical `Assets/` casing so
  protected Linux Kanboard deployments serve the stylesheet and JavaScript
  instead of returning HTTP 403 responses.
- Relates to `d1b529e`.
- No database changes.


## Version 1.0.1 (2026-10-18)

- Renamed the canonical changelog to `changelog.md` so Kanboard release
  auditing and cross-platform package preparation use the expected filename.
- No database changes.


## Version 1.0.0 (2026-10-16)

- Fixed project access checks to use the current Kanboard session and complete permission model.
- Fixed task queries for Kanboard 1.2.54 by using valid lower-case SQL aliases and safe empty access handling.
- Removed the plugin-wide Content Security Policy override.
- Replaced TableSorter, PgwModal and unused Bootstrap assets with a dependency-free table controller.
- Added responsive task cards, theme-aware colours, accessible controls and user-timezone dates.
- Escaped task data and rendered descriptions through Kanboard's Markdown helper.
- Added British English translations and a reusable starter catalogue.
- Added focused PHP 8.2 to 8.5 regression checks and JavaScript validation.


## Version 0.0.2

- Updated design - borders removed for more smooth design. Take care, some of the CSS uses !important :(
- CSS and JS removed from **show.php** to `assets` folder `css` and `js`
- **show.php** for all projects and specific project combined in one **show.php** and replaced in `tabler` folder. Checking variable **$projects** value.


## Version 0.0.1

- Initial release
