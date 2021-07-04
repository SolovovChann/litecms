<?php

namespace Litecms\Models\Field;

/**
 * Use another table's entry
 */
class ForeignKey extends Field
{
    public $datatype = 'foreign key';
    public $mmetype = 'select';
    public $reference;

    public const CASCADE = 'cascade';
    public const RESTRICT = 'restrict';
    public const SETNULL = 'set null';
    public const DEFAULT = 'set default';
    public const NOTHING = 'no action';

    public function __construct(
        string $reference,
        array $params = []
    ) {
        $this->default = $params['default'] ?? null;
        $this->null = gettype($params['null']) === 'boolean' ? $params['null'] : null;
        $this->max = gettype($params['max']) === 'boolean' ? $params['max'] : null;
        $this->reference = $reference;

        $this->onupdate = $params['onupdate'] ?? self::CASCADE;
        $this->ondelete = $params['ondelete'] ?? self::CASCADE;
    }
}