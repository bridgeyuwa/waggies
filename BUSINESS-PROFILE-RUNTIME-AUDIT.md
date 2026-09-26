# BusinessProfile Runtime Audit

The BusinessProfile row is the runtime authority for public business contact, location, map, and social data. The seed fixture supplies canonical bootstrap data; it is not a runtime source.

For affected business-data consumers that were already database-backed, `MOVE_TO_DATABASE` records that they remain in the database-owned runtime path; no additional migration was required for that consumer.

| Config key/value | Consumer | Runtime location | Current source | Database field | Final source | Action |
| --- | --- | --- | --- | --- | --- | --- |
| phone | BusinessProfile fallback | app/Models/BusinessProfile.php | Config fallback when the row/table was unavailable | phone | BusinessProfile | REMOVE_DEAD_USAGE |
| phone | BusinessProfile bootstrap | database/migrations/2026_09_22_234454_create_business_profiles_table.php | Config | phone | BusinessProfile seed fixture / database row | MOVE_TO_DATABASE |
| phone | Waggies content seed | database/seeders/WaggiesContentSeeder.php | Config | phone | database/seeders/fixtures/business_profile.php | MOVE_TO_DATABASE |
| phone | Contact emergency helper text | app/Http/Controllers/ContactController.php | Hard-coded display value | phone | BusinessProfile | MOVE_TO_DATABASE |
| phone | Contact Call Us display | resources/views/pages/contact.blade.php | BusinessProfile public data | phone | BusinessProfile | MOVE_TO_DATABASE |
| phone | Tool index CTA | resources/views/pages/tools/index.blade.php | Config | phone | BusinessProfile | MOVE_TO_DATABASE |
| phone | Emergency guide CTA | resources/views/pages/tools/emergency-guide.blade.php | Config | phone | BusinessProfile | MOVE_TO_DATABASE |
| phone | Medical tool CTA | resources/views/components/waggies/tool-cta.blade.php | Config | phone | BusinessProfile | MOVE_TO_DATABASE |
| phone | Footer phone link | resources/views/components/waggies/footer.blade.php | BusinessProfile public data | phone | BusinessProfile | MOVE_TO_DATABASE |
| phone_international | BusinessProfile fallback | app/Models/BusinessProfile.php | Config fallback | phone_international | BusinessProfile | REMOVE_DEAD_USAGE |
| phone_international | BusinessProfile bootstrap | database/migrations/2026_09_22_234454_create_business_profiles_table.php | Config | phone_international | BusinessProfile seed fixture / database row | MOVE_TO_DATABASE |
| phone_international | Waggies content seed | database/seeders/WaggiesContentSeeder.php | Config | phone_international | database/seeders/fixtures/business_profile.php | MOVE_TO_DATABASE |
| phone_international | Tel-link formatting | app/Models/BusinessProfile.php | Model public projection | phone_international | BusinessProfile presentation projection | MOVE_TO_DATABASE |
| whatsapp | BusinessProfile fallback | app/Models/BusinessProfile.php | Config fallback | whatsapp_url | BusinessProfile | REMOVE_DEAD_USAGE |
| whatsapp | BusinessProfile bootstrap | database/migrations/2026_09_22_234454_create_business_profiles_table.php | Config | whatsapp_url | BusinessProfile seed fixture / database row | MOVE_TO_DATABASE |
| whatsapp | Waggies content seed | database/seeders/WaggiesContentSeeder.php | Config | whatsapp_url | database/seeders/fixtures/business_profile.php | MOVE_TO_DATABASE |
| whatsapp | Booking handoff | app/Http/Controllers/BookingRequestsController.php | Config | whatsapp_url | BusinessProfile | MOVE_TO_DATABASE |
| whatsapp | Contact handoff | resources/views/pages/contact.blade.php | BusinessProfile public data | whatsapp_url | BusinessProfile | MOVE_TO_DATABASE |
| whatsapp | Emergency guide CTA | resources/views/pages/tools/emergency-guide.blade.php | Config | whatsapp_url | BusinessProfile | MOVE_TO_DATABASE |
| whatsapp | Floating WhatsApp action | resources/views/components/waggies/floating-actions.blade.php | Config | whatsapp_url | BusinessProfile | MOVE_TO_DATABASE |
| whatsapp | Footer WhatsApp link | resources/views/components/waggies/footer.blade.php | BusinessProfile | whatsapp_url | BusinessProfile | MOVE_TO_DATABASE |
| map_url | BusinessProfile fallback | app/Models/BusinessProfile.php | Config fallback | map_url | BusinessProfile | REMOVE_DEAD_USAGE |
| map_url | BusinessProfile bootstrap | database/migrations/2026_09_22_234454_create_business_profiles_table.php | Config | map_url | BusinessProfile seed fixture / database row | MOVE_TO_DATABASE |
| map_url | Map backfill migration | database/migrations/2026_09_23_142514_backfill_business_profile_map_url.php | Config | map_url | BusinessProfile seed fixture / database row | MOVE_TO_DATABASE |
| map_url | Waggies content seed | database/seeders/WaggiesContentSeeder.php | Config | map_url | database/seeders/fixtures/business_profile.php | MOVE_TO_DATABASE |
| map_url | Contact map embed | resources/views/pages/contact.blade.php | Config fallback | map_url | BusinessProfile | MOVE_TO_DATABASE |
| address | BusinessProfile fallback | app/Models/BusinessProfile.php | Config fallback | address_street, address_city, address_postal_code, address_state, address_country | BusinessProfile | REMOVE_DEAD_USAGE |
| address | BusinessProfile bootstrap | database/migrations/2026_09_22_234454_create_business_profiles_table.php | Config | Address columns | BusinessProfile seed fixture / database row | MOVE_TO_DATABASE |
| address | Waggies content seed | database/seeders/WaggiesContentSeeder.php | Config | Address columns | database/seeders/fixtures/business_profile.php | MOVE_TO_DATABASE |
| address | About Visit Us section | app/Http/Controllers/AboutController.php | Hard-coded address | Address columns | BusinessProfile | MOVE_TO_DATABASE |
| address | Contact address display | resources/views/pages/contact.blade.php | BusinessProfile public data | Address columns | BusinessProfile | MOVE_TO_DATABASE |
| address | Organization/LocalBusiness JSON-LD | app/Providers/AppServiceProvider.php | BusinessProfile | Address columns | BusinessProfile | MOVE_TO_DATABASE |
| socials | BusinessProfile fallback | app/Models/BusinessProfile.php | Config fallback | Social URL columns | BusinessProfile | REMOVE_DEAD_USAGE |
| socials | BusinessProfile bootstrap | database/migrations/2026_09_22_234454_create_business_profiles_table.php | Config | Social URL columns | BusinessProfile seed fixture / database row | MOVE_TO_DATABASE |
| socials | Waggies content seed | database/seeders/WaggiesContentSeeder.php | Config | Social URL columns | database/seeders/fixtures/business_profile.php | MOVE_TO_DATABASE |
| socials | Global footer social links | resources/views/components/waggies/footer.blade.php | BusinessProfile | Social URL columns | BusinessProfile | MOVE_TO_DATABASE |
| socials | Organization/LocalBusiness JSON-LD | app/Providers/AppServiceProvider.php | BusinessProfile | Social URL columns | BusinessProfile | MOVE_TO_DATABASE |
| socials | Public assistant/tool output | app/AI/Tools/BusinessProfileTool.php | BusinessProfile | Social URL columns | BusinessProfile | MOVE_TO_DATABASE |
| navigation | Main navigation | resources/views/components/waggies/navbar.blade.php | Configuration | config/waggies.php | Configuration | KEEP_CONFIGURATION |
| waggies_pricing | Contact/service pricing payload | app/Http/Controllers/ContactController.php | Configuration | Not database-owned | Configuration | KEEP_CONFIGURATION |

## Environment variables

WAGGIES_PHONE, WAGGIES_PHONE_INTERNATIONAL, WAGGIES_WHATSAPP, and WAGGIES_MAP_URL have no remaining runtime consumers. Their affected config entries and .env.example references were removed. The local ignored .env was left untouched.
