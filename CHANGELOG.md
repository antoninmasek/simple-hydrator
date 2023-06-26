# Changelog

All notable changes to `simple-hydrator` will be documented in this file.

## [1.0.0] - 2023-06-26

### Changed

- **Breaking:** spaces are now ignored in array keys. This means that for the following
  array `$data = ['service Appointments' => ['2022-06-01']]` the `service Appointments` key will be treated as if the
  key was `serviceAppointments` hence it would be saved into `$serviceAppointments` property 
