<?php

/**
 * This file contains the faq entry site type
 *
 * @var QUI\Projects\Project $Project
 * @var QUI\Projects\Site $Site
 * @var QUI\Interfaces\Template\EngineInterface $Engine
 * @var QUI\Template $Template
 **/

$Parent = $Site->getParent();

if (!($Parent instanceof QUI\Projects\Site)) {
    return;
}

$parentType = $Parent->getAttribute('type');

if (
    $parentType == 'quiqqer/faq:types/list'
    || $parentType == 'quiqqer/faq:types/category'
) {
    QUI::getRewrite()->showErrorHeader(
        303,
        $Parent->getUrlRewritten() . '#faq' . $Site->getId()
    );

    exit;
}
