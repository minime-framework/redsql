<?php

namespace RedBeanPHP\Plugins\RedSql\Filters;

use RedBean_Facade as R;
use RedBean_QueryWriter_Oracle;

class FilterOFFSET implements FilterInterface
{

    public function validate(array $parameters)
    {
        if (!array_key_exists('value', $parameters)) {
            throw new \InvalidArgumentException("OFFSET expects an [offset] value.");
        }
    }

    public function apply(&$sql_reference, array &$values_reference, array $parameters)
    {
        $writer = R::$toolbox->getWriter();
        $values_reference[] = $parameters['value'];
        // Oracle has no support for OFFSET. ROWOFFSET is just an alias for standard ROWNUM.
        if ($writer instanceof RedBean_QueryWriter_Oracle) {
            $sql_reference .= " AND ROWOFFSET >= ? ";
            return;
        }
        $sql_reference .= " OFFSET ? ";
    }
}
