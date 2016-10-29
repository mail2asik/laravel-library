<?php
/**
* ModelTrait
*
* @author Asik
* @email  mail2asik@gmal.com
*/

namespace App\Repositories;

/**
 * Trait ModelTrait
 *
 * This trait exists so that repository and other classes can store a
 * dependent model as a property without having a re-entrancy issue
 * where queries are issued against the model object multiple times
 * in a request.
 *
 * To achieve this it stores the class name of the model in the propery
 * rather than the model name.  A new object of the model is created
 * each time the getModel() function is called.
 *
 * Without this, the following code would produce unexpected results:
 *
 * <code>
 *   $id1 = $this->model->where('id', '=', 1)->first();
 *   ...
 *   $id2 = $this->model->where('id', '=', 2)->first();
 * <code>
 *
 * If the $id1 and $id2 lines were run in the same request then the
 * second search would fail, but if they were run in different requests
 * then the search would succeed.
 *
 * Using this trait, the following code will always be correct:
 *
 * <code>
 *   $id1 = $this->getModel()->where('id', '=', 1)->first();
 *   ...
 *   $id2 = $this->getModel()->where('id', '=', 2)->first();
 * <code>
 */
trait ModelTrait
{

    protected $model;

    /**
     * Get a new instance of the model
     *
     * @return mixed
     */
    public function getModel()
    {
        return new $this->model;
    }

    /**
     * Get the name of the stored model
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->model;
    }

    /**
     * Set the model used by the class
     *
     * @param void
     */
    public function setModel($model)
    {
        $this->model = get_class($model);
    }
}
