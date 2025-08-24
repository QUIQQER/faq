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
  'template' => $Site->getAttribute('template'),
  'parentSite' => $Site
]);

$Engine->assign([
    'FAQListControl' => $FAQListControl
]);
