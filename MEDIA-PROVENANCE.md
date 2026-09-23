# Waggies media provenance

The public application now serves its editorial and catalogue image references from `public/media/editorial`. The runtime does not request images from the Unsplash CDN.

The local files preserve the Unsplash photo identifiers used by the existing implementation (`photo-*.jpg`). The source URLs were the image CDN URLs already present in the application. They were copied locally on 23 September 2026 at the dimensions requested by the application, with the original query parameters retained in the migration history rather than in public runtime markup.

Before production release, Waggies must confirm the ownership or licence basis for each source image and replace any image that is not approved. This record intentionally does not claim ownership or licensing that was not supplied with the project. Seven discontinued source URLs were mapped to an existing local image with the same broad subject so the public site has no broken or remote fallback; those replacements also require editorial approval.

When replacing an asset, update the consuming config/content reference and record the new source, licence basis, usage restriction, and replacement date here. CMS-managed uploads remain governed by their Media Library collection and are not copied into this static catalogue.
