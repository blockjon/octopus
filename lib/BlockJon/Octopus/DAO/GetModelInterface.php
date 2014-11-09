<?php

namespace Octopus\DAO;

interface GetModelInterface
{
    /**
     * @return \Octopus\Model\AbstractModel
     */
    public static function getModel();
}
