<?php

namespace QUI\FAQ;

use QUI\Projects\Site;

class JsonLd
{
    public static function getJsonLdFromSite(Site | Site\Edit $site): string
    {
        $mainEntities = [];
        $project = $site->getProject();

        $jsonLd = [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => []
        ];

        try {
            $childrenIds = $site->getChildrenIdsRecursive();
        } catch (\Exception) {
            $childrenIds = [];
        }

        foreach ($childrenIds as $childId) {
            try {
                $child = $project->get((int)$childId);

                if ($child->getAttribute('type') === 'quiqqer/faq:types/entry') {
                    $answerParts = [];
                    $short = $child->getAttribute('short');
                    $content = $child->getAttribute('content');

                    if (is_scalar($short) && $short !== '') {
                        $answerParts[] = (string)$short;
                    }

                    if (is_scalar($content) && $content !== '') {
                        $answerParts[] = (string)$content;
                    }

                    $answer = implode(' ', $answerParts);

                    $answer = strip_tags(
                        $answer,
                        '<a><b><strong><i><em><br><p><div><ul><ol><li><h1><h2><h3><h4><h5><h6>'
                    );

                    $mainEntities[] = [
                        "@type" => "Question",
                        "name" => $child->getAttribute('title'), // question
                        "acceptedAnswer" => [
                            "@type" => "Answer",
                            "text" => $answer
                        ]
                    ];
                }
            } catch (\Exception) {
            }
        }

        $jsonLd['mainEntity'] = $mainEntities;

        return '<script type="application/ld+json">' . json_encode($jsonLd) . '</script>';
    }
}
