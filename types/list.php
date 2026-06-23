<?php

/**
 * This file contains the faq list site type
 *
 * @var QUI\Projects\Project $Project
 * @var QUI\Projects\Site $Site
 * @var QUI\Interfaces\Template\EngineInterface $Engine
 * @var QUI\Template $Template
 **/

$categories = $Site->getChildren([
    'type' => 'quiqqer/faq:types/category'
]);

$jsonLd = '';

if ($Site->getAttribute('quiqqer.faq.list.settings.useFaqStructuredData')) {
    $jsonLd = QUI\FAQ\JsonLd::getJsonLdFromSite($Site);
}

$Engine->assign([
    'categories' => $categories,
    'jsonLd' => $jsonLd
]);
