<?php

/**
 * This file contains the category site type
 *
 * @var QUI\Projects\Project $Project
 * @var QUI\Projects\Site $Site
 * @var QUI\Interfaces\Template\EngineInterface $Engine
 * @var QUI\Template $Template
 **/

$entries = $Site->getChildren([
    'type' => 'quiqqer/faq:types/entry'
]);

if (!is_array($entries)) {
    $entries = [];
}

$faqTemplate = 'default';
$offset = false;
$FAQControl = null;
$useFaqStructuredData = $Site->getAttribute('quiqqer.faq.settings.useFaqStructuredData');
$faqStructuredData = ''; // html string

switch ($Site->getAttribute('quiqqer.faq.settings.template')) {
    case 'accordion':
        $faqTemplate = 'accordion';

        $FAQControl = new \QUI\FAQ\Controls\Accordion([
            'template' => $Site->getAttribute('quiqqer.faq.settings.accordion.template'),
            'columns' => (int)$Site->getAttribute('quiqqer.faq.settings.accordion.columns'),
            'listMaxWidth' => $Site->getAttribute('quiqqer.faq.settings.accordion.listMaxWidth'),
            'max' => 50,
            'stayOpen' => $Site->getAttribute('quiqqer.faq.settings.accordion.stayOpen'),
            'openFirst' => $Site->getAttribute('quiqqer.faq.settings.accordion.openFirst'),
            'parentSite' => $Site,
            'useFaqStructuredData' => $useFaqStructuredData
        ]);

        break;
    case 'default':
    default:
        $offset = intval($Site->getAttribute('quiqqer.faq.settings.offset'));
        $faqTemplate = 'default';
        if ($useFaqStructuredData) {
            $jsonSchemaEntries = [];

            foreach ($entries as $FaqSite) {
                if (!($FaqSite instanceof QUI\Projects\Site)) {
                    continue;
                }

                $short = $FaqSite->getAttribute('short');
                $content = $FaqSite->getAttribute('content');
                $shortHtml = '';
                $contentHtml = '';

                if (is_scalar($short) && $short !== '') {
                    $shortHtml = '<div class="quiqqer-faqAccordion-item-content-pageShort text-muted">' . $short . '</div>';
                }

                if (is_scalar($content) && $content !== '') {
                    $contentHtml = '<div class="quiqqer-faqAccordion-item-content-pageContent">' . $content . '</div>';
                }

                $entryContent = implode(' ', array_filter([$shortHtml, $contentHtml]));

                $entry = [
                    'entryTitle' => (string)$FaqSite->getAttribute('title'),
                    'entryContent' => $entryContent,
                ];

                $jsonSchemaEntries[] = $entry;
            }

            $FAQControl = new QUI\Bricks\Controls\Accordion([
                'entries' => $jsonSchemaEntries
            ]);

            $faqStructuredData = $FAQControl->createJSONLDFAQSchemaCode();
        }
        break;
}

$Engine->assign([
    'entries' => $entries,
    'faqTemplate' => $faqTemplate,
    'offset' => $offset,
    'FAQControl' => $FAQControl,
    'faqStructuredData' => $faqStructuredData
]);
