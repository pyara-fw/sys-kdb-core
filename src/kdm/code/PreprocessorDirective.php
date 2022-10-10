<?php

namespace  SysKDB\kdm\code;

/**
 * PreprocessorDirective is a generic meta-model element that represents preprocessor
 * directives common to some programming languages (for example, the C language
 * preprocessor capabilities). This class is extended by several concrete meta-model
 * elements that represent several key directive types common to language preprocessors.
 * KDM representations of existing systems are expected to use concrete subclasses of
 * PreprocessorDirective, however this class itself is a concrete meta-model element and
 * can be used as an extended element with an appropriate stereotype to represent other
 * types of preprocessing directives not covered by the standard subclasses.
 *
 * Semantics of preprocessor directives in KDM is described later in this sub clause.
 *
 */
class PreprocessorDirective extends AbstractCodeElement
{
}
