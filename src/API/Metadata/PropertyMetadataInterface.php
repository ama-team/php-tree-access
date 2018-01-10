<?php

namespace AmaTeam\TreeAccess\API\Metadata;

interface PropertyMetadataInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isVirtual();

    /**
     * @return string|null
     */
    public function getGetter();

    /**
     * @return string|null
     */
    public function getSetter();
}
