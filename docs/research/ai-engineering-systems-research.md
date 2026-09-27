# Independent Research: Which AI Software Engineering System Should I Adopt?

**Research snapshot:** 27 September 2026 (Africa/Lagos)  
**Question:** For a solo developer or very small team building and maintaining a serious product over many months or years, which AI-assisted software engineering system provides the most complete, coherent, reliable, and practical end-to-end workflow?

## Executive summary

### Main finding

**GSD Core is the strongest documented single-system fit for the stated problem.** Its official materials connect a repeatable `Discuss → Plan → Execute → Verify → Ship` loop to repository-persisted project state, fresh-context subagents, explicit greenfield and brownfield entry points, verification/UAT, and shipping artifacts. That combination is unusually complete for a small team that needs continuity across sessions and phases. See the [GSD Core README](https://github.com/open-gsd/gsd-core/blob/main/README.md), [command reference](https://github.com/open-gsd/gsd-core/blob/main/docs/COMMANDS.md), and [phase-loop documentation](https://github.com/open-gsd/gsd-core/blob/next/docs/explanation/the-phase-loop.md).

That is a capability judgment, not a claim that GSD is easiest or that it produces correct code automatically. Its process is comparatively heavy, its current repository has a moving `next` branch, and its value depends on committing and maintaining `.planning/` artifacts.

### The more useful answer is a set of leaders

| Need | Best fit from the evidence | Why | Main tradeoff |
| --- | --- | --- | --- |
| One primary project operating system | **GSD Core** | Best documented continuity, phase state, verification, and shipping loop | Ceremony and artifact discipline |
| Product discovery, UX, architecture, and progressive planning | **BMAD-METHOD** | Richest role-specialized upstream workflow and project sizing | More workflow complexity; current review-path caveats |
| Lightweight brownfield change management | **OpenSpec** | Delta specs, focused change folders, sync/archive, broad adapters | Not a complete product roadmap, TDD, or release system |
| Engineering/TDD discipline | **Superpowers** | Strongest explicit TDD, task review, worktree, debugging, and branch-finishing workflow | Not a durable roadmap or requirements-management system |
| Composable engineering fundamentals | **Matt Pocock’s Skills** | Strong domain modeling, ADRs, TDD, research, implementation, and two-axis review | Deliberately does not own one end-to-end lifecycle |
| Managed all-in-one development surface | **Kiro** | Persistent steering, specs, hooks, subagents, checkpoints, and IDE/CLI/Web surfaces | Platform lock-in and weaker portability |
| Broadest ambitious lifecycle framework | **AIWG** | Explicit stage gates, traceability, specialist review panels, and provider adapters | Highest complexity and lowest evidence of low-friction adoption |
| Multi-agent project governance | **Spec Kitty** | Work-package lanes, isolated worktrees, review/accept/merge, and repo-native state | Current maturity/prerelease risk and high ceremony |

### Recommended default

For a serious long-running product, start with **GSD Core as the primary system**, use its state artifacts as the source of truth, and add only one specialist layer if a gap is proven. The safest first layer is **Superpowers** for implementation/TDD/review discipline. If GSD feels too heavy for an existing Laravel application, use **OpenSpec + Superpowers** instead. If upstream product and UX discovery are the dominant risk, use **BMAD** as the primary system and avoid adding another planning framework initially.

The evidence does **not** support installing all of these systems together. Their planning, review, task, state, and learning artifacts overlap.

## Methodology

### Candidate discovery and filtering

The named candidates were investigated first, then compared with serious alternatives discovered in the same category space:

- **Spec Kitty**: a repo-native specification and multi-agent work-package operating system.
- **gstack**: a Claude-first product-builder workflow with product review, browser QA, shipping, and retrospectives.
- **OpenAI Harness Engineering**: a methodology and repository-harness design reference, not a drop-in framework.
- **OpenAI Symphony**: an issue-tracker-driven agent scheduler, not a product-development methodology.

OpenHands, Plandex, and standalone Ralph loops were not ranked as peer methodologies. OpenHands is primarily an agent runtime/control surface; Plandex is primarily a coding agent and plan/diff sandbox; Ralph loops provide iterative execution rather than a complete discovery-to-release lifecycle. They remain useful adjacent tools, but comparing them as full software-development systems would be category error.

### Evidence standard

The research used official repositories, first-party documentation, workflow and skill files, templates, release pages, and official issue/changelog material. “Supported” means the project explicitly documents the integration. A technically possible use is not counted as first-class support. Where the official evidence does not establish a capability, this report says **Evidence insufficient** or marks it `U` rather than silently converting absence of documentation into `No`.

Current status was checked on 27 September 2026. The GitHub repository and release pages are linked in the status table below. GitHub stars were not used as a quality proxy.

### Scoring method

Scores use a 0–5 scale and are judgments derived from the documented workflows, artifacts, and operational caveats—not empirical measurements of generated-code quality. The score is intended to make assumptions visible, not to create false precision.

For overall capability:

| Criterion | Weight | Why it matters |
| --- | ---: | --- |
| End-to-end lifecycle coverage | 15 | The question asks for more than code generation. |
| Product, requirements, discovery, and UX | 10 | Upstream ambiguity is a major source of downstream rework. |
| Domain, architecture, and schema support | 10 | Serious products need explicit boundaries and decisions. |
| Persistent context, state, and continuity | 15 | Months-long work crosses sessions and context windows. |
| Brownfield, change, and evolution handling | 10 | Real products revise assumptions and existing code. |
| Implementation, testing, and TDD | 10 | Plans matter only if implementation quality is controlled. |
| Verification, review, debugging, and release | 10 | Completion must be independently checked and shippable. |
| Orchestration and agent portability | 8 | Small teams may switch hosts or use several agents. |
| Human control and safety | 5 | Autonomy must remain interruptible and reviewable. |
| Adoption, maintenance, documentation, and maturity | 7 | A theoretically complete system that is abandoned will fail in practice. |
| **Total** | **100** | Scores are weighted as `weight × raw score / 5`. |

The practical-adoption ranking uses a different model: adoption/maintenance 20, incremental brownfield fit 15, engineering quality 15, human control 10, portability 10, continuity 15, upstream fit 5, and ceremony-to-value ratio 10. This is why a lighter skill system can outrank a more complete operating system in the second ranking.

### Limitations

Documentation cannot establish actual generated-code correctness, real developer throughput, long-term artifact hygiene, or the quality of a system’s model-specific behavior. Those require a controlled hands-on benchmark; a proposed protocol appears near the end of this report. Rapidly changing repositories also mean a release pin and a local pilot matter more than a static ranking.

## Candidate overview

The table includes the 11 serious candidates used in the deep comparison. Release dates are the latest meaningful release visible on the snapshot date; where a project has no conventional release, the repository state is described instead.

| Rank | Candidate | Category | Lifecycle coverage | Core strength | Core weakness | Current status as of 2026-09-27 |
| ---: | --- | --- | --- | --- | --- | --- |
| 1 | **GSD Core** | Project operating system / planning-state system | High | Persistent phase loop, fresh contexts, verify/UAT, ship | Heavy process and moving successor surface | Active successor at [`open-gsd/gsd-core`](https://github.com/open-gsd/gsd-core); v1.15.0 released 26 Sep 2026; default branch `next`. The former [`gsd-build/get-shit-done`](https://github.com/gsd-build/get-shit-done) is archived/moved. |
| 2 | **BMAD-METHOD** | Full lifecycle methodology / agent workflow | High | Product-to-architecture-to-story workflow and specialized roles | Review-state caveat and considerable ceremony | Active at [`bmad-code-org/BMAD-METHOD`](https://github.com/bmad-code-org/BMAD-METHOD); v6.12.0 released 4 Sep 2026. |
| 3 | **AIWG** | Full SDLC framework / provider adapter | High on paper | Stage gates, traceability, specialist panels, broad adapters | Large, optional, and maintenance-heavy | Active at [`jmagly/aiwg`](https://github.com/jmagly/aiwg); v2026.9.23 released 27 Sep 2026. |
| 4 | **Kiro** | IDE-native development system | High inside Kiro | Integrated specs, steering, hooks, subagents, sessions | Proprietary platform boundary and portability cost | Active first-party product/docs at [`kiro.dev`](https://kiro.dev/docs/); no conventional GitHub release used for the product. |
| 5 | **Spec Kitty** | Project operating system / multi-agent governance | Medium-high | Work-package state, isolated worktrees, review/accept/merge | Young, high ceremony, Team Kitty maturity risk | Active at [`spec-kitty/spec-kitty`](https://github.com/spec-kitty/spec-kitty); v3.2.7 released 9 Sep 2026; current Team Kitty 4.x work is not the same maturity as the stable CLI. |
| 7 | **GitHub Spec Kit** | Specification framework / process harness | Medium-high | Portable, extensible SDD plus bugfix and idea flows | Not a complete roadmap, state, or release OS | Active at [`github/spec-kit`](https://github.com/github/spec-kit); v1.0.12 released 25 Sep 2026. |
| 8 | **Compound Engineering** | Engineering workflow / knowledge-compounding plugin | Medium | Plan-work-review-compound loop, PR and learning support | Weak formal product roadmap and requirements model | Active at [`EveryInc/compound-engineering-plugin`](https://github.com/EveryInc/compound-engineering-plugin); v3.29.0 released 25 Sep 2026. |
| 9 | **gstack** | Product-builder workflow / specialist tool | Medium | Product judgment, browser QA, shipping, retrospectives | Claude-first and not a proven multi-year project OS | Active at [`garrytan/gstack`](https://github.com/garrytan/gstack); pushed 26 Sep 2026; no conventional release found. |
| 10 | **OpenSpec** | Brownfield specification and change system | Medium | Focused delta specs and sync/archive | No default TDD, roadmap, or release lifecycle | Active at [`Fission-AI/OpenSpec`](https://github.com/Fission-AI/OpenSpec); v1.13.2 released 23 Sep 2026. |
| 11 | **Superpowers** | Coding methodology / engineering workflow | Medium-low upstream, high implementation | TDD, task review, debugging, worktrees, branch completion | No durable product backlog or roadmap model | Active at [`obra/superpowers`](https://github.com/obra/superpowers); v6.4.2 released 25 Sep 2026. |
| 6 | **Matt Pocock’s Skills** | Composable engineering skill library | Medium-low as a single system | Domain modeling, ADRs, research, TDD, implementation, review | Intentionally does not own one lifecycle | Active at [`mattpocock/skills`](https://github.com/mattpocock/skills); v1.2.3 released 6 Aug 2026; native Codex plugin remains a roadmap item. |

## What each system actually is

### GSD Core

**Basic unit:** milestone, phase, command, and persisted artifact.  
**Control model:** orchestrator-driven, with human discussion/checkpoints and fresh-context subagents.  
**Persists:** `.planning/PROJECT.md`, `REQUIREMENTS.md`, `ROADMAP.md`, `STATE.md`, phase `CONTEXT.md`, research, plans, summaries, verification, and ship/archive records.  
**Actual flow:**

```text
/gsd-new-project or /gsd-onboard
  → Discuss phase context
  → Research and Plan
  → Execute in waves with fresh contexts
  → Verify requirements/UAT and diagnose gaps
  → Ship PR/archive and update STATE.md
```

The [phase-loop documentation](https://github.com/open-gsd/gsd-core/blob/next/docs/explanation/the-phase-loop.md) and [onboarding guide](https://github.com/open-gsd/gsd-core/blob/next/docs/tutorials/onboarding-an-existing-codebase.md) make this more than README language. Verification has an explicit insufficiency path rather than silently treating an unverifiable requirement as passing. The main failure mode is operational: the system can become a second project to maintain if every phase is run at maximum ceremony.

### BMAD-METHOD

**Basic unit:** specialized skill/agent, workflow, planning artifact, epic/story, and sprint status.  
**Control model:** user- or router-invoked role workflow, with optional multi-agent discussions and automation.  
**Persists:** product brief/PRD, UX and architecture artifacts, spec, tickets, story files, `sprint-status.yaml`, and a verified project-context block in `AGENTS.md` in current releases.  
**Actual flow:**

```text
idea / change request
  → brainstorm, research, decision, product brief or PRD
  → UX and architecture
  → spec and implementation-readiness review
  → epics/stories/tickets
  → bmad-build per story
  → code review, corrective course, retrospective
```

The [planning-path guide](https://docs.bmad-method.org/plan/choose-a-planning-path/) explicitly scales from a direct build of a well-defined fix to a full project workflow. The [existing-codebase guide](https://docs.bmad-method.org/existing-codebases/start-in-an-existing-codebase/) says code is the source of truth and offers a smaller direct-build path. The official issue tracker documents a real weakness: overlapping build/review layers can repeat work, and a story may remain in `review` if the separate review step is skipped ([issue #2760](https://github.com/bmad-code-org/BMAD-METHOD/issues/2760)).

### AIWG

**Basic unit:** provider deployment, agent, skill, rule, template, command, phase gate, and `.aiwg` evidence artifact.  
**Control model:** orchestrator/provider adapter with panels, phase gates, and optional long-running loops.  
**Persists:** `.aiwg/`, `WORKSPACE.md`, `AIWG.md`, `AGENTS.md`, requirements, architecture decisions, test strategies, risk registers, deployment plans, traces, reports, sessions, and indexes.  
**Actual flow:**

```text
intent
  → discover canonical skill/playbook
  → Inception → Elaboration → Construction → Transition → Production
  → specialist review panels and traceability
  → execute → verify → learn → iterate
```

The [SDLC Complete framework](https://github.com/jmagly/aiwg/tree/main/agentic/code/frameworks/sdlc-complete) and [orchestrator architecture](https://github.com/jmagly/aiwg/blob/main/agentic/code/frameworks/sdlc-complete/docs/orchestrator-architecture.md) support the breadth claim. The project’s own documentation also says saved artifacts do not guarantee later use, reviewers can share the same error, unattended loops are not guaranteed to finish, and optional services have independent prerequisites. AIWG therefore scores highly for explicit coverage but lower for adoption confidence.

### Kiro

**Basic unit:** IDE/CLI/Web session, steering file, spec, hook, task, checkpoint, and subagent.  
**Control model:** managed platform with agent autonomy, user checkpoints, permissions, hooks, and parallel task waves.  
**Persists:** `.kiro/steering/`, feature or bugfix specs, `design.md`, `tasks.md`, checkpoints, sessions, cloud memory, and project configuration.  
**Actual flow:**

```text
steering context
  → feature requirements or bug root-cause analysis
  → design
  → tasks and dependency waves
  → implementation through IDE/CLI/Web agents and hooks
  → correctness analysis, tests, checkpoint/rewind
```

The [specs documentation](https://kiro.dev/docs/specs/) and [steering documentation](https://kiro.dev/docs/steering/) substantiate the flow. Kiro is arguably the strongest integrated product surface, but it is not a repo-neutral methodology. Its docs also state that compaction is one-way and checkpoints do not track edits made outside Kiro, including shell or MCP changes. Custom agents must explicitly load steering resources.

### Spec Kitty

**Basic unit:** specification, work package, lane, isolated worktree, review, acceptance, and merge.  
**Control model:** project orchestrator with human governance and multi-agent work packages.  
**Persists:** `kitty-specs/`, work-package status, plans, tasks, reviews, acceptance records, retrospectives, and local dashboard state.  
**Actual flow:**

```text
specify → plan → tasks → implement in isolated work packages
  → review → accept → merge → retrospective
```

The [official README](https://github.com/spec-kitty/spec-kitty) documents local-first operation, lanes, isolated worktrees, and human review. It is a strong candidate for a small team coordinating several agents, but current Team Kitty 4.x work and stable CLI support should be treated as separate maturity levels. It is explicitly overkill for tiny edits.

### GitHub Spec Kit

**Basic unit:** constitution, feature specification, plan, task list, and convergence analysis.  
**Control model:** artifact-driven and customizable; the user can require or skip workflow stages.  
**Persists:** `.specify/`, feature specs, plans, task files, constitution, and project-selected spec-aging model.  
**Actual flow:**

```text
constitution / clarify
  → Specify
  → Plan
  → Tasks
  → Implement
  → Analyze / Converge
```

The [workflow reference](https://github.com/github/spec-kit/blob/main/docs/reference/workflows.md) shows review gates and customizable workflows. The [existing-project guide](https://github.github.io/spec-kit/guides/existing-projects.html) explicitly warns that initialization does not infer the existing system and asks the team to decide whether specifications are immutable history, living contracts, or reconciled artifacts. Spec Kit is a specification harness, not automatically a roadmap, ADR, release, or issue-tracker system.

### OpenSpec

**Basic unit:** change proposal, delta spec, design, task, and archived change.  
**Control model:** user-driven artifact workflow with optional expanded commands and human review before application.  
**Persists:** `openspec/specs/`, change folders, `proposal.md`, `design.md`, `tasks.md`, and archived/synced deltas.  
**Actual flow:**

```text
/opsx:explore
  → /opsx:propose
  → review proposal/spec/design/tasks
  → /opsx:apply
  → /opsx:sync
  → /opsx:archive
```

The [workflow guide](https://github.com/Fission-AI/OpenSpec/blob/main/docs/workflows.md) and [existing-project guide](https://github.com/Fission-AI/OpenSpec/blob/main/docs/existing-projects.md) show why it is strong for brownfield evolution: document the slice being changed, use ADDED/MODIFIED/REMOVED scenarios, and let the corpus grow with real changes. The default `core` profile does not include every verification/onboarding step, so a serious team must explicitly select and enforce its quality profile.

### Compound Engineering

**Basic unit:** skill, plan, work session, review, and solution/learning record.  
**Control model:** plugin workflow with optional autonomous `lfg` pipeline and multiple reviewers.  
**Persists:** `docs/plans/`, `docs/solutions/`, configuration, packs, handoffs, commits, and PR artifacts.  
**Actual flow:**

```text
brainstorm → plan → work → simplify → code review → compound learning
```

The [official README](https://github.com/EveryInc/compound-engineering-plugin) and [guide index](https://github.com/EveryInc/compound-engineering-plugin/blob/main/docs/guides/README.md) show a strong implementation/review/learning loop. Its evidence for a canonical multi-milestone roadmap, formal requirements corpus, or ADR lifecycle is insufficient. It is best understood as a high-quality secondary engineering layer.

### Superpowers

**Basic unit:** skill, design document, implementation plan, bite-size task, worktree, subagent, test, and review.  
**Control model:** automatic skill triggering with hard human gates before design and implementation, then subagent-driven execution.  
**Persists:** design docs and plans in `docs/superpowers/`, worktree/ledger records, branch commits, and review findings.  
**Actual flow:**

```text
brainstorm and approve design
  → create worktree
  → write reviewed implementation plan
  → execute bite-size tasks with TDD
  → per-task and final review
  → finish branch, test, merge/PR/keep/discard
```

The [README](https://github.com/obra/superpowers/blob/main/README.md), [brainstorming skill](https://github.com/obra/superpowers/blob/main/skills/brainstorming/SKILL.md), and [subagent-driven-development skill](https://github.com/obra/superpowers/blob/main/skills/subagent-driven-development/SKILL.md) make the quality discipline concrete. Superpowers is not weak; it solves a narrower problem. It does not own the product backlog, long-lived requirements, roadmap, or cross-milestone state.

### Matt Pocock’s Skills

**Basic unit:** composable skill, setup choice, issue/ticket, context document, ADR, plan, and code-review pass.  
**Control model:** deliberately user-invoked/router-driven and model-invoked skills, with no mandatory central orchestrator.  
**Persists:** `CONTEXT.md`, ADRs, specs, tickets, issue-tracker records, research Markdown, and implementation/review artifacts.  
**Actual flow:**

```text
setup issue tracker and document location
  → grill / domain model / research / to-spec
  → to-tickets
  → implement with TDD and review
```

The [skills catalog](https://github.com/mattpocock/skills) explicitly describes the system as small, adaptable, and composable. The [implement skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/implement/SKILL.md), [TDD skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/tdd/SKILL.md), and [code-review skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/code-review/SKILL.md) are strong. Its limitation is intentional: the user must supply the project’s overall operating model.

### gstack

**Basic unit:** product-review role, command/skill, browser QA session, shipping check, and learning.  
**Control model:** Claude-first role workflow with human review and optional external-agent calls.  
**Persists:** project/global state, design/spec documents, learnings, and optional private-git memory sync.  
**Actual flow:**

```text
office hours / CEO / design / engineering review
  → build
  → code and browser review
  → security and QA
  → ship/deploy/canary
  → retro and learning
```

The [gstack README](https://github.com/garrytan/gstack) documents this product-delivery loop. Its product and browser/release depth is compelling, but evidence is insufficient that it provides the same formal multi-year requirements and phase-state model as GSD or BMAD. Windows setup and external-review prerequisites add operational friction.

## Full capability comparison

Legend: **Y** = explicitly supported in the documented workflow; **P** = partial, optional, or dependent on user/extension setup; **—** = not a core capability; **U** = evidence insufficient. These are not claims that a system cannot perform the task at all.

Abbreviations: **BM** BMAD, **MP** Matt Pocock Skills, **SP** Superpowers, **GS** GSD Core, **SK** Spec Kit, **OS** OpenSpec, **CE** Compound Engineering, **KI** Kiro, **AW** AIWG, **KT** Spec Kitty, **GT** gstack.

| # | Capability | BM | MP | SP | GS | SK | OS | CE | KI | AW | KT | GT |
| ---: | --- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| 1 | Product discovery | Y | P | P | P | P | — | P | P | Y | P | Y |
| 2 | Requirements discovery | Y | P | P | Y | Y | P | P | Y | Y | Y | Y |
| 3 | Requirements management | Y | P | P | Y | Y | Y | P | P | Y | Y | P |
| 4 | Domain modeling | Y | Y | P | P | P | P | P | P | Y | P | P |
| 5 | Research | Y | Y | P | Y | P | P | Y | P | Y | P | P |
| 6 | Architecture | Y | Y | P | Y | Y | P | P | Y | Y | Y | P |
| 7 | Database/schema design | P | P | P | Y | Y | P | P | P | Y | P | P |
| 8 | UX/design thinking | Y | P | P | P | P | — | P | P | P | P | Y |
| 9 | Specification | Y | Y | Y | Y | Y | Y | P | Y | Y | Y | P |
| 10 | Roadmapping | Y | P | — | Y | — | — | — | — | Y | P | P |
| 11 | Task/ticket decomposition | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y |
| 12 | Implementation | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y |
| 13 | Testing | Y | Y | Y | Y | P | P | Y | Y | Y | Y | Y |
| 14 | TDD | P | Y | Y | P | P | — | P | P | P | P | P |
| 15 | Code review | Y | Y | Y | P | P | — | Y | P | Y | Y | Y |
| 16 | Debugging | Y | Y | Y | Y | P | P | Y | P | Y | P | Y |
| 17 | Refactoring | P | Y | Y | P | P | P | Y | P | P | P | Y |
| 18 | Documentation | Y | Y | P | Y | P | P | Y | P | Y | P | Y |
| 19 | ADRs | P | Y | — | P | — | — | — | — | Y | P | P |
| 20 | Persistent project context | Y | Y | P | Y | Y | Y | P | Y | Y | Y | Y |
| 21 | Cross-session continuity | P | P | P | Y | P | P | P | Y | Y | Y | P |
| 22 | State management | P | P | P | Y | P | P | P | P | Y | Y | P |
| 23 | Large-project/phase management | Y | P | — | Y | P | — | — | P | Y | Y | P |
| 24 | Brownfield support | Y | Y | P | Y | Y | Y | Y | Y | Y | Y | Y |
| 25 | Greenfield support | Y | U | P | Y | Y | P | P | Y | Y | Y | P |
| 26 | Multi-agent orchestration | Y | P | Y | Y | P | P | Y | Y | Y | Y | P |
| 27 | Human approvals/checkpoints | Y | Y | Y | Y | Y | Y | P | Y | Y | Y | Y |
| 28 | Verification/validation | Y | Y | Y | Y | Y | P | Y | Y | Y | Y | Y |
| 29 | Git workflow | P | P | Y | Y | P | P | Y | P | Y | Y | Y |
| 30 | PR/code-review workflow | P | P | Y | P | P | — | Y | P | P | Y | Y |
| 31 | Release/shipping workflow | P | — | Y | Y | — | — | P | P | Y | P | Y |
| 32 | Session/agent handoffs | P | P | Y | Y | P | P | Y | Y | Y | Y | P |
| 33 | Changing requirements | Y | Y | P | Y | Y | Y | P | P | Y | Y | Y |
| 34 | Contradiction detection | P | P | P | Y | P | P | P | P | Y | P | P |
| 35 | Architectural drift prevention | Y | Y | P | Y | P | P | P | P | Y | Y | P |
| 36 | Context-loss prevention | P | P | P | Y | P | P | P | P | Y | Y | P |
| 37 | Incremental development | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y |
| 38 | Existing-codebase conventions | Y | Y | P | Y | P | Y | Y | Y | Y | Y | Y |
| 39 | Tool/IDE/CLI integration | Y | P | Y | Y | Y | Y | Y | Y | Y | Y | P |
| 40 | Extensibility/customization | Y | Y | Y | Y | Y | P | Y | Y | Y | P | P |
| 41 | Portability across agents | Y | Y | Y | Y | Y | Y | Y | — | Y | Y | P |
| 42 | Ease of adoption | P | Y | Y | P | P | Y | P | P | — | P | P |
| 43 | Maintenance burden | P | Y | Y | P | P | P | P | P | — | — | P |
| 44 | Documentation quality | Y | Y | Y | Y | Y | Y | Y | Y | P | P | P |
| 45 | Community/ecosystem maturity | Y | Y | Y | Y | Y | Y | Y | P | P | P | P |

The table is deliberately conservative. For example, a generic Markdown file could store an ADR for any system, but that earns `Y` only when ADRs are an explicit part of the documented workflow. Likewise, a system can technically run a test without being a TDD methodology.

## Documentation and project-memory comparison

| Candidate | Project context | Requirements | Roadmap | Specs | ADRs | State | Research | Tasks | Session handoff | Automatic updates |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| GSD Core | Yes: `.planning/PROJECT.md`, codebase map | Yes | Yes: `ROADMAP.md` | Yes: phase plans/context | Partial: decisions in phase/state artifacts | Yes: `STATE.md` | Yes: research artifacts | Yes | Yes: summaries and state | Partial: commands refresh/validate; not magic |
| BMAD | Yes: verified `AGENTS.md` project context | Yes: PRD/spec/story | Yes: epics/stories/sprint status | Yes | Partial: architecture/decision artifacts, not a universal ADR spine | Partial: sprint status/story state | Yes | Yes | Partial | Partial: workflow-generated, must be invoked correctly |
| Matt Skills | Yes: `CONTEXT.md` and setup | Partial: issue tracker/local docs | Partial: issue tracker | Yes | Yes | Partial | Yes: cited research Markdown | Yes | Partial: handoff/productivity skills | Partial: managed Claude plugin auto-updates; other installs manual |
| Superpowers | Partial: design/plan artifacts | Partial | No core roadmap | Yes: design and plans | No core ADR workflow | Partial: ledger/worktree/branch | Partial | Yes | Partial | No general auto-update model |
| Spec Kit | Yes: constitution and project docs | Yes | No core roadmap | Yes | No core ADR workflow | Partial: artifact/checklist status | Partial | Yes | Partial | Partial: CLI/integration updates, not project-state updates |
| OpenSpec | Partial: repo-native `openspec/` corpus | Yes: delta specs | No | Yes | No formal ADR spine | Partial: change status/archive | Partial: explore | Yes | Partial: change folders | Partial: managed skill update; telemetry/updates are operational choices |
| Compound Engineering | Partial: repo config and solution corpus | Partial | No core roadmap | Partial | No core ADR workflow | Partial | Yes | Yes | Yes: handoff/PR artifacts | Partial: marketplace/plugin updates |
| Kiro | Yes: `.kiro/steering/` and global/workspace context | Yes | No explicit roadmap system | Yes: feature/bugfix specs | No formal ADR spine | Yes: sessions/checkpoints | Partial | Yes | Yes: sessions/checkpoints/cloud | Yes inside managed product, subject to platform limits |
| AIWG | Yes: `.aiwg/`, `WORKSPACE.md`, `AIWG.md` | Yes | Yes | Yes | Yes | Yes | Yes | Yes | Yes | Partial: indexing/deployment refresh, not guaranteed later use |
| Spec Kitty | Yes: repo-native specs and work-package state | Yes | Partial | Yes | Partial | Yes: lanes/status/acceptance | Partial | Yes | Yes | Partial: generated state, not autonomous truth maintenance |
| gstack | Partial: project/global state | Partial | Partial | Partial | Partial | Yes | Partial | Yes | Yes | Partial: memory sync/skill updates |

“Automatic updates” means automatic maintenance of project knowledge, not merely that a package can update itself. None of these systems should be assumed to keep all dependent documents semantically synchronized without verification.

## Product → architecture → code comparison

| Candidate | Product/discovery depth | Architecture/domain depth | Code/execution depth | Practical interpretation |
| --- | --- | --- | --- | --- |
| BMAD | **High** | **High** | High | Best upstream-to-downstream role workflow. |
| AIWG | High | High | High | Broadest formal surface, but optional complexity is substantial. |
| GSD Core | Medium | High | **High** | Strong project continuity; product discovery is less specialized than BMAD. |
| Kiro | Medium-high | High | High | Strong integrated feature/bugfix spec loop inside Kiro. |
| Spec Kitty | Medium | High | High | Strong governance and execution coordination; less product discovery. |
| Spec Kit | Medium | Medium-high | Medium-high | Specification-first, with extensions carrying the rest. |
| gstack | **High** | Medium | High | Strong product judgment and shipping, less formal long-term state. |
| Matt Skills | Medium | **High** | **High** | Excellent engineering reasoning, but the user supplies the operating model. |
| OpenSpec | Low-medium | Medium | Medium | Focused change contract for existing systems. |
| Compound Engineering | Medium | Medium | **High** | Strong delivery/review loop, weak formal upstream ownership. |
| Superpowers | Low-medium | Medium | **Very high** | Best treated as the implementation layer after upstream decisions exist. |

## Brownfield versus greenfield

| Candidate | Greenfield | Brownfield | How change evolves | Main risk |
| --- | --- | --- | --- | --- |
| GSD Core | Strong: `/gsd-new-project` creates project requirements/roadmap/state | Strong: `/gsd-onboard` maps codebase, conventions, tests, integrations, and concerns | Phase context → plan → verify; state survives clearing | Over-documenting small changes |
| BMAD | Strong: progressive analysis/planning/solutioning | Strong: direct build for small work; context scan and spec path for larger work | Stories, status, corrective course, retrospectives | Wrong workflow invocation can strand review state |
| AIWG | Strong in framework model | Strong in stated lifecycle and traceability | Stage gates and artifact links | Optional subsystems create ambiguity about the active source of truth |
| Kiro | Strong feature-spec path | Strong bugfix/spec/steering path | Update steering/specs/tasks, then hooks/checkpoints | External edits may escape checkpoints |
| Spec Kitty | Strong but ceremony-heavy | Strong repository/work-package governance | Work-package lanes, review, acceptance, merge | Too much machinery for a single developer |
| Spec Kit | Strong SDD path | Explicit existing-project initialization and bounded first change | Team chooses immutable/living/reconciled spec-aging model | Existing project is not inferred automatically |
| OpenSpec | Adequate for a new change corpus | **Best fit**: focused deltas instead of reverse-specifying everything | ADDED/MODIFIED/REMOVED deltas sync into main specs | Default profile is too light for high assurance |
| Compound | Evidence insufficient for full greenfield lifecycle | Good plugin in existing repos | Plan/work/review/compound solution records | No canonical roadmap/source of truth |
| gstack | Good for product sprint flow | Good for existing product/review/QA work | State, learnings, retrospectives | Formal architecture and roadmap evidence is thinner |
| Superpowers | Usable, but upstream decisions remain manual | Usable, with explicit caution around TDD/migration | Design/plan → TDD tasks → review → branch finish | It can delete/rewrite pre-test code by design |
| Matt Skills | Evidence insufficient as a prescriptive greenfield method | Strong for interrogating and improving an existing codebase | Context/ADR/spec/tickets evolve through explicit skills | User must maintain orchestration and tracker setup |

## Solo developer versus small team

| Candidate | Solo developer | Small team | Why |
| --- | --- | --- | --- |
| GSD Core | Strong if phases are pruned | Strong | State, handoffs, and review reduce dependency on one person’s memory. |
| BMAD | Moderate-high | Strong | Roles and gates clarify upstream decisions, but can feel like simulating a larger organization. |
| AIWG | Low-medium | Medium-high | Panels and traceability help teams, but operating the framework is itself work. |
| Kiro | Strong if everyone accepts Kiro | Medium | Integrated surface is efficient; cross-tool team portability is weak. |
| Spec Kitty | Low for tiny work | High for governed parallel work | Work packages, lanes, and acceptance are useful when concurrency is real. |
| Spec Kit | Medium-high | High | Portable artifacts and customizable workflow work across teams. |
| OpenSpec | **High for brownfield** | High | Small focused change contracts are easy to review and merge. |
| Compound | High as a layer | Medium-high | Good PR/review/learning loop; not a full shared roadmap. |
| gstack | High for a product-minded solo builder | Medium | Product/QA/shipping roles help a solo developer; shared-state evidence is thinner. |
| Superpowers | **High for implementation** | High | TDD and reviews scale across agents, but product management remains external. |
| Matt Skills | **High for a disciplined engineer** | Medium | Flexible and low ceremony; team-level consistency must be established separately. |

## AI-agent compatibility

Legend: **O** = explicitly documented official integration/adapter; **A** = official generic/project-file adapter or less first-class path; **T** = technically possible or legacy/unclear, not counted as first-class; **—** = not applicable or not supported as a native target; **U** = evidence insufficient.

| Candidate | Claude Code | Codex | Cursor | Windsurf / Devin | Gemini CLI | Cline | Roo Code | GitHub Copilot | Other documented targets |
| --- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | --- |
| BMAD | O | O | O | O | O | O | U | O | Claude, Codex, Cursor, Copilot, Gemini, Windsurf, Cline and others via installer/add-ons |
| Matt Skills | O | A | A | A | A | A | A | A | Generic `npx skills`; Linear/GitHub/local tracker setup |
| Superpowers | O | O | O | A | O | A | A | O | Antigravity, Devin, Factory Droid, Kimi, OpenCode, Pi, Qwen, Hermes, Muse |
| GSD Core | O | O | O | O | T | A | A | O | OpenCode, Kimi, Kilo, Antigravity; Gemini CLI was removed in 2026 |
| Spec Kit | O | O | O | T | O | O | A | O | 38 integrations and generic fallback |
| OpenSpec | O | O | O | O† | O | O | O | O | Kiro, OpenCode, Pi, Roo, Zed and many adapters; †legacy `.windsurf`/Devin naming caveat |
| Compound Engineering | O | O | O | A | A | O | A | O | Kimi, OpenCode, Pi and other hosts |
| Kiro | — | — | — | — | — | — | — | — | Native Kiro IDE, CLI, Web, Mobile, Crew; external agents can run other systems inside Kiro |
| AIWG | O | O | O | O | A | A | A | O | OpenCode, Factory, Warp and other provider adapters |
| Spec Kitty | O | O | O | O | O | A | A | O | OpenCode, Qwen, Kiro, Vibe, Pi, Letta, llxprt |
| gstack | O | O | O | A | A | A | A | A | OpenCode, Factory Droid, Kiro, OpenClaw, Hermes |

Compatibility is not equivalence. A skills adapter may make commands available, but it does not guarantee identical lifecycle hooks, context windows, permission behavior, or post-compaction behavior across hosts.

## Automation versus human control

| Candidate | Automation | Human control | Opinionatedness | Ceremony | Risk of over-process |
| --- | --- | --- | --- | --- | --- |
| GSD Core | Research, planning, waves, verification, diagnosis, summaries, ship artifacts | Discussion, phase context, approvals, UAT, escape hatches | High | High | Medium-high |
| BMAD | Role workflows, elicitation, artifacts, stories, build/review/retro | Chooses planning path and reviews major artifacts | High but progressive | High | Medium-high |
| AIWG | Discovery, deployment, gates, panels, traceability, loops | Human authorization and phase gates | High | Very high | High |
| Kiro | Specs, task waves, hooks, subagents, sessions, checkpoints | Permissions, approvals, checkpoints, rewind | Medium-high | Medium-high | Medium |
| Spec Kitty | Work packages, lanes, isolated worktrees, review/accept/merge | Strong review and acceptance gates | High | High | Medium-high |
| Spec Kit | Artifact scaffolding, clarify/plan/tasks/implement/analyze | Can customize/skip workflows and choose spec-aging model | Medium | Medium-high | Medium |
| OpenSpec | Proposal/spec/design/task scaffolding, apply/sync/archive | Explicit review before apply; optional expanded stages | Medium | Low-medium | Low-medium |
| Compound | Plan/work/review/PR/learning and optional `lfg` | Review, bounded autonomy, PR handoff | Medium | Medium | Medium |
| gstack | Role reviews, browser QA, shipping, canary, retro | Product and release judgment remain human | Medium-high | Medium | Medium |
| Superpowers | Skill routing, subagents, TDD task loop, reviews, branch completion | Hard gates before implementation and before finalization | High | Medium | Low-medium |
| Matt Skills | Research, domain, TDD, implementation, review skills | Strongly user-driven and composable | Low-medium | Low-medium | Low |

OpenSpec, Superpowers, and Matt Skills are the easiest to right-size. GSD and BMAD can also right-size, but the operator must consciously choose smaller paths. AIWG and Spec Kitty should be adopted only when the team is willing to operate their governance surfaces.

## Failure modes and safety observations

| Candidate | Specific failure mode supported by evidence | Consequence | Mitigation |
| --- | --- | --- | --- |
| GSD Core | Artifact and command surface is large; state freshness can become a burden; `--skip-verify` exists | A solo developer may bypass the very continuity/checking that justifies adoption | Commit `.planning/`, keep phases bounded, treat skip flags as exceptional, run a monthly artifact audit |
| BMAD | Official issue #2760 reports repeated review layers and a story stuck in `review` when a separate review step is skipped | Duplicate work or unclear story state | Pin a tested release, choose one review authority, test the exact build/review path on a sample story |
| AIWG | Own docs warn that saved artifacts may not be used later, reviewers can share errors, and unattended loops may not finish | False confidence from a large evidence corpus | Keep phase gates human-owned; start with a narrow subset; do not deploy every optional service |
| Kiro | Compaction is one-way; checkpoints omit external/MCP/shell edits; custom agents may omit steering | Rewind or context recovery can diverge from actual workspace state | Treat Git as the external source of truth; keep steering explicit; avoid mixing external mutation with checkpoint rollback |
| Spec Kitty | High ceremony and active Team Kitty maturity transition | Adoption costs exceed coordination benefit for solo/small features | Pilot one parallel work package; do not use for tiny changes |
| Spec Kit | `--force` initialization can replace managed conflicts; extensions vary in trust/support | Existing project files or workflow assumptions can be overwritten or become inconsistent | Review baseline before init; pin extensions; choose a spec-aging model explicitly |
| OpenSpec | Default core profile omits some onboarding/verification; managed update replaces managed skill bodies; Stores are beta | Quality gates may be assumed but not run; local edits may be lost | Use expanded profile; customize outside managed paths; treat `openspec/` as source of truth and review syncs |
| Compound | Autonomous pipeline can exhaust bounded repair budget; stale marketplace can leave old code installed | Partial completion or upgrade confusion | Keep PR boundaries small; refresh marketplace before updates; inspect leftover work |
| gstack | External review/toolchain dependencies and Windows setup caveats | A workflow step may silently degrade to `not assessed` or fail to run | Verify native toolchain and setup on the target OS before adoption |
| Superpowers | Strong TDD guardrail can delete/rewrite code created before tests; host lifecycle behavior differs | Surprising migration behavior or lost bootstrap after compaction on some hosts | Use a branch/worktree, understand the TDD skill, verify host-specific hooks |
| Matt Skills | No central orchestrator; setup and fixed-point/spec prerequisites are user-owned | Strong individual skills may not add up to a maintained product system | Pair with one explicit project-state system; keep `CONTEXT.md` and ADRs authoritative |

## Laravel/PHP and serious application fit

None of the candidates is Laravel-specific in the sense of owning Laravel architecture or guaranteeing framework-correct code. They are language-agnostic or host-centric. That is not a disadvantage by itself: the important differentiator is whether the system makes the repository’s conventions, migrations, queues, tests, and boundaries explicit.

For an existing Laravel/PHP/PostgreSQL/Redis application:

- **OpenSpec** is especially natural for focused brownfield deltas: a migration, a domain change, a queue behavior change, and the associated acceptance scenarios can be represented without reverse-specifying the whole application.
- **GSD Core** is strongest when the change spans several bounded phases or requires onboarding the existing codebase, mapping conventions, and preserving continuity across multiple agents.
- **BMAD** is strongest when the change is actually a product/domain initiative that needs requirements, UX, architecture, and stories before Laravel implementation.
- **Matt Skills** is particularly useful for domain modeling, ADRs, existing conventions, TDD, and code review, but it should sit inside a larger project-state choice if the work lasts years.
- **Superpowers** is strong for the implementation boundary: test-first changes, systematic debugging, worktrees, and final review. It does not replace Laravel-specific judgment about migrations, Eloquent boundaries, queues, policies, or modular-monolith seams.

No official source reviewed here establishes a special advantage for PHP, Laravel, PostgreSQL, Redis, or ORM-heavy systems. The correct evaluation is therefore repository-specific: can the chosen system read and preserve the project’s existing conventions, test suite, migrations, and deployment constraints?

## Adoption and operational cost

| Candidate | Setup difficulty | Learning curve | Maintenance | Repo footprint | Lock-in |
| --- | --- | --- | --- | --- | --- |
| GSD Core | Medium | High | Medium-high | Medium-high `.planning/` and commands | Low-medium; repo artifacts portable |
| BMAD | Medium | High | Medium-high | Medium-high workflows and artifacts | Low-medium; host adapters vary |
| AIWG | High | Very high | Very high | Very high agents/skills/templates/rules | Medium; provider adapters reduce it but framework surface grows |
| Kiro | Low-medium inside Kiro | Medium | Platform-managed | Medium `.kiro/` plus cloud/session surface | **High** |
| Spec Kitty | Medium-high | High | High | High specs, work packages, worktrees | Low-medium |
| Spec Kit | Medium | Medium-high | Medium | Medium `.specify/` and artifacts | Low |
| OpenSpec | Low-medium | Medium | Medium | Low-medium `openspec/` corpus | Low-medium |
| Compound Engineering | Medium | Medium | Medium | Medium `docs/`, packs, plugin files | Medium across hosts |
| gstack | Medium-high | Medium | Medium | Medium state/docs and toolchain | Medium-high, Claude-first |
| Superpowers | Low-medium | Medium | Low-medium | Low-medium `docs/superpowers/` | Low |
| Matt Skills | Low | Low-medium | Low | Low-medium skills/context/ADR files | Low |

These are comparative judgments about adoption, not a line-count measurement. A small footprint can still create high cognitive load, and a large artifact corpus can be valuable if it prevents repeated rediscovery.

## Overall capability ranking

### Raw scores and weighted results

Raw columns are scored 0–5 in this order: lifecycle, upstream discovery, architecture/domain, persistent state, brownfield/change, implementation/TDD, verification/release, portability/orchestration, human control, adoption/maturity. Weighted result is out of 100.

| Rank | Candidate | Life | Upstream | Arch | State | Change | Build | Verify | Port. | Human | Adopt | Weighted | Confidence |
| ---: | --- | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | ---: | --- |
| 1 | **GSD Core** | 4.8 | 3.7 | 4.2 | 5.0 | 4.7 | 4.2 | 4.6 | 4.5 | 4.3 | 4.0 | **89.3** | High for documented workflow; medium for real-world sustainability |
| 2 | **BMAD-METHOD** | 4.8 | 5.0 | 4.8 | 4.0 | 4.3 | 4.1 | 4.4 | 3.8 | 4.2 | 4.0 | **87.5** | High for workflow breadth; medium for operational smoothness |
| 3 | **AIWG** | 4.9 | 4.5 | 4.5 | 4.6 | 4.2 | 4.1 | 4.5 | 4.6 | 3.7 | 3.0 | **87.4** | Medium; broad claims are documented but adoption evidence is thin |
| 4 | **Spec Kitty** | 4.3 | 3.8 | 4.1 | 4.6 | 4.5 | 4.1 | 4.4 | 4.2 | 4.2 | 3.0 | **83.6** | Medium; current maturity transition matters |
| 5 | **Kiro** | 4.5 | 4.2 | 4.0 | 4.8 | 4.0 | 4.1 | 4.4 | 3.2 | 4.1 | 3.1 | **82.9** | High inside Kiro; low for portability |
| 6 | **Matt Pocock Skills** | 3.2 | 3.8 | 4.6 | 3.2 | 4.0 | 4.8 | 4.5 | 4.2 | 4.6 | 4.3 | **79.9** | High for skill behavior; medium as a complete system |
| 7 | **GitHub Spec Kit** | 4.0 | 4.0 | 4.0 | 3.8 | 4.1 | 3.6 | 3.8 | 4.5 | 4.3 | 4.0 | **79.5** | High for specification workflow; medium for full lifecycle |
| 8 | **Compound Engineering** | 3.4 | 2.9 | 3.3 | 3.8 | 4.1 | 4.2 | 4.7 | 4.5 | 4.0 | 3.8 | **76.5** | Medium-high for engineering loop; low for roadmap claims |
| 9 | **gstack** | 4.1 | 4.4 | 3.6 | 3.4 | 3.8 | 4.0 | 4.5 | 2.9 | 3.8 | 3.0 | **75.7** | Medium; strong product workflow, thinner long-term evidence |
| 10 | **OpenSpec** | 3.5 | 2.9 | 3.3 | 4.0 | 4.8 | 3.4 | 3.3 | 4.5 | 4.2 | 4.1 | **75.0** | High for brownfield change flow; medium as a full OS |
| 11 | **Superpowers** | 3.1 | 2.5 | 2.8 | 2.7 | 3.4 | 5.0 | 4.8 | 4.6 | 4.5 | 4.2 | **72.1** | High for engineering discipline; high confidence it is not a roadmap system |

### How to read the ranking

GSD wins because the weighting values continuity and project state, not because it has the most commands. BMAD nearly ties because its upstream product/UX/architecture coverage is stronger. AIWG is close on documented breadth but loses adoption points because the framework’s size, optional subsystems, and self-described limitations make its practical reliability less certain. Spec Kitty and Kiro are strong in different governance/platform dimensions but carry maturity or lock-in costs.

Superpowers and Matt Skills score lower overall because they intentionally solve a narrower problem. That is not a quality criticism; it is a category distinction. A specialist can be the right choice even when its full-lifecycle score is lower.

## Practical adoption ranking

This ranking asks: **How sensible is this for a real solo developer or small team to adopt without drowning in process?** It gives more weight to adoption, incremental brownfield use, engineering quality, human control, and ceremony-to-value ratio than to theoretical lifecycle completeness.

| Rank | Candidate | Practical score | Why it ranks here | Main reservation |
| ---: | --- | ---: | --- | --- |
| 1 | **Matt Pocock Skills** | **86.8** | Low footprint, excellent domain/TDD/review fundamentals, adaptable to existing repos | Needs an external project-state/roadmap discipline |
| 2 | **Superpowers** | **83.7** | Very high implementation quality and clear human gates with manageable adoption | Does not carry the product over years by itself |
| 3 | **GSD Core** | **82.4** | Best practical single primary system if the team commits to its artifacts | More ceremony than a solo developer may sustain |
| 4 | **OpenSpec** | **81.9** | Low-to-medium ceremony and excellent brownfield change contracts | Add your own TDD, review, roadmap, and release gates |
| 5 | **GitHub Spec Kit** | **79.7** | Portable, official, extensible, and useful for bounded specifications | Artifact and extension choices can sprawl |
| 6 | **Compound Engineering** | **78.8** | Strong plan/work/review/learning layer with broad host coverage | Not a canonical product operating system |
| 7 | **BMAD-METHOD** | **76.8** | Best when upstream product and architecture work justify the process | High ceremony and review-path complexity |
| 8 | **Spec Kitty** | **76.7** | Valuable when true parallel work-package governance exists | Too much for most solo workflows; maturity risk |
| 9 | **Kiro** | **75.5** | Very efficient if the team chooses Kiro as its managed platform | Lock-in, paid/platform boundary, external-edit limitations |
| 10 | **AIWG** | **73.0** | Potentially powerful for a team willing to operate a framework | Highest complexity and lowest low-friction confidence |
| 11 | **gstack** | **70.2** | Strong product/QA/shipping flow for its target audience | Claude-first and less proven as a long-term source-of-truth system |

The practical ranking is intentionally different. Matt Skills and Superpowers are easier to adopt because they do less. If the requirement is specifically “choose one primary system that carries a product for 6–24 months,” GSD should be preferred over either specialist library despite its lower adoption convenience.

## Situation-based recommendations

| Situation | Recommended system | Why | Tradeoff |
| --- | --- | --- | --- |
| A. Tiny bug fix | **Superpowers** or direct existing project workflow | Root-cause debugging and verification without forcing a full roadmap | GSD/BMAD would usually be excessive |
| B. Small feature | **OpenSpec + Superpowers** | Focused delta contract plus implementation/TDD/review | The integration is compositional, not a documented native integration |
| C. Medium feature | **GSD Core** | Phase context, plan, execution, verification, and state | More artifacts than a one-session feature needs |
| D. Large feature across many files | **GSD Core** | Fresh-context waves and verification reduce context overload | Requires disciplined `.planning/` hygiene |
| E. New greenfield product | **BMAD** if discovery-heavy; **GSD** if execution/state-heavy | BMAD leads product/UX/architecture; GSD leads continuity/execution | Two different primary choices; do not stack both by default |
| F. Existing brownfield Laravel/PHP app | **OpenSpec + Superpowers** for bounded change; **GSD** for a multi-phase initiative | OpenSpec avoids reverse-documenting the entire app; GSD maps and carries larger work | Add Laravel conventions and tests manually; no candidate is Laravel-native |
| G. Large domain-heavy application | **BMAD + Matt Skills**, or GSD + Matt’s domain layer | BMAD/Matt provide domain and architecture reasoning | High overlap; make one system authoritative for plans |
| H. 6–24 month project | **GSD Core** | Explicit roadmap, state, phase artifacts, summaries, verification, and ship/archive | Requires committing artifacts and periodic cleanup |
| I. Solo developer | **Matt Skills** for low ceremony; **GSD** if one primary OS is needed | Low overhead versus durable continuity | Matt alone leaves roadmap/state responsibilities external |
| J. Small team | **GSD** or **Spec Kitty** | Handoffs, work packages, review, and shared state | Spec Kitty is overkill unless parallel work is real |
| K. Multiple AI coding agents | **OpenSpec** or **Spec Kit**; **Spec Kitty** for governed parallelism | Portable repo-native artifacts and adapters | Adapter behavior is not identical across agents |
| L. Strong TDD preference | **Superpowers** | TDD is central and task-level verification is explicit | Upstream product/roadmap still needs another layer |
| M. Strong architecture/domain preference | **BMAD** or **Matt Skills** | Explicit product/UX/architecture or domain/ADR workflows | Neither alone is as strong as GSD at long-running state |
| N. Strong persistent project memory | **GSD Core**; **AIWG** for teams willing to operate it | State spine and phase artifacts are explicit | Memory quality still depends on keeping artifacts current |
| O. Maximum AI autonomy | **GSD Core** or **Kiro** | GSD offers wave execution/verification; Kiro offers managed hooks/sessions | Autonomy increases the cost of stale requirements and missed gates |
| P. Strong human control | **Superpowers**, **Matt Skills**, or OpenSpec | Human gates and user-driven composition | Less autonomous continuity; more manual orchestration |

## Combination analysis

Combinations are recommended only when responsibilities are clearly non-overlapping.

| Combination | Complementarity | Overlap/conflict | Verdict |
| --- | --- | --- | --- |
| **GSD + Superpowers** | GSD owns roadmap, phase state, research, verification, and ship; Superpowers owns implementation/TDD/review | Both can plan, review, and use subagents; duplicate planning is easy | **Recommended for high-assurance work**, with a hard boundary: GSD creates the phase plan; Superpowers executes approved tasks and returns evidence |
| **OpenSpec + Superpowers** | OpenSpec owns change proposal/delta specs/sync; Superpowers owns TDD, debugging, and branch review | Both create plans/tasks and may each claim “review” | **Best lightweight combination** for a brownfield app; use OpenSpec as requirements truth and Superpowers as execution discipline |
| **GSD + Matt Skills** | GSD owns project state; Matt owns domain modeling, ADRs, TDD, and code review | Matt’s specs/tickets can duplicate GSD phase plans | Good only if Matt is limited to domain/decision/quality work; do not let both own the roadmap |
| **BMAD + Superpowers** | BMAD owns discovery/UX/architecture/story shaping; Superpowers owns TDD and branch execution | BMAD already has build/review/testing paths; overlap is high | Useful when BMAD’s upstream work is valuable and its implementation loop is not; pilot before standardizing |
| **BMAD + Matt Skills** | Both are strong at domain, research, architecture, specs, and review | Very high duplicate artifact and decision surfaces | Usually avoid; choose BMAD for role workflow or Matt for lightweight engineering reasoning |
| **Spec Kit + Superpowers** | Spec Kit owns spec/plan/tasks/converge; Superpowers owns TDD/review/branch completion | Similar plan/task boundaries | Reasonable if Spec Kit is the team’s contract and Superpowers is only execution; no native integration evidence |
| **Compound + GSD** | Compound owns review/learning/PR; GSD owns project state | Both plan, work, review, handoff, and persist learning | Avoid initially; add one Compound skill only after a concrete gap appears |
| **Kiro + any external primary OS** | Kiro can host tools and provide sessions/hooks | Two context/state/checkpoint systems create split truth | Choose Kiro alone or keep external artifacts authoritative and disable overlapping state features |

The common rule is: **one primary source of truth, one execution/review layer, no second roadmap.**

## Primary system versus secondary specialist

| Candidate | Primary system? | Secondary layer? | Classification |
| --- | --- | --- | --- |
| GSD Core | **Yes** | Yes | Project operating system / planning-state system |
| BMAD | **Yes** | Yes | Full lifecycle methodology |
| AIWG | Yes, for a team that will operate it | Yes | Full SDLC framework / orchestration |
| Kiro | Yes inside a Kiro-first organization | No outside Kiro | IDE ecosystem / managed development system |
| Spec Kitty | Yes for multi-agent governed teams | Yes | Project operating system / work-package governance |
| Spec Kit | Yes if specification is the chosen center | Yes | Specification framework |
| OpenSpec | Usually no for a whole product; yes for a change-centered repo | **Yes** | Brownfield specification/change layer |
| Compound Engineering | Usually no | **Yes** | Engineering workflow / knowledge-compounding layer |
| gstack | Sometimes for its Claude-first target | Yes | Product-builder workflow / specialist tool |
| Superpowers | Usually no | **Yes** | Coding methodology / engineering quality layer |
| Matt Skills | Usually no | **Yes** | Composable engineering skill library |

## Final verdict

1. **Strongest overall system:** **GSD Core**, because it best connects requirements, phase state, fresh-context execution, verification/UAT, and shipping for a long-running project.
2. **Strongest lightweight option:** **OpenSpec** for brownfield changes; **Matt Pocock Skills** for low-ceremony engineering fundamentals. These are different kinds of lightweight.
3. **Strongest engineering-focused option:** **Superpowers** for TDD, debugging, task review, worktrees, and branch completion.
4. **Strongest product/architecture-focused option:** **BMAD-METHOD**; Matt Skills is the lighter domain/ADR alternative.
5. **Strongest long-running-project option:** **GSD Core**; AIWG is broader on paper but less practical to operate.
6. **Strongest TDD-oriented option:** **Superpowers**, with Matt Skills close for engineers who want a composable TDD plus architecture/review toolkit.
7. **Strongest persistent-context option:** **GSD Core** for a portable repo-native state spine; Kiro is strongest inside its managed platform; AIWG is the most ambitious artifact model.
8. **Strongest option for a solo developer:** **Matt Skills** for low ceremony, or **GSD Core** when continuity and a single primary system matter more than setup cost.
9. **Strongest option for a small team:** **GSD Core** for shared product continuity; **Spec Kitty** when parallel work-package governance is genuinely needed.
10. **Strongest alternative to the overall leader:** **BMAD-METHOD** for upstream product/architecture work, or **OpenSpec** if the actual need is focused brownfield evolution rather than a full project OS.

## What I would verify experimentally

Documentation cannot answer these reliably:

1. Does the system preserve the important decisions after five context compactions and a new session?
2. Does a new agent actually read and apply the persisted artifacts, or merely acknowledge them?
3. How much human intervention is required before the first correct implementation?
4. Does it detect a deliberate contradiction between an old architectural decision and a new requirement?
5. Does a schema/migration change update dependent code, tests, documentation, and state coherently?
6. Can it resume after a failed tool call, partial task, rejected review, or interrupted session without duplicating work?
7. How much ceremony is required for a tiny fix compared with a multi-phase feature?
8. Are cross-agent adapters behaviorally equivalent, especially around hooks, compaction, permissions, and handoffs?
9. Does independent review find real defects, or only produce plausible prose?
10. Does the system remain usable after three months of artifact accumulation and changing requirements?

### Proposed hands-on benchmark

Run the same controlled scenario through the strongest 3–5 candidates: **GSD Core, BMAD, OpenSpec, Superpowers, and Matt Skills**. Keep the repository, model, test budget, and human prompts as constant as practical. Use a small but domain-rich Laravel/PHP application with PostgreSQL-style migrations, a queue/job, an authorization rule, and an existing test suite.

Scenario:

```text
1. Onboard the existing repository
2. Discover and clarify a feature: scheduled customer notifications
3. Record a domain and architecture decision
4. Add a database change and queue/job behavior
5. Implement the feature
6. Run tests and review the change
7. Introduce a deliberate requirement change: per-tenant quiet hours
8. Continue from a fresh session with the original context unavailable
9. Diagnose an injected regression in retry behavior
10. Prepare a release/PR handoff and report unfinished work
```

Measure the following for every candidate:

- number of manual interventions and approvals;
- elapsed human time and number of model/tool turns;
- artifacts created, their quality, and whether they are actually consulted;
- consistency of architecture, migrations, tests, and conventions;
- context retention after a fresh session and compaction;
- ability to identify and revise the impacted old decision;
- unnecessary ceremony for the tiny fix versus the multi-step feature;
- defects found by independent review and tests;
- recovery after interruption or failed commands;
- clarity of final state, handoff, PR, and known unfinished work.

Use a blinded rubric where possible. The winner should be the system with the best combination of correctness, continuity, and human-time efficiency—not the one that creates the most files or produces the longest plan.

## Source notes and primary references

The detailed first-pass source memo is [available in the repository](./ai-engineering-systems-source-notes.md). The most important primary references are:

- [GSD Core README](https://github.com/open-gsd/gsd-core/blob/main/README.md), [commands](https://github.com/open-gsd/gsd-core/blob/main/docs/COMMANDS.md), [phase loop](https://github.com/open-gsd/gsd-core/blob/next/docs/explanation/the-phase-loop.md), and [brownfield onboarding](https://github.com/open-gsd/gsd-core/blob/next/docs/tutorials/onboarding-an-existing-codebase.md).
- [BMAD README](https://github.com/bmad-code-org/BMAD-METHOD/blob/main/README.md), [planning paths](https://docs.bmad-method.org/plan/choose-a-planning-path/), [existing codebases](https://docs.bmad-method.org/existing-codebases/start-in-an-existing-codebase/), and [review lifecycle issue](https://github.com/bmad-code-org/BMAD-METHOD/issues/2760).
- [Matt Pocock Skills](https://github.com/mattpocock/skills), [implementation skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/implement/SKILL.md), and [code review skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/code-review/SKILL.md).
- [Superpowers README](https://github.com/obra/superpowers/blob/main/README.md), [brainstorming](https://github.com/obra/superpowers/blob/main/skills/brainstorming/SKILL.md), and [subagent-driven development](https://github.com/obra/superpowers/blob/main/skills/subagent-driven-development/SKILL.md).
- [GitHub Spec Kit](https://github.com/github/spec-kit), [workflow reference](https://github.com/github/spec-kit/blob/main/docs/reference/workflows.md), and [existing-project guide](https://github.github.io/spec-kit/guides/existing-projects.html).
- [OpenSpec README](https://github.com/Fission-AI/OpenSpec/blob/main/README.md), [workflows](https://github.com/Fission-AI/OpenSpec/blob/main/docs/workflows.md), [existing projects](https://github.com/Fission-AI/OpenSpec/blob/main/docs/existing-projects.md), and [supported tools](https://github.com/Fission-AI/OpenSpec/blob/main/docs/supported-tools.md).
- [Kiro specs](https://kiro.dev/docs/specs/), [steering](https://kiro.dev/docs/steering/), and [Kiro documentation](https://kiro.dev/docs/).
- [AIWG README](https://github.com/jmagly/aiwg/blob/main/README.md), [SDLC Complete](https://github.com/jmagly/aiwg/tree/main/agentic/code/frameworks/sdlc-complete), and [orchestrator architecture](https://github.com/jmagly/aiwg/blob/main/agentic/code/frameworks/sdlc-complete/docs/orchestrator-architecture.md).
- [Spec Kitty README](https://github.com/spec-kitty/spec-kitty).
- [Compound Engineering README](https://github.com/EveryInc/compound-engineering-plugin), [guides](https://github.com/EveryInc/compound-engineering-plugin/blob/main/docs/guides/README.md), and [compound learning](https://github.com/EveryInc/compound-engineering-plugin/blob/main/docs/guides/ce-compound.md).
- [gstack README](https://github.com/garrytan/gstack).
- [OpenAI Harness Engineering](https://openai.com/index/harness-engineering/) and [OpenAI Symphony](https://github.com/openai/symphony) as adjacent methodology/orchestration references rather than ranked peer systems.

**Bottom line:** adopt one primary source of truth. For the stated problem, that primary system should be **GSD Core** unless the project’s dominant risk is upstream product discovery (BMAD), bounded brownfield change (OpenSpec), or implementation quality/TDD (Superpowers/Matt Skills). Validate the choice with the benchmark before committing the team to a large artifact corpus.
