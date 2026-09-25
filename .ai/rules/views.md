---
paths:
  - app/Http/Controllers/**
  - app/Models/**
  - config/waggies_pricing.php
  - database/**
  - resources/js/**
  - resources/views/**
  - routes/**
  - tests/**
---

# Views

## Asset reuse must follow ownership

Waggies owns production media: preserve legitimate existing assets, store new application-owned uploads under Waggies-managed storage, and do not begin broad media migration without an explicit decision. Reuse image assets only when their ownership, visual role, crop, and URL history support it; do not force unrelated images into a shared or editorial bucket merely to reduce file count.

## Truthful public interactions and authoritative pricing
Public Waggies interaction is Blade-first and Alpine-first; use Livewire only when server-side state materially improves correctness, persistence, authorization, or stateful behavior. Any UI that says submitted, subscribed, or booked must have authoritative server-side acceptance. Waggies pricing definitions remain authoritative; serialize config/domain values to the browser instead of duplicating commercial rates in JavaScript. Public submissions default to non-public moderation states.

## Preserve native card interaction semantics
Use one real link when a card is one navigation target. When a card has independent actions, keep the shell non-interactive and use separate native links/buttons. Never nest interactive elements or simulate native controls with card-level click handlers; generic card owns presentation while domain cards own semantics.

## Separate CMS SEO copy from indexing and sitemap policy
Treat seo_title and seo_description as copy overrides for document and social metadata only. Derive robots, canonical, Schema.org output, and sitemap eligibility from publication and indexability policy; keep is_indexable and include_in_sitemap as distinct decisions.
