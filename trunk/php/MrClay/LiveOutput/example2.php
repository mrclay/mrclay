<?php

require '../Html.php';
require '../LiveOutput.php';
require './Renderer.php';
require './Processor.php';

MrClay_LiveOutput::processThis('MrClay_Html highlights');
// at this point LiveOutput fetches and tokenizes this file, cut offs everything
// before "#!begin", and rewrites the rest as linear PHP and evals it, calling
// various LiveOutput methods.

#!begin


#!code
$html = new MrClay_Html;


#!code
$assoc = array(
    array('id' => 2, 'first' => '<b>B</b>íll', 'last' => 'Gátës'),
    array('id' => 3, 'first' => 'Batman', 'last' => null),
    array('id' => 4, 'first' => array(1, 'a', true), 'last' => false),
);

#!codeRender
// non-string values are passed through \$html->stringify()
$html->build_table_from_assoc_array($assoc);


#!code
?><p>Here we'll make a custom function that will be called to convert non-strings to strings.</p><?php
// custom stringify func
function myStringify($var) {
    return $var ? '<i>truthy</i>' : '<b>falsey</b>';
}

#!codeRender
$html->build_table_from_assoc_array($assoc, array(
    'stringifyFunc' => 'myStringify'
));

#!codeRender
$html->build_table_from_assoc_array($assoc, array(
    'escapeValues' => false
    ,'orientation' => 'horizontal'
));

#!code
// nicely formatted markup (slower)
$html->set_whiteSpace(true);

#!codeReturn
$html->build_table_from_assoc_array($assoc, array(
    'escapeValues' => false
    ,'orientation' => 'horizontal'
));

#!codeReturnRender
$html->build_dl_from_row($assoc[2]);

#!codeRender
$html->build_select(
	array( // attributes
		'name'=>'mySelect'
		,'multiple'=>'multiple'
		,'size'=>3
	), array( // value => display
		1 => 'Here is a select'
		,2 => 'element with'
		,3 => 'multiple selections.'
	), array( // selected values
		2,3
	)
);

#!codeReturn
// decode HTML entities
$html->hd("HTML &euml;nt&iacute;ties c&aacute;n be &lt;em&gt;de&lt;/em&gt;coded.");

#!codeReturnRender
// escape and wrap in a P element
$html->hwrap('HTML spëcial chars & áre <esc>aped.','p');

#!codeRender
// wrap doesn't escape contents
$html->wrap(
    $html->hwrap('He said & she said.', 'p') // hwrap does
    ,'blockquote'
);

