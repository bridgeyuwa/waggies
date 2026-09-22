<?php

namespace App\Support;

use Illuminate\Support\Str;

final class ArticleBodyProcessor
{
    /**
     * @return array{
     *     content: string,
     *     headings: list<array{level: int, text: string, id: string}>
     * }
     */
    public static function process(string $content): array
    {
        $headings = [];
        $headingIndex = 0;
        $content = Str::sanitizeHtml($content);
        preg_match_all('/<h([23])\b[^>]*>(.*?)<\/h\1>/is', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $headings[] = [
                'level' => (int) $match[1],
                'text' => trim(strip_tags(html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'))),
                'id' => 'heading-'.$headingIndex++,
            ];
        }

        $headingIndex = 0;
        $processedContent = preg_replace_callback(
            '/<(?<tag>h[23])(?<attributes>[^>]*)>(?<body>.*?)<\/(?P=tag)>/is',
            static function (array $match) use (&$headingIndex): string {
                $id = 'heading-'.$headingIndex++;
                $attributes = preg_replace('/\s+id=(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $match['attributes']) ?? $match['attributes'];

                return sprintf('<%s id="%s" class="scroll-mt-24"%s>%s</%s>', $match['tag'], $id, $attributes, $match['body'], $match['tag']);
            },
            $content,
        ) ?? $content;

        return [
            'content' => $processedContent,
            'headings' => $headings,
        ];
    }
}
