<?php

namespace Modules\Content\Platform\Types;

use Modules\Content\Platform\ContentType;
use Modules\Content\Platform\Types\TaxonomyManager;


abstract class Taxonomy extends ContentType
{

    public function __construct(TaxonomyManager $manager, array $options)
    {
        parent::__construct($manager, $options);
    }
}
