# Changelog

## 1.1.0 - 2026-08-06

- Adopt `BlogPostAuthorTest` and `BlogResourceCoverageTest`, deleted from the host when this package
  left it, with the host's tenancy and Shield scaffolding dropped — neither was ever the subject.
- Add `tests/Fixtures/TestPanelProvider`: a Filament resource is only reachable through a panel, and
  this package ships a plugin while the host composes the panel. It is the first package in the
  fleet to boot one in its own suite.
- Move onto `liberusoftware/package-testbench`: the boundary suites ship with the testbench, and the
  package keeps only its own tests.

## 1.0.4 - 2026-08-01

- Add a validated, machine-readable feature catalog for runtime discovery and diagnostics.
- Add standalone tests that keep feature metadata consistent and unique.

## 1.0.0 - 2026-08-01

- Extract the Blog Filament resources into an optional presentation package.
