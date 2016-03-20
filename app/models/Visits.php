<?php

class Visits extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

	/**
	 *
	 * @var string
	 */
	public $useragent;

    /**
     *
     * @var string
     */
    public $date_created;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'visits';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Visits[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Visits
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
