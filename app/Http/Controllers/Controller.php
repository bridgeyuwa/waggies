<?php

namespace App\Http\Controllers;

use App\Support\WaggiesPageHead;

abstract class Controller
{
    /**
     * Resolve configuration-backed action destinations before they reach a view component.
     *
     * @param  array<int, array<string, mixed>>  $actions
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeActionLinks(array $actions): array
    {
        return array_map(static function (array $action): array {
            if (isset($action['route'])) {
                $action['url'] = route($action['route'], $action['params'] ?? []);
                unset($action['route'], $action['params']);
            }

            if (isset($action['href'])) {
                $action['url'] = $action['href'];
                unset($action['href']);
            }

            return $action;
        }, $actions);
    }

    /**
     * Apply resource-owned metadata through Laravel Head.
     *
     * Controllers remain responsible for choosing the values and indexability
     * policy; this method only adapts those values to the installed package.
     *
     * @param  array<string, mixed>  $metadata
     * @param  array<int, array<string, mixed>>  $schemas
     */
    protected function setPageHead(array $metadata, array $schemas = []): void
    {
        app(WaggiesPageHead::class)->apply($metadata, $schemas);
    }
}
