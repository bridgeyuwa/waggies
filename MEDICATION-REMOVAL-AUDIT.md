# Medication Removal Audit

## Scope and decision

The medication models and tables were not consumed by a current booking, fulfilment, clinical, catalogue, or editorial workflow. Their only runtime product surface was a medication reference tool and its admin resource. That is not a demonstrated requirement for persistent medication entities, so the medication database domain is being removed rather than replaced.

Medication words that remain in ordinary business copy, customer intake, emergency triage, assistant instructions, and safety warnings are not medication persistence. They are retained as application safety or page/domain language.

## Dependency table

| Component | Type | Current purpose | Consumers | Required by current product? | Action |
| --- | --- | --- | --- | --- | --- |
| `App\Models\Medication` | Model / persistent domain | Stored medication identity, warnings, indications, routes, and classification | `ToolsController::medication()`, Medication Filament resource, medication tests | No demonstrated business workflow | `REMOVE` |
| `App\Models\MedicationFormulation` | Model / persistent domain | Stored formulation, concentration, dose data, and dose-display flag | Medication tool, `Medication::formulations()`, medication tests | No; dose safety does not require persistence | `REMOVE` |
| `App\Models\MedicationJurisdiction` | Model / persistent domain | Stored registration and label context by jurisdiction | Medication tool, `Medication::jurisdictions()` | No demonstrated current use | `REMOVE` |
| `MedicationClassification` | Enum | Classification cast for `Medication` | Medication model and Filament form | No after model removal | `REMOVE` |
| `Jurisdiction` | Enum | Unused jurisdiction vocabulary left by the former medication/reference design | No runtime consumer | No | `REMOVE` |
| `medications` | Database table | Medication records | Medication model/tool/admin | No | `REMOVE` |
| `medication_formulations` | Database table | Formulation and dose records | MedicationFormulation model/tool | No | `REMOVE` |
| `medication_jurisdictions` | Database table | Registration and label records | MedicationJurisdiction model/tool | No | `REMOVE` |
| Medication tables' foreign keys/indexes/columns | Schema infrastructure | Persistence support for the three medication tables | Medication migrations/models | No | `REMOVE` |
| `MedicationResource` and medication Filament pages/forms/tables | Admin UI | Create/edit/list persistent medication records | Filament admin panel | No | `REMOVE` |
| `ToolsController::medication()` | Medication-only page action | Loaded medication records and rendered medication reference content | `/tools/medication-dosage-guide` | No demonstrated requirement | `REMOVE` |
| Medication dosage guide Blade view | Medication-only page | Rendered formulation/jurisdiction/dose reference UI | `tools.medication` route | No | `REMOVE` |
| Medication entry in `ToolData::catalogue()` | Tool catalogue data | Advertised a medication database-backed tool | Tools index and related-tools component | No | `REMOVE` |
| Medication navigation and sitemap entries | Route/navigation integration | Linked the medication tool from menus and sitemap | `config/waggies.php`, `PublicUrlCatalog`, related tools | No | `REMOVE` |
| `Medication::public()` and clinical publication coupling | Removed-domain integration | Filtered medication records through the former governance layer | Medication controller/model | No | `REMOVE` |
| `MedicationFormulation::canDisplayDose()` | Safety behavior coupled to persistence | Allowed a stored dose only when a stored formulation was enabled and had dose data | Medication page and medication test | No after page removal | `REMOVE` |
| `ClinicalSafetyBoundary` | Application safety | Escalates suspected poisoning, dangerous medication ingestion, dosage requests, and diagnosis requests | Assistant controllers | Yes; independent of medication records | `PRESERVE` |
| Assistant dosage/poisoning handling | Application safety | Prevents unsafe model responses and directs veterinary care | Assistant endpoints and safety tests | Yes | `PRESERVE` |
| Emergency/symptom tool warnings | Page content / application behavior | Warns against human medication, leftover prescriptions, and home remedies | Emergency and symptom tools | Yes as general safety copy | `PRESERVE` |
| Booking/contact medication fields | Operational intake data | Collects medication instructions or current medications supplied by a customer | Booking/contact forms | Yes as customer-provided care context; not a medication domain | `PRESERVE` |
| Medication wording in Guides, FAQs, and Knowledge Articles | Database-backed page content | Explains what owners should bring or disclose | Existing editorial content | Yes as ordinary page copy | `PRESERVE` |
| Medication icon asset/component mapping | Shared presentation | Supports the removed medication page's icon | Medication page only | No after page removal | `REMOVE` |

## Runtime findings

1. The three medication models had no consumers outside the medication tool, medication Filament resource, and medication-specific regression test.
2. No booking, product, pricing, contact, search, sitemap, or editorial workflow required medication records.
3. The medication route was a standalone reference page, not a required public business route. It is removed and documented as an intentional route removal.
4. The general safety boundary does not query medication records. It classifies dangerous messages and escalates them directly, so it remains code-owned.
5. Customer medication fields are free-text operational intake, not medication persistence, and remain unchanged.

## Removed

- Medication, MedicationFormulation, and MedicationJurisdiction models and relationships.
- MedicationClassification and the unused Jurisdiction enum.
- Medication Filament resource, pages, form, table, and navigation item.
- Medication dosage guide controller action, Blade view, route, ToolData catalogue entry, navigation, related-tool links, and sitemap entry.
- Medication-only tests and the medication dose-display persistence test.
- Medication tables through the forward schema-removal migration.

## Preserved

- `ClinicalSafetyBoundary` and assistant request-time safety behavior.
- Dangerous medication ingestion, dosage, poisoning, and diagnosis escalation tests.
- Emergency and symptom warnings against human medication and unsafe home treatment.
- Booking/contact medication fields used to communicate customer-provided care requirements.
- Existing FAQ, Guide, Knowledge Article, service, search, sitemap, canonical, media, and unrelated tool behavior.
- Clinical governance remains removed; no replacement medication or clinical CMS was introduced.

## Simplified

- The medication tool is removed instead of retaining a database-free shell for an unsupported feature.
- Search/sitemap/navigation now contain no medication route or medication-specific entry.
- The schema-removal migration drops medication tables after removing the dependent clinical foreign key already handled by the previous governance-removal migration.

## Remaining Medication References

Remaining matches are intentionally limited to these categories:

| Reference family | Classification | Why it remains |
| --- | --- | --- |
| Poisoning, dosage, human medication, and leftover-prescription handling in `ClinicalSafetyBoundary`, assistant instructions, symptom data, and emergency data | `APPLICATION_SAFETY` | These are request-time safety and escalation rules; they do not load or write medication records. |
| Medication instructions in booking/contact fields and existing FAQ/Guide/Knowledge Article copy | `PAGE_CONTENT` / `GENERAL_DOMAIN_LANGUAGE` | Customers may need to disclose current medication or receive ordinary care guidance; this is not a medication catalogue. |
| Breed health language about drug sensitivity | `GENERAL_DOMAIN_LANGUAGE` | It is part of breed reference copy and has no medication persistence or lookup. |

There is no remaining persistent medication domain, medication model, medication table, medication-specific admin workflow, or medication-specific public route.
