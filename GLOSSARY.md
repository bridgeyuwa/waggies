# Waggies

The public pet-care request flow for collecting service needs and sending them to Waggies for review and confirmation.

## Language

**Booking request**:
A customer-submitted request for one or more pet-care services that Waggies reviews and confirms operationally. It does not reserve availability by itself.
_Avoid_: reservation, confirmed booking (when referring to an unconfirmed submission)

**Booking request service**:
One requested pet-care service within a booking request. Each service owns its requested timing, care details, assigned pets, pricing snapshot, quote information, and operational status.

**Booking request review**:
The staff workflow for inspecting a booking request, correcting customer-submitted contact, pet, service, care, timing, location, and assignment details when needed, recording internal notes, preparing quotes, and progressing request and service statuses. Corrections are audited with the responsible staff member, time, and old/new values; the intake pricing snapshot remains historical.

**Request status**:
The lifecycle state of the overall booking request, representing Waggies' current position in reviewing or completing the customer's request.

**Service status**:
The operational lifecycle state of one booking request service. It may progress independently from other services in the same request, but the overall request is not confirmed while any requested service remains unresolved.

**Internal note**:
Staff-only commentary attached to a booking request or its operational handling. It is not customer-facing communication.

**Quote**:
A staff-provided price and explanation for one requested service. Quotes belong to individual services; a request-level total is a summary of those service quotes. A quote is distinct from the catalogue pricing used to guide public intake and does not by itself reserve availability.

**Pricing snapshot**:
The historical catalogue and option context captured when a customer submits a booking request. It explains the public estimate at intake and is not overwritten by a later staff quote.

**Mixed service outcome**:
A booking request where its services have different outcomes, such as one service being confirmed while another is declined. The parent request remains awaiting customer action rather than presenting itself as fully confirmed.

**Operational quote**:
The staff-managed price for a booking request service after review. It is stored per service and may differ from the historical pricing snapshot captured during public intake.

**Request confirmation**:
The explicit staff decision that the overall booking request is accepted after every requested service has been confirmed. Confirming individual services does not automatically confirm the parent request.
