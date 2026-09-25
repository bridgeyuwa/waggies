# Images, Icons, Screenshots, and Uncontrolled Media

## Media quality is part of UI quality

A high-quality layout cannot rescue poor or mismatched imagery. Use intentionally selected photography/illustration that matches the brand, subject, crop, lighting, and content context.

Do not build a layout around placeholder art that bears no resemblance to the production asset proportions.

## Text over images needs controlled contrast

When text overlays photography, do not keep changing text color hoping it will work across a high-dynamic-range image. Control the image/background instead.

Possible techniques:

- tonal overlay,
- reduced image contrast plus adjusted brightness,
- brand colorization/duotone where appropriate,
- a contained text surface,
- subtle text glow/shadow as a supporting technique.

Always verify contrast across responsive crops and real images, not only one ideal sample.

## Respect intended icon size

A small interface icon enlarged to illustration scale usually looks coarse even if it is vector. Use artwork designed for the target scale, or place the small icon inside a larger supporting shape/surface.

Likewise, do not shrink detailed illustration-scale icons into tiny UI glyphs. Use a simplified variant.

Do not introduce a third icon family when the product already has an icon system.

## Screenshots

Do not shrink a full desktop screenshot until text/details become unreadable.

Prefer:

- a screenshot captured at a smaller responsive layout,
- a cropped/partial screenshot focused on the relevant feature,
- a simplified illustrative reconstruction when the whole product view must fit in limited space.

## User-uploaded/unpredictable media

Treat arbitrary user media as hostile to layout consistency.

Use:

- explicit aspect-ratio containers,
- `object-fit: cover` or `contain` according to content meaning,
- safe object positioning,
- dimension reservation to prevent layout shift,
- subtle inner boundary treatment when the image background can visually bleed into the page.

Do not rely on the source image's intrinsic aspect ratio when a repeated card/list requires consistent geometry.

## Accessibility and performance

- Meaningful images need useful alt text.
- Decorative images need empty alt when rendered as images.
- Avoid embedding meaningful text only inside images.
- Use responsive image sizes and modern formats where the framework supports them.
- Set dimensions/aspect ratio to avoid cumulative layout shift.
- Lazy-load below-the-fold media unless doing so harms the critical experience.

