<?php

/**
 * This file contains the faq list 2025 site type
 *
 * @var QUI\Projects\Project $Project
 * @var QUI\Projects\Site $Site
 * @var QUI\Interfaces\Template\EngineInterface $Engine
 * @var QUI\Template $Template
 **/

$FAQListControl = new QUI\FAQ\Controls\CategoryList([
  'template' => $Site->getAttribute('quiqqer.faq.list2025.settings.template'),
  'showImage' => $Site->getAttribute('quiqqer.faq.list2025.settings.showImage'),
  'imageWidth' => $Site->getAttribute('quiqqer.faq.list2025.settings.imageWidth'),
  'imageAlignment' => $Site->getAttribute('quiqqer.faq.list2025.settings.imageAlignment'),
  'showTitle' => $Site->getAttribute('quiqqer.faq.list2025.settings.showTitle'),
  'showDesc' => $Site->getAttribute('quiqqer.faq.list2025.settings.showDesc'),
  'textAlignment' => $Site->getAttribute('quiqqer.faq.list2025.settings.textAlignment'),
  'showButton' => $Site->getAttribute('quiqqer.faq.list2025.settings.showButton'),
  'btnCssClass' => $Site->getAttribute('quiqqer.faq.list2025.settings.btnCssClass'),
  'btnAlignment' => $Site->getAttribute('quiqqer.faq.list2025.settings.btnAlignment'),
  'subpages' => $Site->getAttribute('quiqqer.faq.list2025.settings.subpages'),
  'parentSite' => $Site
]);

$Engine->assign([
    'FAQListControl' => $FAQListControl,
    'jsonLd' => QUI\FAQ\JsonLd::getJsonLdFromSite($Site)
]);
