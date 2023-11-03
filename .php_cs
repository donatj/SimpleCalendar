<?php

$finder = PhpCsFixer\Finder::create()
	->files()
	->in(__DIR__ . '/src')
	->in(__DIR__ . '/test')
	->in(__DIR__ . '/example')
	->name('*.php');


return PhpCsFixer\Config::create()
	->setUsingCache(true)
	->setIndent("\t")
	->setLineEnding("\n")
	//->setUsingLinter(false)
	//->setRiskyAllowed(true)
	->setRules(
		[
			'concat_space' => [
				'spacing' => 'one',
			],

			'visibility_required' => true,
			'indentation_type'    => true,
			'no_useless_return'   => true,

			'switch_case_space'              => true,
			'switch_case_semicolon_to_colon' => true,

			'array_syntax' => [ 'syntax' => 'short' ],
			'list_syntax'  => [ 'syntax' => 'short' ],

			'no_leading_import_slash'         => true,
			'no_leading_namespace_whitespace' => true,

			'no_whitespace_in_blank_line' => true,

			'phpdoc_add_missing_param_annotation' => [ 'only_untyped' => true, ],
			'phpdoc_indent'                       => true,

			'phpdoc_no_alias_tag'          => true,
			'phpdoc_no_package'            => true,
			'phpdoc_no_useless_inheritdoc' => true,

			'phpdoc_order'                   => true,
			'phpdoc_scalar'                  => true,
			'phpdoc_single_line_var_spacing' => true,

			'phpdoc_trim'                                   => true,
			'phpdoc_trim_consecutive_blank_line_separation' => true,
			
			'phpdoc_types'       => true,
			'phpdoc_types_order' => [
				'null_adjustment' => 'always_last',
				'sort_algorithm'  => 'alpha',
			],

			'short_scalar_cast' => true,

			'standardize_not_equals'          => true,
			'ternary_operator_spaces'         => true,
			'no_spaces_after_function_name'   => true,
			'no_unneeded_control_parentheses' => true,

			'return_type_declaration' => [
				'space_before' => 'one',
			],

			'single_line_after_imports'          => true,
			'single_blank_line_before_namespace' => true,
			'blank_line_after_namespace'         => true,
			'single_blank_line_at_eof'           => true,
			'ternary_to_null_coalescing'         => true,
			'whitespace_after_comma_in_array'    => true,

			'cast_spaces' => [ 'space' => 'none' ],

			'encoding' => true,

			'space_after_semicolon' => [
				'remove_in_empty_for_expressions' => true,
			],

			'align_multiline_comment' => [
				'comment_type' => 'phpdocs_like',
			],

			'blank_line_before_statement' => [
				'statements' => [ 'continue', 'try', 'switch', 'die', 'exit', 'throw', 'return' ],
			],

			'no_superfluous_phpdoc_tags' => true,
			'no_superfluous_elseif'      => true,

			'no_useless_else' => true,

			'combine_consecutive_issets'  => true,
			'escape_implicit_backslashes' => true,
			'heredoc_to_nowdoc'           => true,

			'no_empty_phpdoc'             => true,
			'no_empty_statement'          => true,
			'no_empty_comment'            => true,
			'no_extra_blank_lines'        => true,
			'no_blank_lines_after_phpdoc' => true,

			'return_assignment'          => true,
			'lowercase_static_reference' => true,

			'method_chaining_indentation' => true,

			'elseif' => true,
		]
	)
	->setFinder($finder);


