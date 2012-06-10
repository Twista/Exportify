<?php

/**
 * exportify testing case
 * @author Michal Haták <me@twista.cz>
 */
require '../exportify.php';

$export = new \Exportify\Exportify();

// settings
$export->setTemplate('template.twt')
	->setHeader('Content-Type:text/xml');

// get data into variable
$items[] = array(
    'name' => 'test',
    'description' => 'desc',
    'url' => 'http://example.com',
    'imgurl' => 'http://example.com/img.jpg',
    'price' => 4,
    'price_vat' => 4.2,
    'ean' => '131243'
);

$items[] = array(
    'name' => 'eset',
    'url' => 'http://twista.cz',
    'imgurl' => 'http://twista.cz/image.jpg',
    'price' => 6,
    'price_vat' => 9.2,
    'ean' => '131243'
);

// set variable
$export->items = $items;

// render template
$export->render();