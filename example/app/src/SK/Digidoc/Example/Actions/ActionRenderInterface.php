<?php
/**
 * Created by PhpStorm.
 * User: MihkelNortal
 * Date: 11/23/15
 * Time: 11:28 AM
 */

namespace SK\Digidoc\Example\Actions;

/**
 * Render response view given action
 *
 * Interface ActionRenderInterface
 *
 * @package SK\Digidoc\Example\Actions
 */
interface ActionRenderInterface
{
    /**
     * Render action view as response
     *
     * @return mixed
     */
    public function render();
}