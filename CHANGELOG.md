# Changelog

All notable changes to `traccar` will be documented in this file

## 1.2.0 - 2026-05-18

### Added

- Added `phrity/websocket` support for the `traccar:listen` websocket command.
- Added websocket cookie configuration for browser access through `TraccarSession` middleware.

### Changed

- `traccar:listen` now authenticates websocket connections with the full `JSESSIONID=value` cookie from `getCookies()`.
- `TraccarSession` now sets the Traccar session cookie on the frontend response so browsers can automatically include it in websocket handshakes.
- README websocket documentation now targets the current Traccar cookie and token-based websocket behavior instead of the old custom `?session=` flow.

### Fixed

- Replaced the removed `session/check-sid` session validation flow with `GET /session` using the cached session cookie.

## 1.1.0 - 2026-05-18

### Added

- Added Bearer token API authentication support from the Traccar OpenAPI security schema.
- Added `TRACCAR_AUTH_METHOD` with `auto`, `token`, and `basic` modes.
- Added missing repositories for commands, orders, permissions, sharing, statistics, calendars, computed attributes, and maintenance.
- Added missing endpoint helpers for device images, position exports, server utilities, session tokens, TOTP, report geofences, and custom notifications.
- Added models for the newly supported API schemas, including commands, orders, permissions, calendars, computed attributes, maintenance, statistics, notification messages, and device accumulators.

### Changed

- Authorized requests now use `TRACCAR_TOKEN` as a Bearer token by default when configured, unless Basic authentication is forced.
- Existing resource methods now continue to work with Basic auth but transparently use token auth in `auto` mode.
- Report route responses now map to `Position` models, and report event responses now map to `Event` models.
- Updated README examples to document the current authentication behavior and all exposed repositories.

### Fixed

- Fixed model factory visibility so resource classes can create models from API responses reliably.
- Fixed `/notifications/test` usage to match the OpenAPI path.
- Fixed PHPStan configuration and type issues introduced by the new resources.

## 1.0.1 - 2025-07-21

fix

## v1.0.0 - 2024-02-28

first release

## 1.0.0

- initial release
- support Traccar (5.12)
