# Spatie Opening Hours implementation audit

## Enforcement statement

Package:
`spatie/opening-hours`

Installed version:
`4.2.2`

Opening-hours calculation engine: `Spatie\OpeningHours\OpeningHours`

Custom opening-hours calculation engine: **NONE**

`App\Models\BusinessHour` is an adapter and presentation serializer only. It maps the existing `business_hours` records into the package input format, passes the business timezone to the package, calls package methods for schedule behavior, and formats the returned package objects for Blade/Alpine. It does not independently decide whether a date or time is open.

## Required behavior audit

| Required behavior | Package capability | Exact package API used | Existing custom implementation | Action |
|---|---|---|---|---|
| Weekly schedule | Weekday opening-hour definitions | `OpeningHours::create()` | DB rows are mapped to the package's `sunday` through `saturday` keys | Keep adapter mapping; package remains the source of behavior |
| Open/closed state | Date-time and day checks | `isOpenAt()`, `isOpenOn()`, `OpeningHoursForDay::isEmpty()` | None | Use package result |
| Current open range | Range containing a date-time | `currentOpenRange()` | None | Use package result |
| Current close | End of the active range | `nextClose()` | None | Use package result |
| Next opening | Next available opening across dates | `nextOpen()` | None | Use package result |
| Next closing | Next close after a date-time | `nextClose()` | None | Use package result |
| Weekly generation | Weekly day objects | `forWeek()` | Date labels are presentation-only | Use package result |
| Consecutive grouping | Groups equal consecutive weekday schedules | `forWeekConsecutiveDays()` | Labels are presentation-only | Use package result |
| Combined grouping | Compact week representation | `forWeekCombined()` is available | The UI specifically needs consecutive grouped rows, so this API is not used | No custom replacement; retain `forWeekConsecutiveDays()` for the requested view |
| Date-specific schedule | Resolve one exact date, including exceptions | `forDate()` | Exception labels and dates are presentation data only | Use package result for each displayed date |
| Exceptions | Exact-date overrides | `OpeningHours::create([... 'exceptions' => [...]])` and `forDate()` | DB records are mapped to the package's exception map | Keep adapter mapping; `[]` means closed/excluded |
| Exception collection inspection | Read parsed package exceptions | `exceptions()` | Database records are retained only for labels and dates | Inspected in v4.2.2; no second exception engine is needed |
| Closed/excluded exception | Empty date schedule overrides weekly hours | Exception value `[]`; `forDate()->isEmpty()` | `is_closed` is translated to `[]` only | Keep DB-to-package translation |
| Timezone | Input/output timezone-aware calculations | `OpeningHours::create($data, $timezone)` | Profile timezone is selected and passed through | Keep boundary configuration |
| Contact status text | Package-backed current status | `isOpenAt()`, `currentOpenRange()`, `nextOpen()`, `nextClose()` | 12/24-hour strings are presentation formatting | Keep formatting only |
| Timeline bars | Render package-returned ranges | `OpeningHoursForDay` and `TimeRange` values from `forWeek()`/`forDate()` | Percent positions and CSS labels are presentation geometry | Keep presentation serializer only |
| Filament administration | Store all supported fields needed by the adapter | Existing `BusinessHour` Filament form/table | Form labels, paired interval validation, and exception display are admin UX | Keep Filament as the storage interface |

## Database-to-package boundary

The current schema supports one weekly row per weekday, two daily intervals, full-day closures, one-time exceptions, annual recurring exceptions, and one-time or annual date ranges. The adapter maps those fields as follows:

- `day_of_week` selects the package weekday key;
- `open_time`/`close_time` and `second_open_time`/`second_close_time` become package interval strings;
- `is_closed = true` becomes an empty interval array;
- each non-null exception `date` and optional `end_date` becomes a package exception key;
- `recurrence = once` produces an exact date or exact date range key;
- `recurrence = yearly` produces an annual date or annual date range key;
- the profile timezone is passed to `OpeningHours::create()`.

No alternate `isOpen`, `nextOpen`, range, date-exception, weekday-grouping, or interval-merging algorithm remains in the application layer. The only remaining time arithmetic computes visual bar geometry and display strings after the package has already returned its ranges.

## Filament coverage

The Filament Business Hours resource exposes weekly rows and exception rows with:

- one-time and annual recurring exceptions;
- single dates and date ranges;
- full-day closures without mandatory time fields;
- modified hours with one or two intervals;
- optional labels shown in the exception list;
- package-backed validation for malformed and overlapping schedules.

The form hides and removes the time requirement when a row is closed. Open rows are validated by reconstructing the candidate schedule through Spatie's parser, so parse errors are presented as normal Filament validation errors. There is no duplicate overlap or exception-precedence implementation in Filament.

## Rolling public window

The public `Every day` view always contains exactly seven selected calendar dates: today and the next six days in the business timezone. The application selects those dates; each date is resolved through `OpeningHours::forDate()`, allowing package exceptions to override weekly hours.

The public `Grouped days` view remains a recurring Monday-to-Sunday representation generated from `OpeningHours::forWeekConsecutiveDays()`. It is intentionally not replaced by the rolling window.

## Structured data and no-schedule state

The existing Waggies `LocalBusiness` Schema.org object receives the package's `asStructuredData()` output. No second `LocalBusiness` object and no custom opening-hours JSON-LD serializer were added.

`OpeningHours::isAlwaysClosed()` distinguishes a missing weekly schedule from an explicitly configured all-closed schedule. The first is presented as unavailable; the second remains closed.

## Verification coverage

`tests/Feature/OpeningHoursPresentationTest.php` exercises the real package object through `BusinessHour::openingHours()` and verifies split intervals, current ranges, next opening/closing, rolling seven-day output, annual and range exceptions, weekly consecutive grouping, 12-hour labels, no-schedule state, structured data, and the Contact page payload. Filament feature tests cover annual range persistence, closure validation behavior, and package-backed overlap rejection.
