<?php
use Tikomatic\Registry;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\PoFileLoader;

$registry = Registry::getInstance();

//Read language from config
$config = $registry->get('config');

//Setup Translator
$translator = new Translator($config->get('language'));
$translator->setFallbackLocales(array('en'));
//$translator->addLoader('array', new ArrayLoader());
$translator->addLoader('pofile', new PoFileLoader());

/*$translator->addResource('array', array(
    'Symfony is great!' => 'J\'aime Symfony!',
), 'fr_FR');*/

$translator->addResource('pofile', APP_BASEPATH . '/../locale/fr_FR/LC_MESSAGES/default.po', 'fr_FR');
$translator->addResource('pofile', APP_BASEPATH . '/../locale/en/LC_MESSAGES/default.po', 'en');

//Insert translator into Registry
$registry->set('translator', $translator);