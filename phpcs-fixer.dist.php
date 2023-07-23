<?php
$finder = PhpCsFixer\Finder::create();
if ( is_dir( __DIR__ . '/app' ) ) {
	$finder->in( __DIR__ . '/app' );
}
if ( is_dir( __DIR__ . '/templates' ) ) {
	$finder->in( __DIR__ . '/templates' );
}

$config = new PhpCsFixer\Config();
$config
	->setRiskyAllowed( true )
	->setUsingCache( false )
	->setFinder( $finder )
	->setRules(
		array(
			'@PHP80Migration:risky'      => true,
			'array_syntax'               => array( 'syntax' => 'long' ),
			'constant_case'              => array( 'case' => 'lower' ),
			'phpdoc_to_param_type'       => true,
			'phpdoc_to_return_type'      => true,
			'native_function_invocation' => array( 'include' => array( '@all' ) ),
			'native_constant_invocation' => true,
		)
	);

return $config;
