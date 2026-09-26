# Opening hours audit

## Source of truth

`business_hours` remains the authoritative Waggies schedule table and the Filament Business Hours resource remains its administration surface.

The existing table now stores:

- one `weekly` row per `day_of_week` (`0` Sunday through `6` Saturday);
- one `exception` row per one-time or recurring exception definition;
- `date` and optional `end_date` for single dates and date ranges;
- `recurrence` as `once` or `yearly`;
- `is_closed` for full-day closures;
- up to two daily intervals;
- an optional `label` for customer-facing context.

The database-to-package boundary is deliberately thin. Waggies owns persistence and presentation; Spatie owns opening-hours interpretation.

## Public views

The Contact page has two distinct views:

- `Every day` is a rolling window of exactly today plus the next six calendar dates. The application selects those seven dates, then calls Spatie `forDate()` for each date. It is not anchored to Monday–Sunday.
- `Grouped days` is the recurring weekly schedule produced by Spatie `forWeekConsecutiveDays()`. Date-specific exceptions never alter this recurring grouping.

Both views use package-derived intervals. Alpine only switches between 12-hour and 24-hour labels and does not calculate schedule state.

## Opening-hours calculation

Installed package:

```text
spatie/opening-hours 4.2.2
```

`BusinessHour::openingHours()` builds one `Spatie\OpeningHours\OpeningHours` instance from the database. The implementation uses the package for:

- weekly schedules and split intervals;
- date-specific, recurring, and range exceptions;
- closed/excluded dates via an empty exception schedule;
- current open/closed state;
- current open range;
- next opening and next closing;
- weekly grouping;
- Schema.org opening-hours structured data;
- always-closed/no-schedule detection.

The remaining model code is an adapter and presentation serializer. It formats returned package value objects and computes only display geometry and labels after Spatie has returned the ranges.

## Exceptions and excluded days

Spatie-native exception keys are generated from the same `BusinessHour` records:

- `2026-12-25` — one-time date;
- `12-25` — recurring annual date;
- `2026-12-24 to 2026-12-26` — one-time range;
- `12-24 to 12-26` — recurring annual range.

`is_closed = true` becomes `[]`, which is Spatie’s closed/excluded representation. Each rolling-window date is resolved with `forDate()`, so an excluded Thursday overrides its normal weekly hours without changing the recurring grouped view.

Exception labels, recurrence text, and range labels are presentation metadata. The package remains responsible for deciding which hours apply.

## Filament administration

Filament supports:

- weekly closed days;
- one or two opening intervals;
- one-time exceptions;
- annual recurring exceptions;
- one-time exception ranges;
- annual recurring exception ranges;
- full-day closures without mandatory time fields;
- modified exception hours;
- optional exception descriptions.

When an open schedule is submitted, the form reconstructs the candidate database-backed schedule and passes it through Spatie’s parser. Package parsing failures, including overlapping or malformed ranges, are returned as Filament validation errors. No parallel overlap, recurrence, or exception-precedence engine exists.

## Timezone and status

The business profile timezone is currently `Africa/Lagos`. It is passed into Spatie for all status, date, and exception evaluation. The public component identifies the timezone subtly as “Times shown in Africa/Lagos”.

If Spatie reports an always-closed schedule and no weekly records exist, the public status is `Hours unavailable`. An explicitly configured all-closed weekly schedule remains `Closed`.

## Structured data

The existing Waggies `LocalBusiness` Schema.org object now receives the result of Spatie `asStructuredData()`. No second `LocalBusiness` schema and no custom opening-hours JSON-LD serializer were added.

## Accessibility and responsive behavior

- view and time-format controls are native buttons with `aria-pressed`;
- focus-visible styling comes from the Waggies design system;
- the current day is named with text and `aria-current`;
- status is announced with `aria-live`;
- timeline bars are supplementary and marked `aria-hidden`;
- every interval, closure, and exception remains available as text;
- exception rows wrap on small screens and controls remain touch-friendly.

## Final architecture

```text
BusinessHour database
        ↓
Waggies adapter
        ↓
Spatie\OpeningHours\OpeningHours
        ↓
package-derived date/schedule/status results
        ↓
Contact presentation
```

The application chooses the rolling calendar dates. Spatie determines the schedule for each date.
