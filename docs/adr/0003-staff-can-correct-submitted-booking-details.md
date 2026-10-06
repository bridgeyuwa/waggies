# Staff can correct submitted booking details

**Status**: accepted

Authorized staff may correct customer-submitted contact details, the customer message, pet facts, service selections, care details, requested timing and location, and pet-to-service assignments when a customer has made a genuine mistake. The Filament review surface edits the existing pet and service records; this correction slice does not add or remove those records.

Corrections to submitted fields and pet assignments record the responsible staff member, time, and old/new values through the existing activity log. The historical intake pricing snapshot is captured when the request is created and is not rewritten by staff corrections. Staff review and update the operational service quote separately when a correction affects pricing.

Pet and service-specific intake fields continue to use the existing JSON `details` columns on their canonical records. Filament presents known fields with labels and controls from the shared booking schema, while preserving editable extra stored values. Parent summary fields remain compatibility mirrors of the primary submitted pet and service.

All request and service status changes continue to use guarded actions. This decision supersedes the customer-facts-read-only clauses in ADR 0002; it does not add customer messaging, availability reservation, or a staff activity timeline UI.
