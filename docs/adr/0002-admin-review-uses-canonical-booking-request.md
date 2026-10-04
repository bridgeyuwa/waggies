# Admin review uses the canonical booking request

**Status**: accepted

Filament Admin will operate as a staff review console over the canonical booking request structure rather than recreating the public intake flow or relying on its legacy flat parent fields. Booking request services may be reviewed and quoted independently, while the parent request represents the overall outcome and cannot be confirmed while any requested service remains unresolved. Service quotes are authoritative operational values; the pricing snapshot remains historical intake context. The first review slice will not add customer messaging, availability reservation, or direct correction of customer-submitted facts.

The parent request uses `Awaiting customer` for mixed service outcomes in the first slice rather than introducing a partial-confirmation state. Services share the parent lifecycle vocabulary and use guarded transitions. A quoted service needs an amount or clear manual-quote notes, the Admin queue prioritizes requests needing attention, and status/quote changes record the responsible staff member and time through the existing activity-log infrastructure.

Staff use explicit guarded actions rather than an unrestricted status dropdown. Customer-submitted facts remain read-only in this slice; staff may edit internal notes and service-level operational quotes and statuses. Service quotes are the source of truth, while legacy parent quote columns remain calculated compatibility summaries. Audit records capture actor, timestamp, and old/new values for status and quote changes without logging the entire customer submission or adding a timeline UI.

The parent status is explicit rather than automatically derived. Confirming the parent requires every service to be confirmed, while declining or cancelling the parent cascades to unresolved services. Service transitions reuse the parent's guarded lifecycle graph, and the existing `is_admin` boundary is sufficient for the first slice.
