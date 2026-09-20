<?php

namespace App\Support;

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
        preg_match_all('/<h([23])>([^<]+)<\/h[23]>/', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $headings[] = [
                'level' => (int) $match[1],
                'text' => $match[2],
                'id' => 'heading-'.$headingIndex++,
            ];
        }

        $headingIndex = 0;
        $processedContent = preg_replace_callback(
            '/<(h[23])>([^<]+)<\/\1>/',
            static function (array $match) use (&$headingIndex): string {
                $id = 'heading-'.$headingIndex++;

                return sprintf('<%s id="%s" class="scroll-mt-24">%s</%s>', $match[1], $id, $match[2], $match[1]);
            },
            $content,
        ) ?? $content;

        return [
            'content' => $processedContent,
            'headings' => $headings,
        ];
    }
}
