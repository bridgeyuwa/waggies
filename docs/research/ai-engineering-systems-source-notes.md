# AI-assisted software engineering systems: source notes

**Research snapshot:** 2026-09-27 (Africa/Lagos)

**Question:** Which system best fits a solo developer or tiny team building a serious, long-running product?

**Scope:** BMAD-METHOD, Matt Pocock/Engineering Skills, Superpowers, GSD/GSD Core and successor, GitHub Spec Kit, OpenSpec, Compound Engineering, Kiro, AIWG, plus gstack, OpenAI Harness Engineering, and OpenAI Symphony as serious alternatives or adjacent systems.

**Method:** Primary sources only: official repositories, first-party documentation, workflow/skill files, official issue/release material, and OpenAI's first-party engineering publications. “Supported” means the source explicitly documents it. “Technically possible” is not counted as supported. Where a source does not establish a capability, this memo says **Evidence insufficient**. Repository `main`/default-branch documentation is treated as the current surface on the snapshot date; it is not a reproducibility guarantee unless a release or commit is pinned.

## Short answer

**Best single fit: GSD Core (`open-gsd/gsd-core`), the current successor to the original GSD repository.** It is the only candidate in this set whose current primary documentation explicitly combines:

- a repeatable Discuss → Plan → Execute → Verify → Ship loop;
- fresh-context subagents for research, planning, and execution;
- repo-persisted `STATE.md`, `CONTEXT.md`, roadmap, requirements, research, summaries, and verification artifacts;
- explicit greenfield and brownfield entry points;
- phase verification, user acceptance testing, fix-plan generation, review/convergence, and PR shipping;
- broad multi-runtime support, including Claude Code, Codex, Copilot, Cursor, Windsurf, OpenCode, Kimi, Kilo, and Antigravity.

Those are documented capabilities, not an inference from marketing copy: see the [current GSD Core README](https://github.com/open-gsd/gsd-core/blob/main/README.md), [command reference](https://github.com/open-gsd/gsd-core/blob/main/docs/COMMANDS.md), and [architecture documentation](https://github.com/open-gsd/gsd-core/blob/main/docs/ARCHITECTURE.md).

The main trade-off is process weight. GSD Core is a project operating system, not a small helper. A solo developer should use its phase loop selectively and keep the artifacts committed. For a tiny team that wants a lighter, more brownfield-first spec layer, **OpenSpec** is the best alternative. For a richer product/UX/architecture lifecycle with specialized personas, **BMad** is the closest competitor. For implementation discipline alone, **Superpowers** is stronger than either, but it does not by itself provide the same project roadmap and long-running state model.

## Decision criteria

The comparison below uses the capabilities that matter for a long-lived product, not star counts or vendor claims:

1. **Lifecycle coverage:** idea/requirements, architecture/design, implementation, testing, review, release, retrospectives, and recovery.
2. **Persistence:** whether decisions, state, plans, research, and verification survive a new session through versioned project artifacts.
3. **Greenfield and brownfield:** whether each is explicitly documented for both new projects and existing codebases.
4. **Quality discipline:** TDD, deterministic checks, independent review, UAT, security/performance/research workflows.
5. **Portability and installability:** supported agent hosts, project-local installation, upgrade path, and runtime prerequisites.
6. **Failure containment:** what happens when a plan is stale, a review is skipped, context is compacted, a tool is missing, or an unattended loop cannot finish.

## Evidence matrix

| System | Documented lifecycle | Durable project state | Greenfield / brownfield | Quality evidence | Main limitation for this brief |
|---|---|---|---|---|---|
| **GSD Core** | Discuss, plan/research, execute, verify/UAT, ship; milestones, audits, debug, security, review | `.planning/PROJECT.md`, `REQUIREMENTS.md`, `ROADMAP.md`, `STATE.md`, phase artifacts, summaries, verification | Explicit `/gsd-new-project` and `/gsd-onboard` | Optional TDD mode, plan checker, review convergence, UAT, cross-AI review, security phase | Heavy and command-rich; requires disciplined artifact hygiene |
| **BMad Method** | Four-phase analysis, planning, solutioning, implementation; quick flow and autonomous loop | Planning artifacts, `AGENTS.md` project context, `sprint-status.yaml`, story/epic files | Explicitly supports new and existing code | PRD/architecture/readiness gates, code review, testing add-on, retrospectives | Workflow/runtime lives in agent instructions; official issue documents duplicated review layers and a review-state dead end |
| **OpenSpec** | Explore/propose/apply/sync/archive; expanded continue/verify/onboard flow | `openspec/specs/`, change folders, archive; optional beta Stores | Explicitly brownfield-first; also greenfield | Concrete scenarios; optional verify; no default TDD requirement | Default `core` profile omits expanded verification/onboarding; teams must choose and enforce the quality profile |
| **GitHub Spec Kit** | Constitution, Specify, Plan, Tasks, Implement, Converge; separate bugfix and idea-assessment flows | `.specify/`, feature Markdown artifacts, persistence model choices | Explicit existing-project guide; strong greenfield path | Checklists, clarify/analyze/converge; tests are optional in the task template | Python/uv setup and substantial artifacts; git workflow is an extension, not core; `--force` can replace managed conflicts |
| **Kiro** | Feature specs, bugfix specs, quick specs; requirements/design/tasks; hooks and sub-agents | `.kiro/steering/`, specs, session persistence, checkpoints/rewind, cloud memory/sessions | Feature docs explicitly mention greenfield; bugfix/existing code is supported | EARS requirements, root-cause bugfix specs, property-based correctness option, hooks | Kiro-controlled platform; compaction is one-way and checkpoints do not track external/MCP/shell edits |
| **AIWG** | Optional stage-gate lifecycle from inception through production; specialist review panels and agent loops | `.aiwg/` artifact memory, indexing, traceability, optional daemon/loop | Explicitly aimed at individual developers and long projects; repo-local deployment | Test Architect, security/performance review, phase gates, citation/traceability structures | Very broad/optional architecture; its own README says saved artifacts do not guarantee later use and optional services are not automatically running |
| **Compound Engineering** | Brainstorm, plan, work, simplify, review, compound; autonomous `lfg` and PR/CI repair | `docs/plans/`, `docs/solutions/`, config, packs; repo-relative artifact root can be changed | Works as a plugin in existing projects; explicit greenfield lifecycle evidence is insufficient | Multi-agent code/doc review, browser tests, research/explain/POV, bounded repair | A skill/plugin loop rather than a canonical product roadmap; upgrades and host behavior vary materially |
| **Superpowers** | Brainstorm, worktree, plan, subagent execution, TDD, review, finish branch | Design docs, plans, worktree/ledger during execution | Technically usable in either; explicit brownfield lifecycle documentation is insufficient | Strongest documented TDD and per-task review discipline in this set | Not a product backlog/roadmap system; install separately per harness and some harnesses have lifecycle gaps |
| **Matt Pocock Skills** | Composable idea → spec → tickets → implement → review path | `CONTEXT.md`, ADRs, issue tracker or local files, specs/tickets | Works on existing repos; explicit greenfield claims are insufficient | TDD, two-axis review, diagnosis, architecture, research | Deliberately does not own the process; setup is mandatory and native Codex plugin is still a roadmap item |
| **gstack** | Office hours, CEO/eng/design review, build, review, browser QA, ship/deploy, canary, retro | Project/global state, design/spec docs, learnings; optional private git memory sync | Designed for products and existing repos; formal brownfield onboarding evidence is insufficient | Review, security, live browser QA, regression tests, release verification | Claude Code first; Bun/Node/browser prerequisites; Windows and cross-agent install caveats |
| **OpenAI Symphony** | Issue-tracker polling, isolated workspace, agent run, retries/reconciliation, workflow-defined handoff | Filesystem/tracker recovery; exact in-memory scheduler state is intentionally not restored | Orchestration around an existing product repo; it is not a project planning method | Depends on the repo `WORKFLOW.md`, agent, CI, and tracker | Explicitly an engineering preview for trusted environments; scheduler, not a complete lifecycle methodology |

## Candidate evidence and failure modes

### 1. GSD Core — strongest single fit

**Status and installation.** The original [`gsd-build/get-shit-done`](https://raw.githubusercontent.com/gsd-build/get-shit-done/main/README.md) now says it is an archived redirect and that active development moved to [`open-gsd/gsd-core`](https://github.com/open-gsd/gsd-core). GSD Core installs with `npx @opengsd/gsd-core@latest`; the installer asks for a runtime and global/local scope. The README documents Claude Code, OpenCode, Antigravity CLI, Kimi CLI, Kilo, Codex, Copilot, Cursor, and Windsurf.

**Workflow and state.** The current README documents Discuss → Plan → Execute → Verify → Ship, with fresh 200k-token execution contexts. The command reference makes the state concrete: `/gsd-new-project` produces `PROJECT.md`, `REQUIREMENTS.md`, `ROADMAP.md`, `STATE.md`, `config.json`, `research/`, and `CLAUDE.md`; `/gsd-onboard` maps an existing codebase into `.planning/codebase/`; execution produces summaries and verification artifacts; verify-work produces UAT and targeted fix plans; ship creates a PR and archives the phase. This is unusually strong evidence for long-running continuity.

**Research, architecture, TDD, review.** `gsd-plan-phase` has a research phase, plan-check verification, optional plan-bounce review, tracer-first planning, one-way-door checkpoints, and `--tdd` mode. `gsd-plan-review-convergence` runs review/replan cycles with a default cap. `gsd-verify-work` supports browser-backed UAT and has an explicit `insufficient_spec` abstention path rather than silently passing an unverifiable requirement. This is documented in the [command reference](https://github.com/open-gsd/gsd-core/blob/main/docs/COMMANDS.md).

**Concrete failure modes.** The same command reference documents `STATE.md` freshness warnings after a commit-age threshold, state validation/synchronization commands, a `--skip-verify` escape hatch, and human checkpoints for failed package installation rather than silently substituting a similarly named package. The system can therefore fail safely, but only if the operator does not routinely bypass its checks. The process surface is large; evidence is insufficient to claim that a solo developer will sustain every artifact without deliberate operating discipline.

### 2. BMad Method — closest lifecycle-rich alternative

**Status and installation.** The current [BMAD-METHOD README](https://github.com/bmad-code-org/BMAD-METHOD/blob/main/README.md) documents `npx skills add bmad-code-org/BMAD-METHOD`, Claude Code and Codex plugin marketplaces, `uv` for setup/scripts, `bmad setup`, and `bmad-build`. It explicitly says the same method covers a weekend prototype, an established codebase, and a system with years of history.

**Workflow and state.** The [official workflow map](https://github.com/bmad-code-org/BMAD-METHOD/blob/main/docs/reference/workflow-map.md) shows four phases: analysis (`brief`, PRD, research), planning (`prd`, UX, spec), solutioning (architecture, epics/stories, readiness), and implementation (`sprint-status.yaml`, stories, dev-story, code review, corrective course, status, retrospective). The README also documents a project-context block in `AGENTS.md` as the preferred verified context surface in current releases.

**Quality and research.** BMad has explicit PRD, architecture, implementation-readiness, code-review, testing, and retrospective workflows. The Test Architect extension documents step files, checklists, isolated workers, aggregation, validation, and resumable checkpoint frontmatter. Current release notes describe consolidated deep reconnaissance/research and configurable review layers.

**Concrete failure modes.** The official [issue #2760](https://github.com/bmad-code-org/BMAD-METHOD/issues/2760) reports that `bmad-build` and `bmad-code-review` can repeat identical review layers and that `bmad-build` may leave a story in `review` indefinitely if the separate review step is skipped. The official TEA README also states that core BMad is a small agent/workflow engine with no external orchestrator; state and progress are primarily instruction- and artifact-driven inside the agent runtime. That makes BMad capable, but more dependent on correct workflow invocation than GSD's explicit phase commands.

### 3. OpenSpec — best lighter brownfield layer

**Status and installation.** The current [OpenSpec README](https://github.com/Fission-AI/OpenSpec/blob/main/README.md) documents a rebuilt artifact-guided `opsx` workflow, Node.js 20.19+, `npm install -g @fission-ai/openspec@latest`, `openspec init`, and 30+ tool integrations. The [supported-tools reference](https://github.com/Fission-AI/OpenSpec/blob/main/docs/supported-tools.md) lists Claude, Codex, Cursor, Kiro, GitHub Copilot, OpenCode, Pi, Zed, and many others with their generated skills/command locations.

**Workflow and persistence.** The default loop is `/opsx:explore` → `/opsx:propose` → `/opsx:apply` → `/opsx:sync` → `/opsx:archive`. A proposal creates `proposal.md`, delta specs, `design.md`, and `tasks.md`; archive merges the change into `openspec/specs/`. The [existing-project guide](https://github.com/Fission-AI/OpenSpec/blob/main/docs/existing-projects.md) is unusually explicit: do not document the whole legacy system; document only the slice being changed, use ADDED/MODIFIED/REMOVED deltas, commit `openspec/`, and let the corpus grow with real changes.

**Quality and lifecycle.** OpenSpec has explore, proposal, apply, sync, archive, and an expanded profile with continue, fast-forward, verify, bulk archive, and onboard. It is specification-first but deliberately fluid rather than rigid. TDD, independent review, security, and release gates are not required by the default workflow.

**Concrete failure modes.** The default `core` profile excludes `verify` and `onboard`; a serious team must explicitly select an expanded profile and run `openspec update`. The docs call cross-repo Stores beta. `openspec update` replaces OpenSpec-managed skill/command bodies, so local edits inside managed paths are not a durable customization seam. The README also documents anonymous telemetry enabled by default unless disabled. These are manageable, but they are operational decisions, not incidental details.

### 4. GitHub Spec Kit — thorough, extensible, heavier

**Status and installation.** The current [Spec Kit README](https://github.com/github/spec-kit/blob/main/README.md) documents Python 3.11+, `uv`, `specify init`, and a broad integration surface. The official docs currently describe 38 integrations and a generic escape hatch. The core workflow is Specify → Plan → Tasks → Implement → Converge, with separate bug-fixing and idea-assessment extensions.

**Workflow files.** The [spec template](https://github.com/github/spec-kit/blob/main/templates/spec-template.md) requires prioritized, independently testable user stories, acceptance scenarios, edge cases, functional requirements, entities, and measurable success criteria. The [plan template](https://github.com/github/spec-kit/blob/main/templates/plan-template.md) requires technical context, constitution checks, research, data model, contracts, and project structure. The [tasks template](https://github.com/github/spec-kit/blob/main/templates/tasks-template.md) organizes work by story and says tests are optional unless requested, although included test tasks must fail before implementation.

**Brownfield and quality.** The [existing-project guide](https://github.com/github/spec-kit/blob/main/docs/guides/existing-projects.md) says to initialize in place, capture only relevant guardrails, and use the next bounded change rather than reverse-specifying the entire system. Clarify, plan, tasks, analyze, implement, and converge are documented. This is good evidence for both greenfield and brownfield work.

**Concrete failure modes.** `specify init --here --force` may replace files at conflicting managed paths, so the guide requires a reviewable baseline first. Git initialization/branching is managed by an optional extension, not core. The workflow catalog docs explicitly warn that Spec Kit maintainers do not review, audit, endorse, or support workflow code from catalogs. The system is therefore powerful but can become a large, partially customized process whose quality depends on which extensions/presets are installed.

### 5. Kiro — strongest managed product surface, weakest portability

**Status and installation.** Kiro's first-party docs describe one harness across IDE, CLI, Web, Mobile, and Crew. The CLI docs give the current install command, `curl -fsSL https://cli.kiro.dev/install | bash`, and document headless mode, hooks, steering, MCP, custom agents, skills, and sub-agents.

**Workflow and state.** The [Specs documentation](https://kiro.dev/docs/specs/) documents feature and bugfix specs, parallel task execution, `requirements.md`/`bugfix.md`, `design.md`, and `tasks.md`. Feature specs support Requirements-First and Design-First flows; bugfix specs include root-cause analysis, unchanged behavior, and regression-prevention properties. [Steering](https://kiro.dev/docs/steering/) provides persistent `.kiro/steering/` project knowledge, `AGENTS.md` support, and workspace/global/cloud scopes. [Hooks](https://kiro.dev/docs/hooks/) can run shell commands or agent prompts on file, tool, prompt, and task events.

**Long-running behavior and quality.** Kiro documents automatic compaction, checkpoints, rewind, cloud sessions, parallel task waves, property-based correctness, and browser/CLI/IDE continuity. This is more than a prompt pack. It is also a managed platform rather than a repo-neutral method.

**Concrete failure modes.** Compaction is one-way: the complete pre-compaction conversation is not recoverable in-session. Checkpoints do not track edits made outside Kiro, including MCP tools or shell commands, so restoring a checkpoint can leave the agent's file state and actual workspace state out of sync. Custom agents do not automatically include steering files; their `resources` must explicitly load them. Web cannot read local global steering without Configuration Sync. Those are documented limitations, not hypothetical ones.

### 6. AIWG — broadest artifact and specialist framework

**Status and installation.** The current [AIWG README](https://github.com/jmagly/aiwg/blob/main/README.md) documents Node.js 20+, `npm i -g aiwg`, `aiwg use all --provider <provider>`, a project-local deployment model, 18 named provider integrations plus a generic adapter, and optional AIWG Cockpit/Agentic Sandbox.

**Workflow and state.** AIWG deploys agents, skills, commands, rules, behaviors, and templates into provider-readable locations. `.aiwg/` is documented as persistent project memory for requirements, architecture decisions, test strategies, risk registers, and deployment plans. Its stage-gate lifecycle is Inception → Elaboration → Construction → Transition → Production. The README documents review panels with architecture, security, performance, test, and writing roles; traceability links across docs/code/tests; and bounded execute/verify/learn loops.

**Concrete failure modes.** AIWG's own README is unusually candid: a saved artifact is not a guarantee that later sessions will read it correctly; multiple reviewers can share an error; unattended loops are not guaranteed to finish; optional services have their own prerequisites and do not start merely because assets were installed; and the base install intentionally excludes native packages requiring explicit trust. This makes AIWG a strong platform for a team willing to operate a framework, but evidence is insufficient to call it the lowest-friction fit for a solo developer.

### 7. Compound Engineering — good compounding loop, not a full product OS

**Status and installation.** The current [Compound Engineering README](https://github.com/EveryInc/compound-engineering-plugin/blob/main/README.md) describes 36 skills running on 14 agent hosts, including Claude Code, Cursor, Codex, Kimi, Cline, Copilot, OpenCode, Pi, and others. It documents host-specific installation, including a custom marketplace for Codex App and native plugin installation for Codex CLI.

**Workflow and persistence.** The loop is brainstorm → plan → work → simplify → code review → compound. The repo documents `/ce-brainstorm`, `/ce-plan`, `/ce-work`, `/ce-simplify-code`, `/ce-code-review`, and `/ce-compound`; learning is written to `docs/solutions/`, plans to `docs/plans/`, and optional packs provide version-pinned rules. The [concepts file](https://github.com/EveryInc/compound-engineering-plugin/blob/main/CONCEPTS.md) says each stage hands a durable artifact to the next.

**Quality and failure modes.** It has multi-agent review, doc review, browser testing, research/explain/POV skills, and an autonomous `/lfg` pipeline. The README says the autonomous pipeline can finish with leftovers when its bounded repair budget is hit, and that existing installs must refresh the marketplace before updating or they can remain on an old version. Specialist reviewer/research behavior is mostly prompt assets inside skills rather than separately exposed agent definitions. This is strong as a layer, but the evidence for roadmap/milestone ownership is insufficient.

### 8. Superpowers — strongest implementation discipline

**Status and installation.** The current [Superpowers README](https://github.com/obra/superpowers/blob/main/README.md) documents Claude Code, Codex App/CLI, Cursor, Gemini CLI, GitHub Copilot CLI, OpenCode, Pi, Kimi, Qwen, Factory Droid, Antigravity, and others. It requires separate installation per harness.

**Workflow files.** The documented basic workflow is brainstorming → git worktree → writing plans → subagent-driven development or executing plans → TDD → code review → finishing the branch. The actual [executing-plans skill](https://github.com/obra/superpowers/blob/main/skills/executing-plans/SKILL.md) requires a workspace/ledger, per-task TDD and verification, task completion records, and a fresh final branch review. The [systematic-debugging skill](https://github.com/obra/superpowers/blob/main/skills/systematic-debugging/SKILL.md) forbids fixes before root-cause investigation.

**Concrete failure modes.** Superpowers is process discipline, not a product-management or multi-milestone state system. It can leave the developer to provide the durable roadmap/backlog. Installation and lifecycle behavior vary by host. The README explicitly says Hermes has no post-compaction hook, so a long compacted session can lose the bootstrap and require a fresh session. Its TDD skill can delete code written before tests, which is a deliberate guardrail but can surprise a user migrating an existing implementation.

### 9. Matt Pocock / Engineering Skills — best composable fundamentals

**Status and installation.** The current [skills README](https://github.com/mattpocock/skills/blob/main/README.md) offers a Claude Code plugin or `npx skills@latest add mattpocock/skills` for Codex and other agents. It requires `/setup-matt-pocock-skills` once per repository to choose GitHub, Linear, or local issue tracking, labels, and document location. The repo explicitly says installing both the managed plugin and editable skills leaves duplicates, and that a native Codex plugin is on the roadmap.

**Workflow and quality.** The repo's engineering catalog covers grill-with-docs, domain context, ADRs, specs, tickets, implementation, TDD, diagnosis, architecture, research, and code review. The actual [implement skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/implement/SKILL.md) requires regular typechecking, targeted tests, the full suite, code review, and a commit. The [TDD skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/tdd/SKILL.md) enforces red-green-refactor at pre-agreed public seams. The [code-review skill](https://github.com/mattpocock/skills/blob/main/skills/engineering/code-review/SKILL.md) runs Standards and Spec review as parallel sub-agents.

**Concrete failure modes.** This is intentionally small and composable: it does not own a single end-to-end lifecycle. Code review fails early if no fixed point is supplied; it reports “no spec available” if no originating spec exists; and the repository's own research skill requires a background agent. Those are good honesty checks, but they mean the developer must supply orchestration and durable product planning.

### 10. gstack — product-builder workflow with browser/release depth

The current [gstack README](https://github.com/garrytan/gstack/blob/main/README.md) documents office hours, CEO/engineering/design reviews, review, browser QA, security, shipping/deploy, canary monitoring, documentation, and retrospectives. It supports Claude Code first and documents setup targets for Codex CLI, OpenCode, Cursor, Factory Droid, Kiro, OpenClaw, and Hermes.

It has useful persistent surfaces: project/global state, design/spec archives, learnings, and optional private-git memory sync. It is compelling for a solo product builder who wants product judgment, live browser testing, and shipping in one skill pack.

Concrete operational caveats are documented in the README: Windows may fall back to copies rather than symlinks and requires rerunning setup after updates; `/cso` can report `not assessed` without its native toolchain; and outside reviews require the selected external CLI to be installed and authenticated. Evidence is insufficient that gstack itself is a complete replacement for a roadmap/spec/state system across years; its documented strength is the sprint and product-delivery loop.

### 11. OpenAI Harness Engineering — important methodology, not a drop-in system

OpenAI's [Harness Engineering article](https://openai.com/index/harness-engineering/) reports a five-month internal experiment in which a small team built and shipped a product with agent-written application code, tests, CI, documentation, observability, and tooling. The operational lessons are directly relevant: repository knowledge must be the system of record; agents need legible architecture and executable checks; humans should encode invariants and feedback loops; and the team is still learning how coherence evolves over years.

The [Codex cookbook workflow](https://github.com/openai/openai-cookbook/blob/main/examples/codex/iterating-development-workflows-with-codex.md) gives a concrete repo harness with `AGENTS.md`, goals, plans, prompts, phase files, build logs, code-review artifacts, and context records. It explicitly calls most of those files recommended conventions rather than Codex requirements. This is a valuable design reference for whichever system is chosen, but **Evidence insufficient** to treat Harness Engineering alone as an installable lifecycle framework.

### 12. OpenAI Symphony — serious orchestration alternative, not a replacement

The official [Symphony repository](https://github.com/openai/symphony) describes a service that polls a tracker, creates isolated workspaces, runs coding agents, produces work evidence, and moves accepted work toward a human handoff. The [service specification](https://github.com/openai/symphony/blob/main/SPEC.md) defines a repo-owned `WORKFLOW.md`, bounded concurrency, deterministic per-issue workspaces, retry/backoff, reconciliation, observability, and filesystem/tracker restart recovery without requiring a persistent database. The [Elixir README](https://github.com/openai/symphony/blob/main/elixir/README.md) gives the current reference implementation setup.

Concrete limitations are explicit: the repository calls Symphony a low-key engineering preview for trusted environments; exact in-memory scheduler state is not restored; ticket writes/business logic normally live in the agent workflow; and Symphony is a scheduler/runner, not a general workflow engine or a planning method. It is a strong future layer after a team has a stable repo harness and issue tracker, not the first system to adopt for a solo product.

## Recommendation by operating situation

### Choose GSD Core when one system must carry the product

Choose GSD Core if the priority is continuity across months, explicit phase state, brownfield onboarding, fresh-context execution, verification/UAT, and a release artifact at the end of each phase. Commit `.planning/` artifacts. Use `--tdd` for behavior-heavy phases, retain plan/verify gates, and treat `--skip-verify` as an exceptional escape hatch.

### Choose OpenSpec when brownfield change flow and low ceremony matter most

Choose OpenSpec when you already have a serious codebase and want each change to leave a focused, reviewable delta spec without reverse-documenting the whole system. Enable the expanded profile and add your own test/review gates; the default core profile is not enough by itself for a high-assurance long-lived product.

### Choose BMad when product discovery and role-specialized planning are central

Choose BMad when you value product/UX/architecture personas, progressive planning, epics/stories, readiness gates, and retrospectives. Pin versions and explicitly verify the review lifecycle in your chosen release because the official issue tracker documents review duplication and a possible `review`-state dead end.

### Layer rather than replace

Superpowers, Matt Pocock's skills, and Compound Engineering are best treated as quality layers around a project state system. A practical combination is GSD Core or OpenSpec for durable state plus one carefully selected implementation/review layer—not all of them at once. Kiro is the best choice if the team explicitly wants a managed IDE/CLI/Web product and accepts its platform boundary. AIWG is the most ambitious framework, but its breadth and optional subsystems make it an operating commitment rather than a lightweight default.

## Bottom line

On the evidence available on **2026-09-27**, **GSD Core is the best documented single fit for a solo developer or tiny team building a serious long-running product**. It has the clearest end-to-end state machine and the strongest explicit story for surviving context boundaries while keeping research, plans, implementation, verification, UAT, and shipping connected. **OpenSpec** is the best lower-ceremony brownfield alternative; **BMad** is the best product-process alternative; **Superpowers** is the best focused implementation-discipline layer.

This is a fit judgment from documented capability coverage and documented failure modes, not a claim that any system guarantees correct code. Every system still requires human ownership of scope, architecture, tests, review, and production risk.
