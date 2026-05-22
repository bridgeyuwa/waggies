# `php artisan export` — Permission Denied on `public\storage`

## Proven Facts (from diagnostic output)

### What is `public\storage`?

`fsutil reparsepoint query` confirms it is a **Windows Mount Point (junction)** with reparse tag `0xa0000003`:

```
Substitute Name: \??\C:\Users\Bridges\Herd\waggies\storage\app\public
```

### How does PHP see it?

Diagnostic results on this exact system:

| Check | Result |
|---|---|
| `file_exists('public/storage')` | `true` |
| `is_file('public/storage')` | `false` |
| `is_dir('public/storage')` | `true` |
| `is_link('public/storage')` | `false` |
| `SplFileInfo->isFile()` | `false` |
| `SplFileInfo->isDir()` | **`false`** ⚠️ |
| `SplFileInfo->isLink()` | `false` |
| `SplFileInfo->getType()` | `'unknown'` (with PHP Notice) |

> [!IMPORTANT]
> There is a **proven discrepancy** between `is_dir()` (returns `true`) and `SplFileInfo->isDir()` (returns `false`).
> PHP's `SplFileInfo` classifies the junction as type `'unknown'` — it is neither file, nor dir, nor link.

### What does `RecursiveDirectoryIterator` yield for it?

```
path=public\storage | isDir=false | isFile=false | isLink=false
```

The iterator **does yield** the `public\storage` entry. It classifies it as **not a directory**, **not a file**, and **not a link**.

### Can `file_get_contents()` read it?

```
FAILED: file_get_contents(public/storage): Failed to open stream: Permission denied
```

Confirmed crash on this exact path.

---

## Source Files Responsible

### 1. Config origin — [export.php](file:///c:/Users/Bridges/Herd/waggies/vendor/spatie/laravel-export/config/export.php#L26-L28)

```php
'include_files' => [
    'public' => '',   // ← this is the entry that causes `public/` to be scanned
],
```

There is **no published `config/export.php`** in this project. The package default is used via `mergeConfigFrom()` in [ExportServiceProvider.php](file:///c:/Users/Bridges/Herd/waggies/vendor/spatie/laravel-export/src/ExportServiceProvider.php#L15).

### 2. Pipeline wiring — [ExportServiceProvider.php:40](file:///c:/Users/Bridges/Herd/waggies/vendor/spatie/laravel-export/src/ExportServiceProvider.php#L36-L41)

```php
$this->app->make(Exporter::class)
    // ...
    ->includeFiles(config('export.include_files', []))  // passes ['public' => '']
    ->excludeFilePatterns(config('export.exclude_file_patterns', []));
```

### 3. Dispatch — [Exporter.php:110-113](file:///c:/Users/Bridges/Herd/waggies/vendor/spatie/laravel-export/src/Exporter.php#L110-L114)

```php
foreach ($this->includeFiles as $source => $target) {
    $this->dispatcher->dispatchNow(
        new IncludeFile($source, $target, $this->excludeFilePatterns)
        // $source = 'public', $target = '', $excludeFilePatterns = ['/\.php$/', '/mix-manifest\.json$/']
    );
}
```

### 4. The crash site — [IncludeFile.php](file:///c:/Users/Bridges/Herd/waggies/vendor/spatie/laravel-export/src/Jobs/IncludeFile.php)

---

## Call Chain (step-by-step, with line numbers)

```
1. php artisan export
   → ExportCommand::handle()                         [ExportCommand.php:47]
     calls $exporter->export()

2. Exporter::export()                                [Exporter.php:110]
     loops $this->includeFiles → ['public' => '']
     dispatches new IncludeFile('public', '', [...])

3. IncludeFile::handle()                             [IncludeFile.php:30-33]
     is_file('public') → false
     is_dir('public')  → true
     calls $this->exportIncludedDirectory('public', '', $destination)

4. IncludeFile::exportIncludedDirectory()            [IncludeFile.php:52-67]
     creates RecursiveDirectoryIterator('public', SKIP_DOTS)
     wraps in RecursiveIteratorIterator(SELF_FIRST)
     iterates all entries under public/

5. The iterator yields public\storage as an entry
     $item->isDir() returns FALSE (proven above)
     → does NOT skip via `continue`
     falls through to exportIncludedFile()

6. IncludeFile::exportIncludedFile()                 [IncludeFile.php:39-48]
     checks shouldExclude('public\storage')
       patterns: /\.php$/ → no match
       patterns: /mix-manifest\.json$/ → no match
     → NOT excluded

7. file_get_contents('public\storage')               [IncludeFile.php:47]
     → CRASH: "Permission denied"
```

---

## The Exact Line Where the Decision Breaks

[IncludeFile.php:58](file:///c:/Users/Bridges/Herd/waggies/vendor/spatie/laravel-export/src/Jobs/IncludeFile.php#L57-L60):

```php
foreach ($iterator as $item) {
    if ($item->isDir()) {   // ← returns FALSE for Windows junction
        continue;           // ← NOT reached
    }
    // falls through to file_get_contents() on a junction
```

The code assumes every entry from `RecursiveDirectoryIterator` is either a directory or a file. On this system, `public\storage` is a Windows junction, and `SplFileInfo` classifies it as **`unknown`** (not dir, not file, not link). It falls through the `isDir()` guard and is treated as a readable file.

---

## Minimal Fix

> [!IMPORTANT]
> This is not a regex guess. This is a config-level fix that prevents the junction from being reached at all.

**Publish the config and exclude `storage` from `include_files` explicitly:**

```bash
php artisan vendor:publish --provider="Spatie\Export\ExportServiceProvider" --tag="export-config"
```

Then edit the newly created `config/export.php` — replace the `include_files` section:

```diff
 'include_files' => [
-    'public' => '',
+    'public' => '',
+    'storage/app/public' => 'storage',
 ],
```

Wait — that still scans `public/` and hits the junction. The correct minimal fix is:

### Option A: Config-level (no vendor edit)

Publish the config, then **list individual subdirectories of `public/` instead of the whole directory**, skipping the junction entirely:

```php
'include_files' => [
    'public/build' => 'build',
    'public/css' => 'css',
    'public/fonts' => 'fonts',
    'public/js' => 'js',
    'public/.htaccess' => '.htaccess',
    'public/favicon.ico' => 'favicon.ico',
    'public/robots.txt' => 'robots.txt',
    // Map the real storage directory if you need uploaded files in the export:
    // 'storage/app/public' => 'storage',
],
```

### Option B: Fix the actual bug in `IncludeFile.php` (1-line vendor patch)

In [IncludeFile.php:57-60](file:///c:/Users/Bridges/Herd/waggies/vendor/spatie/laravel-export/src/Jobs/IncludeFile.php#L57-L60), change:

```diff
 foreach ($iterator as $item) {
-    if ($item->isDir()) {
+    if (! $item->isFile()) {
         continue;
     }
```

This inverts the logic: instead of "skip directories, process everything else", it becomes "skip everything that isn't a file". Since the junction returns `isFile() = false`, it gets skipped. This is the actual bug — the code has an incomplete type check. This would be a proper upstream PR.

### Recommendation

**Do Option B as a published override or upstream PR, plus Option A for safety.** Option B is the 1-line root-cause fix. Option A gives you explicit control over what gets exported.
