<?php

namespace  SysKDB\kdm\code;

/**
 * TemplateType class is a meta-model element that represents references to
 * parameterized datatypes. The TemplateType class owns the actual parameters
 * to the datatype reference, represented by “ParameterTo” relationships.
 *
 * The TemplateType class also owns the “InstanceOf” relationship to the
 * TemplateUnit that represents the referenced parameterized datatype.
 *
 * TemplateType has the role of a Datatype.
 */
class TemplateType extends DataType
{
}
