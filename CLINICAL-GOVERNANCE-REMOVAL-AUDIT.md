# Clinical Governance Removal Audit

## Scope and decision

The repository contained a clinical editorial governance subsystem, but no demonstrated requirement for clinician accounts, formal clinical review records, source registers, version snapshots, or clinical publication workflows. That subsystem has been removed. The removal does not turn safety guidance into ordinary medical advice: safety boundaries, escalation rules, medication warnings, and fail-closed dose display remain code-owned behavior.

The already-applied governance migrations remain in migration history. The forward migration `2026_09_24_005831_remove_clinical_governance_schema.php` removes their tables and governance-only columns from the current schema. It deliberately does not recreate governance records on rollback because those records are not part of the retained domain.

## Dependency table

| Component | Type | Current purpose before removal | Consumers | Genuine requirement? | Action | Final source of truth |
| --- | --- | --- | --- | --- | --- | --- |
| `ClinicalContent` and `clinical_contents` | Persistent governance dataset | Approval, publication, withdrawal, risk, version, and source gates | Guides, Knowledge Articles, Medication, Filament, search observer | No evidence of a required clinician editorial workflow | `REMOVE` | None; normal editorial status fields or application code own the retained behavior |
| `ClinicalContentVersion` and `clinical_content_versions` | Governance version dataset | Clinical snapshots and approval-version tracking | Clinical workflow and Filament | No | `REMOVE` | None |
| `ClinicalReview` and `clinical_reviews` | Governance review dataset | Reviewer decisions, credentials, notes, and review dates | Clinical workflow and Filament | No | `REMOVE` | None |
| `ClinicalSource` and `clinical_sources` | Governance source dataset | Source register, evidence metadata, conflicts, and source links | Clinical workflow and Filament | No | `REMOVE` | None |
| `ClinicalToolReview` and `clinical_tool_reviews` | Tool-governance dataset | Review/version records for tool keys | Filament and governance tests | No | `REMOVE` | Tool-specific safety rules remain in `ToolsController` and `ToolData` |
| `clinical_content_source` | Governance pivot dataset | Links between governed content and sources | ClinicalContent and ClinicalSource | No | `REMOVE` | None |
| `users.is_clinical_reviewer` | Governance authorization field | Grants access to reviewer policies and work queues | Governance policies, Filament widgets, workflow | No | `REMOVE` | Normal Filament access remains `User::canAccessPanel()` |
| `Guide::clinicalContent()` | Incorrect governance relationship | Added a second publication gate to database-backed guides | Guide indexability, sitemap, search | No | `SIMPLIFY` | Guide `status`, `published_at`, `is_indexable`, and `include_in_sitemap` |
| `KnowledgeArticle::clinicalContent()` | Incorrect governance relationship | Added a second publication gate to database-backed knowledge articles | Knowledge indexability, sitemap, search | No | `SIMPLIFY` | Knowledge Article `status`, `published_at`, `is_indexable`, and `include_in_sitemap` |
| `Medication::clinicalContent()` and `clinical_content_id` | Incorrect governance relationship | Exposed medication records only through governed content | Medication tool and Filament | No | `SIMPLIFY` | Medication, formulation, and jurisdiction records; application safety gates |
| `MedicationFormulation::canDisplayDose()` | Application safety behavior with governance coupling | Withheld doses unless both a governance record and explicit dose flag existed | Medication tool | The safety gate is required; governance coupling is not | `SIMPLIFY` | `dose_display_enabled` plus non-null dose data, with veterinary escalation in the tool |
| `ClinicalContentWorkflow` and `ClinicalPublicationGate` | Governance behavior | Approval, publication, withdrawal, and source checks | Filament actions and model events | No | `REMOVE` | None |
| `ClinicalContentObserver` | Governance/search integration | Rebuilt search documents from governance record changes | App service provider | No | `REMOVE` | `SearchContentObserver` and `SearchDocumentSynchronizer` for retained editorial models |
| Clinical policies and governance Filament resources | Governance administration | Protected review/source/content/tool-review screens | Admin panel and tests | No | `REMOVE` | Existing FAQ, Guide, Knowledge Article, Product, Testimonial, Gallery, Job, Business, and Medication resources |
| Clinical status/review metadata in `ToolsController` behavior data | Page data mixed with governance scaffolding | Marked controller-owned advice as pending review/CMS-replaceable | Behavior tool payload | The advice and escalation points are required; review workflow metadata is not | `SIMPLIFY` | `ToolsController::behaviorReferenceData()` |
| `Medication`, `MedicationFormulation`, `MedicationJurisdiction` | Genuine medication domain data | Persistent medication reference, formulation, registration, and label context | Medication tool and Medication Filament resource | Yes | `PRESERVE` | Existing medication tables and models |
| `ClinicalSafetyBoundary` | Application behavior | Emergency, poisoning, dosage, and diagnosis escalation | Assistant controllers | Yes | `PRESERVE` | `app/Support/ClinicalSafetyBoundary.php` |
| Tool datasets and public tool routes | Controller-owned application data/behavior | Symptom, emergency, vaccination, breed, behavior, checklist, and related guidance | `ToolsController` and public views | Yes | `PRESERVE` | `ToolsController`, `ToolData`, and existing views/routes |
| FAQ, Guide, Knowledge Article, Testimonial, Gallery, Job, Product, and business records | Database-backed content/business data | Existing editorial, catalogue, and business workflows | Public pages, search, sitemap, Filament | Yes | `PRESERVE` | Existing models, repositories/actions, and tables |

## Before/after ownership summary

| Before | After |
| --- | --- |
| Guides and Knowledge Articles needed both their normal editorial publication fields and an optional clinical governance record. | Their existing database-backed editorial fields are authoritative for publication, search, and sitemap eligibility. |
| Medication visibility depended on `ClinicalContent`; medication dose display also depended on that governance record. | Medication records remain persistent domain data. Dose display remains explicitly disabled unless the formulation enables it and has dose data. Patient-specific advice still escalates to a veterinarian. |
| Tool behavior data carried review status, CMS replacement, and clinical-review markers. | Tool advice and escalation rules remain controller-owned. Governance-only metadata is gone. |
| Admin users could receive a clinical-review role and governance work queues. | The admin panel retains ordinary editorial, business, catalogue, and medication workflows only. |
| Search could be changed by a governance observer. | Search continues to follow the existing editorial model observer and synchronizer paths. |

## Counts

- Governance datasets removed: **6** (`ClinicalContent`, `ClinicalContentVersion`, `ClinicalReview`, `ClinicalSource`, `ClinicalToolReview`, and their pivot).
- Governance integrations simplified: **5** (Guide, Knowledge Article, Medication, medication dose gating, and controller tool metadata).
- Genuine medication datasets retained: **3** (`medications`, `medication_formulations`, `medication_jurisdictions`).
- Genuine shared clinical-governance datasets retained: **0**.
- Existing database-backed editorial/business datasets preserved: **7 families** (FAQ, Guide, Knowledge Article, Testimonial, Gallery, Job Opening, Product) plus business and operational records.
- Public tool routes preserved: **all existing tool routes**, including symptom checker, new-pet checklist, vaccination schedule, emergency guide, breed finder, and behavior tips.
- Schema migrations added for removal: **1**. No new tables were introduced.

## Ambiguous cases and evidence

1. Medication data has clinical terminology and dose fields, but it is a genuine persistent medication domain already used by the medication tool and admin resource. The tables and safety fields were therefore preserved; only the unsupported governance foreign key and publication gate were removed.
2. `Guide` and `KnowledgeArticle` can contain health-related writing, but they already have complete normal editorial publication, search, and sitemap fields and existing CMS workflows. No consumer required clinician approval records, so their second governance relationship was removed.
3. Tool payloads contained review markers, but the public tools have explicit safety and escalation behavior in code and no demonstrated review workflow. The markers were removed while keeping the advice, warnings, and veterinary escalation paths.
4. The assistant's clinical-safety boundary is not editorial governance. It is request-time decision behavior, so it remains unchanged.

## Removed

- Clinical governance models, enums, policies, workflow action, publication gate, observer, admin resources, widgets, and governance-only tests.
- Governance tables and the medication/user governance columns through the forward schema-removal migration.
- Governance-only relationships and search/publication coupling.

## Preserved

- Medication models and their formulation/jurisdiction relationships.
- Medication warnings and fail-closed dose display behavior.
- `ClinicalSafetyBoundary` and assistant/controller escalation behavior.
- Controller-owned tool data and all public tool URLs.
- Existing database-backed editorial, catalogue, business, search, sitemap, canonical, media, and Filament behavior.

## Simplified

- Normal Guide and Knowledge Article publication now directly determines indexability and sitemap eligibility.
- Medication queries no longer require a governance record.
- Behavior tool data no longer carries review/CMS workflow metadata.

## Remaining clinical code

The remaining clinical terminology is limited to legitimate application safety or domain language: emergency escalation, veterinary advice, medication warnings, and non-diagnostic tool guidance. There is no remaining runtime model, table, policy, workflow, observer, or admin resource for clinician editorial governance.
