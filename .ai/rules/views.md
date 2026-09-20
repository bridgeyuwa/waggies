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

## Truthful public interactions and authoritative pricing
Public Waggies interaction is Blade-first and Alpine-first; use Livewire only when server-side state materially improves correctness, persistence, authorization, or stateful behavior. Any UI that says submitted, subscribed, or booked must have authoritative server-side acceptance. Waggies pricing definitions remain authoritative; serialize config/domain values to the browser instead of duplicating commercial rates in JavaScript. Public submissions default to non-public moderation states. Waggies owns production media: preserve legitimate existing assets, store new application-owned uploads under Waggies-managed storage, and do not begin broad media migration without an explicit decision.

## Preserve native card interaction semantics
Use one real link when a card is one navigation target. When a card has independent actions, keep the shell non-interactive and use separate native links/buttons. Never nest interactive elements or simulate native controls with card-level click handlers; generic card owns presentation while domain cards own semantics.
